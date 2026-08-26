<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMenageRequest;
use App\Models\Maison;
use App\Models\Menage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenageController extends Controller
{
    /**
     * Liste des ménages d'une maison.
     */
    public function index(Maison $maison)
    {
        $maison->load([
            'village',
            'menages',
        ]);

        return view('menages.index', compact('maison'));
    }


    /**
     * Formulaire d'ajout d'un ménage depuis une maison.
     */
    public function create(Maison $maison)
    {
        $maison->load('village');

        return view('menages.create', compact('maison'));
    }


    /**
     * Enregistrer un ménage.
     */
    public function store(StoreMenageRequest $request, Maison $maison)
    {
        $menage = DB::transaction(function () use ($request, $maison) {

            /*
            |--------------------------------------------------------------------------
            | Génération automatique du numéro du ménage
            |--------------------------------------------------------------------------
            |
            | Exemple :
            | DJA-C-M-00001-M01
            | DJA-C-M-00001-M02
            |
            */

            $numero = $maison->menages()->count() + 1;

            $numeroMenage = $maison->numeroMaison
                . '-M'
                . str_pad($numero, 2, '0', STR_PAD_LEFT);


            /*
            |--------------------------------------------------------------------------
            | Création du ménage
            |--------------------------------------------------------------------------
            */

            return $maison->menages()->create([

                'numeroMenage' => $numeroMenage,

                'nomChef' => $request->validated('nomChef'),

                'nombrePersonnes' =>
                    $request->validated('nombrePersonnes'),

                'aChamp' =>
                    $request->boolean('aChamp'),

            ]);
        });


        return redirect()
            ->route('maisons.show', $maison)
            ->with(
                'success',
                'Le ménage '.$menage->numeroMenage.
                ' a été enregistré avec succès.'
            );
    }


    /**
     * Afficher un ménage.
     */
    public function show(Menage $menage)
    {
        $menage->load([
            'maison.village.canton.commune',
        ]);

        return view('menages.show', compact('menage'));
    }


    /**
     * Formulaire de modification.
     */
    public function edit(Menage $menage)
    {
        $menage->load('maison');

        return view('menages.edit', compact('menage'));
    }


    /**
     * Mettre à jour un ménage.
     */
    public function update(
        Request $request,
        Menage $menage
    ) {
        $validated = $request->validate([

            'nomChef' => [
                'required',
                'string',
                'max:255',
            ],

            'nombrePersonnes' => [
                'required',
                'integer',
                'min:1',
            ],

            'aChamp' => [
                'nullable',
                'boolean',
            ],

        ]);


        $menage->update([

            'nomChef' =>
                $validated['nomChef'],

            'nombrePersonnes' =>
                $validated['nombrePersonnes'],

            'aChamp' =>
                $request->boolean('aChamp'),

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
    public function destroy(Menage $menage)
    {
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