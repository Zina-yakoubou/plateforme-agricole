<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePointGPSRequest;
use App\Http\Requests\UpdatePointGPSRequest;
use App\Models\Parcelle;
use App\Models\PointGPS;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PointGPSController extends Controller
{
    /**
     * Afficher les points GPS d'une parcelle.
     */
    public function index(Parcelle $parcelle): View
    {
        $parcelle->load([
            'exploitant',
            'pointsGPS' => function ($query) {
                $query->orderBy('ordre');
            },
        ]);

        return view(
            'points-gps.index',
            compact('parcelle')
        );
    }

    /**
     * Afficher le formulaire d'ajout d'un point GPS.
     */
    public function create(Parcelle $parcelle): View
    {
        $parcelle->load([
            'exploitant',
        ]);

        return view(
            'points-gps.create',
            compact('parcelle')
        );
    }

    /**
     * Enregistrer un point GPS.
     */
    public function store(
        StorePointGPSRequest $request,
        Parcelle $parcelle
    ): RedirectResponse {
        $pointGPS = DB::transaction(function () use (
            $request,
            $parcelle
        ) {
            /*
             * Si l'ordre n'est pas fourni correctement,
             * on utilise automatiquement le prochain ordre.
             */
            $ordre = $request->validated('ordre');

            if (!$ordre) {
                $ordre = (
                    $parcelle->pointsGPS()->max('ordre') ?? 0
                ) + 1;
            }

            return $parcelle->pointsGPS()->create([
                'latitude' =>
                    $request->validated('latitude'),

                'longitude' =>
                    $request->validated('longitude'),

                'altitude' =>
                    $request->validated('altitude'),

                'precisionGPS' =>
                    $request->validated('precisionGPS'),

                'ordre' => $ordre,
            ]);
        });

        return redirect()
            ->route(
                'parcelles.show',
                $parcelle
            )
            ->with(
                'success',
                "Le point GPS n°{$pointGPS->ordre} a été enregistré avec succès."
            );
    }

    /**
     * Afficher un point GPS.
     */
    public function show(PointGPS $pointGPS): View
    {
        $pointGPS->load([
            'parcelle.exploitant',
        ]);

        return view(
            'points-gps.show',
            compact('pointGPS')
        );
    }

    /**
     * Afficher le formulaire de modification.
     */
    public function edit(PointGPS $pointGPS): View
    {
        $pointGPS->load([
            'parcelle',
        ]);

        return view(
            'points-gps.edit',
            compact('pointGPS')
        );
    }

    /**
     * Mettre à jour un point GPS.
     */
    public function update(
        UpdatePointGPSRequest $request,
        PointGPS $pointGPS
    ): RedirectResponse {
        $pointGPS->update([
            'latitude' =>
                $request->validated('latitude'),

            'longitude' =>
                $request->validated('longitude'),

            'altitude' =>
                $request->validated('altitude'),

            'precisionGPS' =>
                $request->validated('precisionGPS'),

            'ordre' =>
                $request->validated('ordre'),
        ]);

        return redirect()
            ->route(
                'parcelles.show',
                $pointGPS->parcelle
            )
            ->with(
                'success',
                'Le point GPS a été mis à jour avec succès.'
            );
    }

    /**
     * Supprimer un point GPS.
     */
    public function destroy(
        PointGPS $pointGPS
    ): RedirectResponse {
        $parcelle = $pointGPS->parcelle;

        $pointGPS->delete();

        return redirect()
            ->route(
                'parcelles.show',
                $parcelle
            )
            ->with(
                'success',
                'Le point GPS a été supprimé avec succès.'
            );
    }
}