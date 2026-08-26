<?php

namespace App\Http\Controllers;

use App\Models\CampagnePlanification;
use App\Models\PlanificationEtape;
use App\Models\RattachementPrefecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanificationEtapeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTE DES ÉTAPES D'UNE PLANIFICATION
    |--------------------------------------------------------------------------
    */

    public function index(CampagnePlanification $planification)
    {
        $this->autoriserPlanification($planification);

        $planification->load([
            'campagne',
            'prefecture',
            'etapes' => function ($query) {
                $query->orderBy('ordre');
            }
        ]);

        return view(
            'dpa.planification.etapes.index',
            compact('planification')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FORMULAIRE DE CRÉATION
    |--------------------------------------------------------------------------
    */

    public function create(CampagnePlanification $planification)
    {
        $this->autoriserPlanification($planification);

        if ($planification->statut !== 'brouillon') {
            return redirect()
                ->route('dpa.planification.show', $planification)
                ->with(
                    'error',
                    'Cette planification ne peut plus être modifiée.'
                );
        }

        $planification->load('campagne');

        return view(
            'dpa.planification.etapes.create',
            compact('planification')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ENREGISTRER UNE ÉTAPE
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        CampagnePlanification $planification
    ) {
        $this->autoriserPlanification($planification);

        if ($planification->statut !== 'brouillon') {
            return back()->with(
                'error',
                'Impossible d’ajouter une étape sur une planification validée.'
            );
        }

        $validated = $request->validate([
            'libelle' => ['required', 'string', 'max:255'],

            'description' => ['nullable', 'string'],

            'ordre' => [
                'required',
                'integer',
                'min:1'
            ],

            'dateDebut' => [
                'required',
                'date'
            ],

            'dateFin' => [
                'required',
                'date',
                'after_or_equal:dateDebut'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | CONTRÔLE DES DATES
        |--------------------------------------------------------------------------
        */

        if (
            $validated['dateDebut']
            < $planification->dateDebut->format('Y-m-d H:i:s')
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'dateDebut' =>
                        'La date de début doit être comprise dans la période de planification.'
                ]);
        }

        if (
            $planification->dateFin &&
            $validated['dateFin']
            > $planification->dateFin->format('Y-m-d H:i:s')
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'dateFin' =>
                        'La date de fin dépasse la période de planification.'
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | ORDRE UNIQUE
        |--------------------------------------------------------------------------
        */

        $ordreExiste = PlanificationEtape::query()
            ->where('planification_id', $planification->idPlanification)
            ->where('ordre', $validated['ordre'])
            ->exists();

        if ($ordreExiste) {
            return back()
                ->withInput()
                ->withErrors([
                    'ordre' => 'Cet ordre est déjà utilisé.'
                ]);
        }

        PlanificationEtape::create([
            'planification_id' => $planification->idPlanification,

            'libelle' => $validated['libelle'],

            'description' => $validated['description'],

            'ordre' => $validated['ordre'],

            'dateDebut' => $validated['dateDebut'],

            'dateFin' => $validated['dateFin'],

            'statut' => 'planifiee',
        ]);

        return redirect()
            ->route(
                'dpa.planification.etapes.index',
                $planification
            )
            ->with(
                'success',
                'Étape ajoutée avec succès.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | FORMULAIRE DE MODIFICATION
    |--------------------------------------------------------------------------
    */

    public function edit(PlanificationEtape $etape)
    {
        $planification = $etape->planification;

        $this->autoriserPlanification($planification);

        if ($planification->statut !== 'brouillon') {
            return redirect()
                ->route('dpa.planification.show', $planification)
                ->with(
                    'error',
                    'Cette planification ne peut plus être modifiée.'
                );
        }

        return view(
            'dpa.planification.etapes.edit',
            compact(
                'planification',
                'etape'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | METTRE À JOUR UNE ÉTAPE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        PlanificationEtape $etape
    ) {
        $planification = $etape->planification;

        $this->autoriserPlanification($planification);

        if ($planification->statut !== 'brouillon') {
            return back()->with(
                'error',
                'Cette planification ne peut plus être modifiée.'
            );
        }

        $validated = $request->validate([
            'libelle' => ['required', 'string', 'max:255'],

            'description' => ['nullable', 'string'],

            'ordre' => [
                'required',
                'integer',
                'min:1'
            ],

            'dateDebut' => [
                'required',
                'date'
            ],

            'dateFin' => [
                'required',
                'date',
                'after_or_equal:dateDebut'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | CONTRÔLE DES DATES
        |--------------------------------------------------------------------------
        */

        if (
            $validated['dateDebut']
            < $planification->dateDebut->format('Y-m-d H:i:s')
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'dateDebut' =>
                        'La date de début doit être comprise dans la période de planification.'
                ]);
        }

        if (
            $planification->dateFin &&
            $validated['dateFin']
            > $planification->dateFin->format('Y-m-d H:i:s')
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'dateFin' =>
                        'La date de fin dépasse la période de planification.'
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | ORDRE UNIQUE
        |--------------------------------------------------------------------------
        */

        $ordreExiste = PlanificationEtape::query()
            ->where('planification_id', $planification->idPlanification)
            ->where('ordre', $validated['ordre'])
            ->where('idEtape', '!=', $etape->idEtape)
            ->exists();

        if ($ordreExiste) {
            return back()
                ->withInput()
                ->withErrors([
                    'ordre' => 'Cet ordre est déjà utilisé.'
                ]);
        }

        $etape->update([
            'libelle' => $validated['libelle'],

            'description' => $validated['description'],

            'ordre' => $validated['ordre'],

            'dateDebut' => $validated['dateDebut'],

            'dateFin' => $validated['dateFin'],
        ]);

        return redirect()
            ->route(
                'dpa.planification.etapes.index',
                $planification
            )
            ->with(
                'success',
                'Étape mise à jour avec succès.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SUPPRIMER UNE ÉTAPE
    |--------------------------------------------------------------------------
    */

    public function destroy(PlanificationEtape $etape)
    {
        $planification = $etape->planification;

        $this->autoriserPlanification($planification);

        if ($planification->statut !== 'brouillon') {
            return back()->with(
                'error',
                'Impossible de supprimer une étape après validation.'
            );
        }

        $etape->delete();

        return redirect()
            ->route(
                'dpa.planification.etapes.index',
                $planification
            )
            ->with(
                'success',
                'Étape supprimée avec succès.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | CONTRÔLE DE SÉCURITÉ TERRITORIAL
    |--------------------------------------------------------------------------
    */

    private function autoriserPlanification(
        CampagnePlanification $planification
    ): void {

        $user = Auth::user();

        $rattachement = RattachementPrefecture::query()
            ->where('user_id', $user->id)
            ->where('statut', 'actif')
            ->whereNull('dateFin')
            ->first();

        if (!$rattachement) {
            abort(
                403,
                'Aucun rattachement préfectoral actif.'
            );
        }

        if (
            (int) $planification->prefecture_id !==
            (int) $rattachement->prefecture_id
        ) {
            abort(
                403,
                'Cette planification ne concerne pas votre préfecture.'
            );
        }
    }
}