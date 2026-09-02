<?php

namespace App\Http\Controllers\Dpa;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlanificationPrefectoraleRequest;
use App\Models\CampagneDeploiement;
use App\Models\PlanificationPrefectorale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlanificationPrefectoraleController extends Controller
{
    /**
     * Liste des planifications préfectorales.
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
                'besoins',
            ])
            ->latest()
            ->paginate(15);

        return view(
            'dpa.planifications_prefectorales.index',
            compact('planifications', 'prefecture')
        );
    }


    /**
     * Formulaire de création d'une planification préfectorale.
     */
    public function create(CampagneDeploiement $deploiement)
    {
        $user = Auth::user();

        if (
            !is_callable([$user, 'isDpa']) ||
            !call_user_func([$user, 'isDpa']) ||
            !$user->prefectureActuelle ||
            (int) $deploiement->prefecture_id !==
                (int) $user->prefectureActuelle->idPrefecture
        ) {
            abort(403);
        }

        $deploiement->load([
            'campagne',
            'prefecture',
            'planificationPrefectorale',
        ]);

        $prefecture = $deploiement->prefecture;

        if (!$prefecture) {
            abort(404, 'La préfecture du déploiement est introuvable.');
        }

        $planification = $deploiement->planificationPrefectorale;

        if ($planification) {
            $planification->load([
                'besoins',
                'adaptationsActivites',
            ]);
        }

        return view(
            'dpa.planifications_prefectorales.create',
            [
                'deploiement' => $deploiement,
                'planification' => $planification,
                'besoins' => $planification?->besoins ?? collect(),
            ]
        );
    }


    /**
     * Enregistrer la planification préfectorale.
     */
    public function store(
        StorePlanificationPrefectoraleRequest $request,
        CampagneDeploiement $deploiement
    ) {
        $user = Auth::user();

        if (
            !is_callable([$user, 'isDpa']) ||
            !call_user_func([$user, 'isDpa']) ||
            !$user->prefectureActuelle ||
            (int) $deploiement->prefecture_id !==
                (int) $user->prefectureActuelle->idPrefecture
        ) {
            abort(403);
        }

        if ($deploiement->planificationPrefectorale) {

            return redirect()
                ->route(
                    'dpa.planifications-prefectorales.show',
                    $deploiement->planificationPrefectorale
                )
                ->with('info', 'Cette planification existe déjà.');
        }

        $validated = $request->validated();

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

            $this->enregistrerBesoins(
                $planification,
                $validated['besoins'] ?? []
            );

            return $planification;
        });

        return redirect()
            ->route(
                'dpa.planifications-prefectorales.index'
            )
            ->with('success', 'La planification préfectorale a été créée avec succès.');
    }


    /**
     * Afficher une planification préfectorale.
     */
    public function show(
        PlanificationPrefectorale $planificationPrefectorale
    ) {
        $user = Auth::user();

        if (
            !is_callable([$user, 'isDpa']) ||
            !call_user_func([$user, 'isDpa']) ||
            !$user->prefectureActuelle
        ) {
            abort(403);
        }

        $planificationPrefectorale->load([
            'deploiement.campagne',
            'deploiement.prefecture',
            'besoins',
            'adaptationsActivites',
        ]);

        if (
            (int) $planificationPrefectorale->deploiement->prefecture_id !==
                (int) $user->prefectureActuelle->idPrefecture
        ) {
            abort(403);
        }

        return view(
            'dpa.planifications_prefectorales.show',
            compact('planificationPrefectorale')
        );
    }


    /**
     * Formulaire de modification.
     */
    public function edit(
        PlanificationPrefectorale $planificationPrefectorale
    ) {
        $user = Auth::user();

        $deploiement = $planificationPrefectorale->deploiement;

        if (
            !is_callable([$user, 'isDpa']) ||
            !call_user_func([$user, 'isDpa']) ||
            !$user->prefectureActuelle ||
            !$deploiement ||
            (int) $deploiement->prefecture_id !==
                (int) $user->prefectureActuelle->idPrefecture
        ) {
            abort(403);
        }

        $deploiement->load([
            'campagne',
            'prefecture',
        ]);

        $planificationPrefectorale->load([
            'besoins',
            'adaptationsActivites',
        ]);

        return view(
            'dpa.planifications_prefectorales.edit',
            [
                'planification' => $planificationPrefectorale,
                'planificationPrefectorale' => $planificationPrefectorale,
                'deploiement' => $deploiement,
                'besoins' => $planificationPrefectorale->besoins,
            ]
        );
    }


    /**
     * Mettre à jour une planification.
     */
    public function update(
        StorePlanificationPrefectoraleRequest $request,
        PlanificationPrefectorale $planificationPrefectorale
    ) {
        $user = Auth::user();

        $planificationPrefectorale->load([
            'deploiement',
            'besoins',
        ]);

        $prefecture = $user->prefectureActuelle;

        if (
            !$prefecture ||
            (int) $planificationPrefectorale->deploiement->prefecture_id !==
                (int) $prefecture->idPrefecture
        ) {
            abort(403);
        }

        $validated = $request->validated();

        DB::transaction(function () use (
            $validated,
            $planificationPrefectorale
        ) {

            $planificationPrefectorale->update([
                'planTravail' => $validated['planTravail'] ?? null,
                'observations' => $validated['observations'] ?? null,
            ]);

            $planificationPrefectorale->besoins()->delete();

            $this->enregistrerBesoins(
                $planificationPrefectorale,
                $validated['besoins'] ?? []
            );
        });

        return redirect()
            ->route(
                'dpa.planifications-prefectorales.index',
                $planificationPrefectorale
            )
            ->with('success', 'La planification préfectorale a été mise à jour avec succès.');
    }


    /**
     * Supprimer une planification.
     */
    public function destroy(
        PlanificationPrefectorale $planificationPrefectorale
    ) {
        $user = Auth::user();

        $planificationPrefectorale->load('deploiement');

        $prefecture = $user->prefectureActuelle;

        if (
            !$prefecture ||
            (int) $planificationPrefectorale->deploiement->prefecture_id !==
                (int) $prefecture->idPrefecture
        ) {
            abort(403);
        }

        $planificationPrefectorale->delete();

        return redirect()
            ->route('dpa.planifications-prefectorales.index')
            ->with('success', 'La planification préfectorale a été supprimée avec succès.');
    }


    /**
     * Crée les lignes de besoins pour une planification.
     */
    private function enregistrerBesoins(
        PlanificationPrefectorale $planification,
        array $besoins
    ): void {

        foreach ($besoins as $besoin) {
            $planification->besoins()->create([
                'categorie' => $besoin['categorie'],
                'designation' => $besoin['designation'],
                'quantite' => $besoin['quantite'],
                'unite' => $besoin['unite'] ?? null,
                'observations' => $besoin['observations'] ?? null,
            ]);
        }
    }
}