<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCampagnePlanificationRequest;
use App\Models\CampagnePlanification;
use App\Models\CampagneRecensement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CampagnePlanificationController extends Controller
{
    /**
     * ==========================================================
     * FORMULAIRE DE PLANIFICATION
     * ==========================================================
     */
    public function create(CampagneRecensement $campagne)
    {
        // Une seule planification par campagne
        if ($campagne->planification()->exists()) {
            return redirect()
                ->route('campagnes.planification.show', $campagne)
                ->with(
                    'info',
                    'Cette campagne possède déjà une planification.'
                );
        }

        return view(
            'campagnes.planification.create',
            compact('campagne')
        );
    }

    /**
     * ==========================================================
     * ENREGISTRER LA PLANIFICATION
     * ==========================================================
     */
    public function store(
        StoreCampagnePlanificationRequest $request,
        CampagneRecensement $campagne
    ) {
        // Sécurité : une seule planification par campagne
        if ($campagne->planification()->exists()) {
            return redirect()
                ->route('campagnes.planification.show', $campagne)
                ->with(
                    'info',
                    'Cette campagne possède déjà une planification.'
                );
        }

        $data = $request->validated();

        $activites = $data['activites'];

        DB::transaction(function () use (
            $campagne,
            $data,
            $activites
        ) {

            $planification = CampagnePlanification::create([
                'campagne_id'  => $campagne->idCampagne,
                'planifie_par' => Auth::user()->id,
                'statut'       => 'planifiee',
                'observations' => $data['observations'] ?? null,
            ]);

            foreach ($activites as $index => $activite) {

                $planification->activites()->create([
                    'ordre'       => $index + 1,
                    'libelle'     => $activite['libelle'],
                    'description' => $activite['description'] ?? null,
                    'dateDebut'   => $activite['dateDebut'],
                    'dateFin'     => $activite['dateFin'],
                    'statut'      => 'planifiee',
                ]);
            }
        });

        return redirect()
            ->route(
                'campagnes.planification.show',
                $campagne
            )
            ->with(
                'success',
                'La planification de la campagne a été créée avec succès.'
            );
    }

    /**
     * ==========================================================
     * AFFICHER LA PLANIFICATION
     * ==========================================================
     */
    public function show(CampagneRecensement $campagne)
    {
        $planification = $campagne->planification()
            ->with([
                'planifiePar',
                'activites',
            ])
            ->firstOrFail();

        return view(
            'campagnes.planification.show',
            compact(
                'campagne',
                'planification'
            )
        );
    }

    /**
     * ==========================================================
     * FORMULAIRE DE MODIFICATION
     * ==========================================================
     */
    public function edit(CampagneRecensement $campagne)
    {
        $planification = $campagne->planification()
            ->with('activites')
            ->firstOrFail();

        return view(
            'campagnes.planification.edit',
            compact(
                'campagne',
                'planification'
            )
        );
    }

    /**
     * ==========================================================
     * METTRE À JOUR LA PLANIFICATION
     * ==========================================================
     */
    public function update(
        StoreCampagnePlanificationRequest $request,
        CampagneRecensement $campagne
    ) {
        $planification = $campagne->planification()
            ->firstOrFail();

        $data = $request->validated();

        $activites = $data['activites'];

        DB::transaction(function () use (
            $planification,
            $data,
            $activites
        ) {

            $planification->update([
                'observations' => $data['observations'] ?? null,
            ]);

            // On reconstruit les activités
            $planification->activites()->delete();

            foreach ($activites as $index => $activite) {

                $planification->activites()->create([
                    'ordre'       => $index + 1,
                    'libelle'     => $activite['libelle'],
                    'description' => $activite['description'] ?? null,
                    'dateDebut'   => $activite['dateDebut'],
                    'dateFin'     => $activite['dateFin'],
                    'statut'      => 'planifiee',
                ]);
            }
        });

        return redirect()
            ->route(
                'campagnes.planification.show',
                $campagne
            )
            ->with(
                'success',
                'La planification a été mise à jour avec succès.'
            );
    }

    /**
     * ==========================================================
     * SUPPRIMER LA PLANIFICATION
     * ==========================================================
     */
    public function destroy(CampagneRecensement $campagne)
    {
        $planification = $campagne->planification()
            ->firstOrFail();

        DB::transaction(function () use ($planification) {

            $planification->activites()->delete();

            $planification->delete();
        });

        return redirect()
            ->route(
                'campagnes.show',
                $campagne
            )
            ->with(
                'success',
                'La planification a été supprimée avec succès.'
            );
    }


    
}