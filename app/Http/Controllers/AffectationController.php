<?php

namespace App\Http\Controllers;

use App\Models\Affectation;
use App\Models\User;
use App\Models\Village;
use App\Models\Prefecture;
use App\Models\CampagneRecensement;
use App\Http\Requests\StoreAffectationRequest;
use App\Http\Requests\UpdateAffectationRequest;
use Illuminate\Http\Request;

class AffectationController extends Controller
{
    /**
     * ============================================================
     * FORMULAIRE DE NOUVELLE AFFECTATION / RECONDUCTION
     * ============================================================
     *
     * Le user_id peut être transmis depuis Utilisateurs :
     *
     * /affectations/create?user_id=15
     *
     * L'ancienne affectation n'est jamais modifiée.
     * Une nouvelle affectation sera créée.
     */
    public function create(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Utilisateur pré-sélectionné
        |--------------------------------------------------------------------------
        */

        $userSelectionne = null;

        if ($request->filled('user_id')) {

            $userSelectionne = User::with('role')
                ->where('statut', true)
                ->findOrFail($request->user_id);
        }


        /*
        |--------------------------------------------------------------------------
        | Utilisateurs pouvant être affectés
        |--------------------------------------------------------------------------
        |
        | On prend les trois rôles concernés :
        | - Agent recenseur
        | - Superviseur
        | - Technicien
        |
        */

        $users = User::with('role')
            ->where('statut', true)
            ->whereHas('role', function ($query) {

                $query->whereIn('nom', [
                    'Agent recenseur',
                    'Superviseur',
                    'Technicien',
                ]);

            })
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Campagne de la nouvelle affectation
        |--------------------------------------------------------------------------
        |
        | IMPORTANT :
        | On ne demande jamais à l'utilisateur de choisir la campagne.
        |
        */

        $campagne = CampagneRecensement::where(
            'statut',
            'planifiee'
        )->first();


        /*
        |--------------------------------------------------------------------------
        | Villages
        |--------------------------------------------------------------------------
        |
        | Nécessaire uniquement pour les agents recenseurs.
        |
        */

        $villages = Village::with([
            'canton.commune.prefecture'
        ])
        ->orderBy('nom')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Préfectures
        |--------------------------------------------------------------------------
        |
        | Nécessaire pour superviseurs et techniciens.
        |
        */

        $prefectures = Prefecture::orderBy('nom')->get();


        /*
        |--------------------------------------------------------------------------
        | Retour vers le formulaire
        |--------------------------------------------------------------------------
        */

        return view(
            'affectations.create',
            compact(
                'users',
                'userSelectionne',
                'campagne',
                'villages',
                'prefectures'
            )
        );
    }


