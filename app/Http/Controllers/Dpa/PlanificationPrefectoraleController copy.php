<?php

namespace App\Http\Controllers\Dpa;

use App\Http\Controllers\Controller;
use App\Models\CampagneDeploiement;
use App\Models\PlanificationPrefectorale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PlanificationTerritoire;
use Illuminate\Support\Facades\DB;

class PlanificationPrefectoraleController extends Controller
{

    /**
     * Afficher le formulaire de planification préfectorale.
     */

        public function index()
    {
        $user = Auth::user();

        $prefecture = $user->prefectureActuelle;

        if (!$prefecture) {
            abort(403, 'Aucune préfecture n’est rattachée à cet utilisateur.');
        }

        $planifications = PlanificationPrefectorale::with([
                'deploiement.campagne',
                'deploiement.prefecture',
                'territoires',
                'besoins',
            ])
            ->latest()
            ->paginate(15);

        return view(
            'dpa.planifications_prefectorales.index',
            compact('planifications', 'prefecture')
        );
    }


       
    public function create(CampagneDeploiement $deploiement)
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | AUTORISATION
        |--------------------------------------------------------------------------
        */

        if (
            !is_callable([$user, 'isDpa']) ||
            !call_user_func([$user, 'isDpa']) ||
            !$user->prefectureActuelle ||
            $deploiement->prefecture_id !== $user->prefectureActuelle->idPrefecture
        ) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | CHARGEMENT DU DÉPLOIEMENT
        |--------------------------------------------------------------------------
        */

        $deploiement->load([
            'campagne',
            'prefecture.communes.cantons.villages',
            'planificationPrefectorale',
        ]);

        /*
        |--------------------------------------------------------------------------
        | PRÉFECTURE
        |--------------------------------------------------------------------------
        */

        $prefecture = $deploiement->prefecture;

        if (!$prefecture) {
            abort(404, 'La préfecture du déploiement est introuvable.');
        }

        /*
        |--------------------------------------------------------------------------
        | TERRITOIRES
        |--------------------------------------------------------------------------
        |
        | On reste strictement dans la préfecture du déploiement.
        |
        */

        $communes = $prefecture->communes;

        $cantons = $communes
            ->flatMap(function ($commune) {
                return $commune->cantons;
            })
            ->values();

