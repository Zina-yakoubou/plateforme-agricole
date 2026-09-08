<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMenageRequest;
use App\Http\Requests\UpdateMenageRequest;
use App\Models\Maison;
use App\Models\Menage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MenageController extends Controller
{
    /**
     * Liste des ménages d'une maison.
     */
    public function index(Maison $maison): View
    {
        $maison->load([
            'village',
            'menages',
        ]);

        return view('menages.index', compact('maison'));
    }

    /**
     * Formulaire de création d'un ménage.
     */
    public function create(Maison $maison): View
    {
        $maison->load('village');

        return view('menages.create', compact('maison'));
    }

    /**
     * Enregistrer un ménage.
     */
    public function store(
        StoreMenageRequest $request,
        Maison $maison
    ): RedirectResponse {
        $menage = DB::transaction(function () use (
            $request,
            $maison
        ) {
            $numero = $maison->menages()->count() + 1;

            $numeroMenage = $maison->numeroMaison
                . '-M'
                . str_pad(
                    $numero,
                    2,
                    '0',
                    STR_PAD_LEFT
                );

            return $maison->menages()->create([
                'uid' => (string) Str::uuid(),

                'numeroMenage' => $numeroMenage,

                'nomChef' =>
                    $request->validated('nomChef'),

                'prenomChef' =>
                    $request->validated('prenomChef'),

                'sexeChef' =>
                    $request->validated('sexeChef'),

                'nombreHommes' =>
                    $request->validated('nombreHommes', 0),

                'nombreFemmes' =>
                    $request->validated('nombreFemmes', 0),

                'nombreGarcons' =>
                    $request->validated('nombreGarcons', 0),

                'nombreFilles' =>
                    $request->validated('nombreFilles', 0),

                'possedeExploitation' =>
                    $request->boolean('possedeExploitation'),

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
            ->route('maisons.show', $maison)
            ->with(
                'success',
                "Le ménage {$menage->numeroMenage} a été enregistré avec succès."
            );
    }

    /**
     * Afficher un ménage.
     */
    public function show(Menage $menage): View
    {
        $menage->load([
            'maison.village.canton.commune',
            'exploitants',
        ]);

        return view('menages.show', compact('menage'));
    }

    /**
     * Formulaire de modification.
     */
    public function edit(Menage $menage): View
    {
        $menage->load('maison');

        return view('menages.edit', compact('menage'));
    }

    /**
     * Mettre à jour un ménage.
     */
    public function update(
        UpdateMenageRequest $request,
        Menage $menage
    ): RedirectResponse {
        $menage->update([
            'nomChef' =>
                $request->validated('nomChef'),

            'prenomChef' =>
                $request->validated('prenomChef'),

            'sexeChef' =>
                $request->validated('sexeChef'),

            'nombreHommes' =>
                $request->validated('nombreHommes'),

            'nombreFemmes' =>
                $request->validated('nombreFemmes'),

            'nombreGarcons' =>
                $request->validated('nombreGarcons'),

            'nombreFilles' =>
                $request->validated('nombreFilles'),

            'possedeExploitation' =>
                $request->boolean('possedeExploitation'),

            'observations' =>
                $request->validated('observations'),

            'statut' =>
                $request->validated('statut'),
        ]);

        return redirect()
            ->route('menages.show', $menage)
            ->with(
                'success',
                'Le ménage a été mis à jour avec succès.'
            );
    }

    /**
     * Supprimer un ménage.
     */
    public function destroy(
        Menage $menage
    ): RedirectResponse {
        $maison = $menage->maison;

        $menage->delete();

        return redirect()
            ->route('maisons.show', $maison)
            ->with(
                'success',
                'Le ménage a été supprimé avec succès.'
            );
    }
}