    /**
     * ============================================================
     * ENREGISTRER UNE NOUVELLE AFFECTATION
     * ============================================================
     *
     * Cette méthode sert aussi pour une reconduction.
     *
     * L'ancienne affectation reste dans la base.
     * Une nouvelle ligne est créée.
     */
    public function store(StoreAffectationRequest $request)
    {
        $data = $request->validated();


        /*
        |--------------------------------------------------------------------------
        | Utilisateur
        |--------------------------------------------------------------------------
        */

        $user = User::with('role')
            ->findOrFail($data['user_id']);


        /*
        |--------------------------------------------------------------------------
        | Vérifier que le compte est actif
        |--------------------------------------------------------------------------
        */

        if (!$user->statut) {

            return back()
                ->withErrors([
                    'user_id' =>
                        'Ce compte utilisateur est désactivé.'
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier le rôle
        |--------------------------------------------------------------------------
        */

        $rolesAutorises = [
            'Agent recenseur',
            'Superviseur',
            'Technicien',
        ];

        if (
            !$user->role ||
            !in_array($user->role->nom, $rolesAutorises)
        ) {

            return back()
                ->withErrors([
                    'user_id' =>
                        'Ce rôle ne peut pas recevoir une affectation.'
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Campagne
        |--------------------------------------------------------------------------
        |
        | La campagne est déterminée par le serveur.
        |
        */

        $campagne = CampagneRecensement::where(
            'statut',
            'planifiee'
        )->first();


        if (!$campagne) {

            return back()
                ->withErrors([
                    'campagne' =>
                        'Aucune campagne planifiée n\'est disponible pour cette affectation.'
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Déterminer le type de zone selon le rôle
        |--------------------------------------------------------------------------
        */

        if ($user->role->nom === 'Agent recenseur') {

            /*
             * Agent recenseur → village obligatoire
             */

            if (empty($data['village_id'])) {

                return back()
                    ->withErrors([
                        'village_id' =>
                            'Veuillez sélectionner un village.'
                    ])
                    ->withInput();
            }


            $village = Village::find(
                $data['village_id']
            );

            if (!$village) {

                return back()
                    ->withErrors([
                        'village_id' =>
                            'Le village sélectionné n\'existe pas.'
                    ])
                    ->withInput();
            }

            /*
             * Récupération automatique de la hiérarchie.
             */

            $cantonId = $village->canton_id;

            $prefectureId =
                $village
                    ->canton
                    ->commune
                    ->prefecture_id;

        } else {

            /*
             * Superviseur / Technicien → préfecture
             */

            if (empty($data['prefecture_id'])) {

                return back()
                    ->withErrors([
                        'prefecture_id' =>
                            'Veuillez sélectionner une préfecture.'
                    ])
                    ->withInput();
            }

            $prefecture = Prefecture::find(
                $data['prefecture_id']
            );

            if (!$prefecture) {

                return back()
                    ->withErrors([
                        'prefecture_id' =>
                            'La préfecture sélectionnée n\'existe pas.'
                    ])
                    ->withInput();
            }

            $villageId = null;
            $cantonId = null;
            $prefectureId = $prefecture->idPrefecture;
        }


        /*
        |--------------------------------------------------------------------------
        | Vérification d'une affectation identique
        |--------------------------------------------------------------------------
        |
        | On empêche seulement le doublon dans la même campagne.
        | Les anciennes campagnes restent évidemment conservées.
        |
        */

        $existe = Affectation::where(
            'user_id',
            $user->id
        )
        ->where(
            'campagne_id',
            $campagne->idCampagne
        )
        ->where(
            'statut',
            'ACTIVE'
        )
        ->when(
            $user->role->nom === 'Agent recenseur',
            function ($query) use ($data) {

                $query->where(
                    'village_id',
                    $data['village_id']
                );

            }
        )
        ->when(
            $user->role->nom !== 'Agent recenseur',
            function ($query) use ($data) {

                $query->where(
                    'prefecture_id',
                    $data['prefecture_id']
                );

            }
        )
        ->exists();


        if ($existe) {

            return back()
                ->withErrors([
                    'user_id' =>
                        'Cet utilisateur possède déjà cette affectation pour la campagne sélectionnée.'
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Générer la référence
        |--------------------------------------------------------------------------
        */

        $reference =
            'AFF-' . now()->format('YmdHis');


        /*
        |--------------------------------------------------------------------------
        | Création de la nouvelle affectation
        |--------------------------------------------------------------------------
        */

        Affectation::create([

            'reference' =>
                $reference,

            'user_id' =>
                $user->id,

            'campagne_id' =>
                $campagne->idCampagne,

            'prefecture_id' =>
                $prefectureId,

            'canton_id' =>
                $cantonId ?? null,

            'village_id' =>
                $villageId ?? ($data['village_id'] ?? null),

            'dateDebut' =>
                $data['dateDebut'] ?? now()->toDateString(),

            'dateFin' =>
                $data['dateFin'] ?? null,

            'statut' =>
                'ACTIVE',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Retour
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('users.show', $user->id)
            ->with(
                'success',
                'L\'utilisateur a été reconduit avec succès. Une nouvelle affectation a été créée.'
            );
    }
}