<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaisonRequest;
use App\Http\Requests\UpdateMaisonRequest;
use App\Models\Maison;
use App\Models\Village;
use App\Services\ReferenceGeneratorService;
use Illuminate\Support\Str;

class MaisonController extends Controller
{
    public function __construct(
        protected ReferenceGeneratorService $referenceGenerator
    ) {
    }

    /**
     * Liste des maisons d'un village.
     */
    // public function index(Village $village)
    // {
    //     $maisons = $village->maisons()
    //         ->withCount('menages')
    //         ->orderBy('idMaison')
    //         ->paginate(10);

    //     return view('maisons.index', compact(
    //         'village',
    //         'maisons'
    //     ));
    // }

        public function index(Village $village)
    {
        $maisons = $village->maisons()
            ->withCount('menages')
            ->orderBy('idMaison')
            ->paginate(5);

        return view('maisons.index', compact(
            'village',
            'maisons'
        ));
    }

    /**
     * Formulaire de création.
     */
    public function create(Village $village)
    {
        return view('maisons.create', compact('village'));
    }

    /**
     * Enregistrement d'une maison.
     */
    public function store(
        StoreMaisonRequest $request,
        Village $village
    ) {
        /*
         * Génération de la référence métier.
         *
         * Exemple :
         * KPE-M-00001
         * KPE-M-00002
         */
        $numeroMaison = $this->referenceGenerator->generate(
            type: 'maison',
            parentType: 'village',
            parentId: $village->idVillage,
            codeParent: $village->code,
            prefix: 'M',
            padding: 5
        );

        /*
         * Génération de l'identifiant technique unique.
         *
         * Cet UID ne change jamais pendant la vie
         * de la maison et pourra servir à la
         * synchronisation hors ligne.
         */
        $uid = (string) Str::uuid();

        $maison = $village->maisons()->create([
            'uid' => $uid,
            'numeroMaison' => $numeroMaison,
            'adresse' => $request->validated('adresse'),
        ]);

        return redirect()
            ->route(
                'villages.maisons.index',
                $village
            )
            ->with(
                'success',
                "La maison {$maison->numeroMaison} a été enregistrée avec succès."
            );
    }

    /**
     * Affichage d'une maison.
     */
    public function show(Maison $maison)
    {
        $maison->load([
            'village',
            'menages',
        ]);

        return view('maisons.show', compact('maison'));
    }

    /**
     * Formulaire de modification.
     */
    public function edit(Maison $maison)
    {
        $maison->load('village');

        return view('maisons.edit', compact('maison'));
    }

    /**
     * Modification d'une maison.
     */
    public function update(
        UpdateMaisonRequest $request,
        Maison $maison
    ) {
        $maison->update([
            'adresse' => $request->validated('adresse'),
        ]);

        return redirect()
            ->route(
                'villages.maisons.index',
                $maison->village
            )
            ->with(
                'success',
                "La maison {$maison->numeroMaison} a été modifiée avec succès."
            );
    }

    /**
     * Suppression d'une maison.
     */
    public function destroy(Maison $maison)
    {
        $village = $maison->village;

        $maison->delete();

        return redirect()
            ->route(
                'villages.maisons.index',
                $village
            )
            ->with(
                'success',
                'La maison a été supprimée avec succès.'
            );
    }
}