        $villages = $cantons
            ->flatMap(function ($canton) {
                return $canton->villages;
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | PLANIFICATION EXISTANTE
        |--------------------------------------------------------------------------
        */

        $planification = $deploiement->planificationPrefectorale;

        if ($planification) {
            $planification->load([
                'adaptationsActivites',
                'territoires',
                'besoins',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | AFFICHAGE
        |--------------------------------------------------------------------------
        */

        return view(
            'dpa.planifications_prefectorales.create',
            [
                'deploiement' => $deploiement,
                'planification' => $planification,
                'communes' => $communes,
                'cantons' => $cantons,
                'villages' => $villages,
                'besoins' => $planification?->besoins ?? collect(),
            ]
        );
    }

    /**
     * Enregistrer la planification préfectorale.
     */
   

   public function store(Request $request, CampagneDeploiement $deploiement)
{
    $user = Auth::user();

    /*
    |--------------------------------------------------------------------------
    | AUTORISATION
    |--------------------------------------------------------------------------
    */

    if (
        !is_callable([$user, 'isDpa']) ||
        !call_user_func([$user, 'isDpa']) ||
        !$user->prefectureActuelle ||
        $deploiement->prefecture_id !== $user->prefectureActuelle->idPrefecture
    ) {
        abort(403);
    }

    /*
    |--------------------------------------------------------------------------
    | EMPÊCHER LES DOUBLONS
    |--------------------------------------------------------------------------
    */

    if ($deploiement->planificationPrefectorale) {
        return redirect()
            ->route(
                'dpa.planifications-prefectorales.show',
                $deploiement->planificationPrefectorale
            )
            ->with(
                'info',
                'Cette planification existe déjà.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([
        'planTravail' => [
            'nullable',
            'string',
        ],

        'observations' => [
            'nullable',
            'string',
        ],

        'canton_ids' => [
            'nullable',
            'array',
        ],

        'canton_ids.*' => [
            'integer',
            'exists:cantons,idCanton',
        ],

        'village_ids' => [
            'nullable',
            'array',
        ],

        'village_ids.*' => [
            'integer',
            'exists:villages,idVillage',
        ],

        'besoins' => [
            'nullable',
            'array',
        ],

        'besoins.*.categorie' => [
            'required',
            'string',
            'max:100',
        ],

        'besoins.*.designation' => [
            'required',
            'string',
            'max:255',
        ],

        'besoins.*.quantite' => [
            'required',
            'numeric',
            'min:0',
        ],

        'besoins.*.unite' => [
            'nullable',
            'string',
            'max:50',
        ],

        'besoins.*.observations' => [
            'nullable',
            'string',
            'max:1000',
        ],
    ]);

    /*
    |--------------------------------------------------------------------------
    | CRÉATION
    |--------------------------------------------------------------------------
    */

    $planification = DB::transaction(function () use (
        $validated,
        $deploiement,
        $user
    ) {

        $planification = PlanificationPrefectorale::create([
            'deploiement_id' => $deploiement->idDeploiement,
            'planifie_par' => $user->id,
            'planTravail' => $validated['planTravail'] ?? null,
            'observations' => $validated['observations'] ?? null,
            'statut' => 'planifier',
        ]);

        /*
        |--------------------------------------------------------------------------
        | CANTONS
        |--------------------------------------------------------------------------
        */

        foreach ($validated['canton_ids'] ?? [] as $cantonId) {

            PlanificationTerritoire::create([
                'planification_prefectorale_id'
                    => $planification->idPlanificationPrefectorale,

                'canton_id' => $cantonId,

                'village_id' => null,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | VILLAGES
        |--------------------------------------------------------------------------
        */

        foreach ($validated['village_ids'] ?? [] as $villageId) {

            PlanificationTerritoire::create([
                'planification_prefectorale_id'
                    => $planification->idPlanificationPrefectorale,

                'canton_id' => null,

                'village_id' => $villageId,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | BESOINS
        |--------------------------------------------------------------------------
        */

        foreach ($validated['besoins'] ?? [] as $besoin) {

            $planification->besoins()->create([
                'categorie' => $besoin['categorie'],
                'designation' => $besoin['designation'],
                'quantite' => $besoin['quantite'],
                'unite' => $besoin['unite'] ?? null,
                'observations' => $besoin['observations'] ?? null,
            ]);
        }

        return $planification;
    });

    /*
    |--------------------------------------------------------------------------
    | REDIRECTION
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route(
            'dpa.planifications-prefectorales.index',
            $planification
        )
        ->with(
            'success',
            'La planification préfectorale a été créée avec succès.'
        );
}


    public function show(
        PlanificationPrefectorale $planificationPrefectorale
    ) {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Autorisation
        |--------------------------------------------------------------------------
        */

        if (
            ! is_callable([$user, 'isDpa']) ||
            ! call_user_func([$user, 'isDpa']) ||
            ! $user->prefectureActuelle
        ) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Charger les données
        |--------------------------------------------------------------------------
        */

        $planificationPrefectorale->load([
            'deploiement.campagne',
            'deploiement.prefecture',
            'territoires',
            'besoins',
            'adaptationsActivites',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Vérifier que la planification appartient à la préfecture du DPA
        |--------------------------------------------------------------------------
        */

        if (
            $planificationPrefectorale->deploiement->prefecture_id
            !== $user->prefectureActuelle->idPrefecture
        ) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Affichage
        |--------------------------------------------------------------------------
        */

        return view(
            'dpa.planifications_prefectorales.show',
            compact('planificationPrefectorale')
        );
    }


    
    /**
     * Afficher le formulaire de modification de la planification préfectorale.
     */
        public function edit(PlanificationPrefectorale $planificationPrefectorale)
    {
        $user = Auth::user();

        $deploiement = $planificationPrefectorale->deploiement;

        /*
        |--------------------------------------------------------------------------
        | Vérifier que le déploiement appartient à la préfecture du DPA
        |--------------------------------------------------------------------------
        */

        if (
            !is_callable([$user, 'isDpa']) ||
            !call_user_func([$user, 'isDpa']) ||
            !$user->prefectureActuelle ||
            !$deploiement ||
            $deploiement->prefecture_id !== $user->prefectureActuelle->idPrefecture
        ) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Charger le déploiement
        |--------------------------------------------------------------------------
        */

        $deploiement->load([
            'campagne.planification.activites',
            'prefecture.communes.cantons.villages',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Préfecture
        |--------------------------------------------------------------------------
        */

        $prefecture = $deploiement->prefecture;

        if (!$prefecture) {
            abort(404, 'La préfecture du déploiement est introuvable.');
        }

        /*
        |--------------------------------------------------------------------------
        | Territoires disponibles
        |--------------------------------------------------------------------------
        */

        $communes = $prefecture->communes;

        $cantons = $communes
            ->flatMap(function ($commune) {
                return $commune->cantons;
            })
            ->values();

        $villages = $cantons
            ->flatMap(function ($canton) {
                return $canton->villages;
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Territoires déjà sélectionnés
        |--------------------------------------------------------------------------
        */

        $planificationPrefectorale->load([
            'territoires.canton',
            'territoires.village',
            'besoins',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Affichage
        |--------------------------------------------------------------------------
        */

        return view(
            'dpa.planifications_prefectorales.edit',
            compact(
                'deploiement',
                'planificationPrefectorale',
                'prefecture',
                'communes',
                'cantons',
                'villages'
            )
        );
    }

    
   public function update(
    Request $request,
    PlanificationPrefectorale $planificationPrefectorale
) {
    $user = Auth::user();

    $deploiement = $planificationPrefectorale->deploiement;

    /*
    |--------------------------------------------------------------------------
    | AUTORISATION
    |--------------------------------------------------------------------------
    */

    if (
        !is_callable([$user, 'isDpa']) ||
        !call_user_func([$user, 'isDpa']) ||
        !$user->prefectureActuelle ||
        !$deploiement ||
        $deploiement->prefecture_id !== $user->prefectureActuelle->idPrefecture
    ) {
        abort(403);
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([
        'planTravail' => [
            'nullable',
            'string',
        ],

        'observations' => [
            'nullable',
            'string',
        ],

        'canton_ids' => [
            'nullable',
            'array',
        ],

        'canton_ids.*' => [
            'integer',
            'exists:cantons,idCanton',
        ],

        'village_ids' => [
            'nullable',
            'array',
        ],

        'village_ids.*' => [
            'integer',
            'exists:villages,idVillage',
        ],

        'besoins' => [
            'nullable',
            'array',
        ],

        'besoins.*.categorie' => [
            'required',
            'string',
            'max:100',
        ],

        'besoins.*.designation' => [
            'required',
            'string',
            'max:255',
        ],

        'besoins.*.quantite' => [
            'required',
            'numeric',
            'min:0',
        ],

        'besoins.*.unite' => [
            'nullable',
            'string',
            'max:50',
        ],

        'besoins.*.observations' => [
            'nullable',
            'string',
            'max:1000',
        ],
    ]);

    /*
    |--------------------------------------------------------------------------
    | MISE À JOUR
    |--------------------------------------------------------------------------
    */

    DB::transaction(function () use (
        $validated,
        $planificationPrefectorale
    ) {

        /*
        |--------------------------------------------------------------------------
        | INFORMATIONS GÉNÉRALES
        |--------------------------------------------------------------------------
        */

        $planificationPrefectorale->update([
            'planTravail' => $validated['planTravail'] ?? null,
            'observations' => $validated['observations'] ?? null,
        ]);

        /*
        |--------------------------------------------------------------------------
        | SUPPRESSION DES ANCIENS TERRITOIRES
        |--------------------------------------------------------------------------
        */

        $planificationPrefectorale
            ->territoires()
            ->delete();

        /*
        |--------------------------------------------------------------------------
        | NOUVEAUX CANTONS
        |--------------------------------------------------------------------------
        */

        foreach ($validated['canton_ids'] ?? [] as $cantonId) {

            PlanificationTerritoire::create([
                'planification_prefectorale_id'
                    => $planificationPrefectorale
                        ->idPlanificationPrefectorale,

                'canton_id' => $cantonId,

                'village_id' => null,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | NOUVEAUX VILLAGES
        |--------------------------------------------------------------------------
        */

        foreach ($validated['village_ids'] ?? [] as $villageId) {

            PlanificationTerritoire::create([
                'planification_prefectorale_id'
                    => $planificationPrefectorale
                        ->idPlanificationPrefectorale,

                'canton_id' => null,

                'village_id' => $villageId,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | SUPPRESSION DES ANCIENS BESOINS
        |--------------------------------------------------------------------------
        */

        $planificationPrefectorale
            ->besoins()
            ->delete();

        /*
        |--------------------------------------------------------------------------
        | NOUVEAUX BESOINS
        |--------------------------------------------------------------------------
        */

        foreach ($validated['besoins'] ?? [] as $besoin) {

            $planificationPrefectorale->besoins()->create([
                'categorie' => $besoin['categorie'],
                'designation' => $besoin['designation'],
                'quantite' => $besoin['quantite'],
                'unite' => $besoin['unite'] ?? null,
                'observations' => $besoin['observations'] ?? null,
            ]);
        }
    });

    /*
    |--------------------------------------------------------------------------
    | REDIRECTION
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route(
            'dpa.planifications-prefectorales.index',
            $planificationPrefectorale
        )
        ->with(
            'success',
            'La planification préfectorale a été mise à jour avec succès.'
        );
}
}