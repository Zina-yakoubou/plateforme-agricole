<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParcelleRequest;
use App\Http\Requests\UpdateParcelleRequest;
use App\Models\Exploitant;
use App\Models\Parcelle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ParcelleController extends Controller
{
    /**
     * Afficher la liste des parcelles d'un exploitant.
     */
    public function index(Exploitant $exploitant): View
    {
        $exploitant->load([
            'menage.maison.village',
            'parcelles',
        ]);

        return view(
            'parcelles.index',
            compact('exploitant')
        );
    }

    /**
     * Afficher le formulaire de création d'une parcelle.
     */
    public function create(Exploitant $exploitant): View
    {
        $exploitant->load([
            'menage.maison.village',
        ]);

        return view(
            'parcelles.create',
            compact('exploitant')
        );
    }

    /**
     * Enregistrer une parcelle.
     */
    public function store(
        StoreParcelleRequest $request,
        Exploitant $exploitant
    ): RedirectResponse {
        $parcelle = DB::transaction(function () use (
            $request,
            $exploitant
        ) {
            /*
             * Le numéro de parcelle est unique pour chaque exploitant.
             *
             * Exemple :
             * EXP-001-P01
             * EXP-001-P02
             */
            $numero = $exploitant->parcelles()->count() + 1;

            $numeroParcelle = $exploitant->uid
                . '-P'
                . str_pad(
                    $numero,
                    2,
                    '0',
                    STR_PAD_LEFT
                );

            return $exploitant->parcelles()->create([
                'uid' => (string) Str::uuid(),

                'numeroParcelle' => $numeroParcelle,

                'superficie' =>
                    $request->validated('superficie'),

                'typeSol' =>
                    $request->validated('typeSol'),

                'modeFaireValoir' =>
                    $request->validated('modeFaireValoir'),

                'modeIrrigation' =>
                    $request->validated('modeIrrigation', 'pluvial'),

                'estCultivee' =>
                    $request->boolean('estCultivee'),

                'estJachere' =>
                    $request->boolean('estJachere'),

                'presenceArbres' =>
                    $request->boolean('presenceArbres'),

                'observations' =>
                    $request->validated('observations'),

                'statut' =>
                    $request->validated(
                        'statut',
                        'brouillon'
                    ),
            ]);
        });

        return redirect()
            ->route(
                'exploitants.show',
                $exploitant
            )
            ->with(
                'success',
                "La parcelle {$parcelle->numeroParcelle} a été enregistrée avec succès."
            );
    }

    /**
     * Afficher une parcelle.
     */
    public function show(Parcelle $parcelle): View
    {
        $parcelle->load([
            'exploitant.menage.maison.village.canton.commune',
            'pointsGPS',
            'cultures',
        ]);

        return view(
            'parcelles.show',
            compact('parcelle')
        );
    }

    /**
     * Afficher le formulaire de modification.
     */
    public function edit(Parcelle $parcelle): View
    {
        $parcelle->load([
            'exploitant',
        ]);

        return view(
            'parcelles.edit',
            compact('parcelle')
        );
    }

    /**
     * Mettre à jour une parcelle.
     */
    public function update(
        UpdateParcelleRequest $request,
        Parcelle $parcelle
    ): RedirectResponse {
        $parcelle->update([
            'numeroParcelle' =>
                $request->validated('numeroParcelle'),

            'superficie' =>
                $request->validated('superficie'),

            'typeSol' =>
                $request->validated('typeSol'),

            'modeFaireValoir' =>
                $request->validated('modeFaireValoir'),

            'modeIrrigation' =>
                $request->validated('modeIrrigation'),

            'estCultivee' =>
                $request->boolean('estCultivee'),

            'estJachere' =>
                $request->boolean('estJachere'),

            'presenceArbres' =>
                $request->boolean('presenceArbres'),

            'observations' =>
                $request->validated('observations'),

            'statut' =>
                $request->validated('statut'),
        ]);

        return redirect()
            ->route(
                'parcelles.show',
                $parcelle
            )
            ->with(
                'success',
                'La parcelle a été mise à jour avec succès.'
            );
    }

    /**
     * Supprimer une parcelle.
     */
    public function destroy(
        Parcelle $parcelle
    ): RedirectResponse {
        $exploitant = $parcelle->exploitant;

        $parcelle->delete();

        return redirect()
            ->route(
                'exploitants.show',
                $exploitant
            )
            ->with(
                'success',
                'La parcelle a été supprimée avec succès.'
            );
    }
}

