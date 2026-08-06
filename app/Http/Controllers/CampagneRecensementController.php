<?php

namespace App\Http\Controllers;


use App\Models\CampagneRecensement;
use App\Models\User;

use App\Http\Requests\StoreCampagneRecensementRequest;
use App\Http\Requests\UpdateCampagneRecensementRequest;

use Illuminate\Http\Request;


class CampagneRecensementController extends Controller
{


    /**
     * Liste des campagnes
     */
    public function index(Request $request)
    {

        $search = $request->search;


        $campagnes = CampagneRecensement::with('responsable')

            ->when($search, function($query) use ($search){

                $query->where('libelle','like',"%{$search}%")
                      ->orWhere('codeRNA','like',"%{$search}%");

            })

            ->orderByDesc('created_at')

            ->paginate(10);



        return view(
            'campagnes.index',
            compact(
                'campagnes',
                'search'
            )
        );

    }




    /**
     * Formulaire création
     */
    public function create()
    {

        $responsables = User::whereHas('role', function($query){

            $query->whereIn('nom', [
                'Administrateur',
                'Coordinateur'
            ]);

        })

        ->orderBy('name')

        ->get();



        return view(
            'campagnes.create',
            compact(
                'responsables'
            )
        );

    }





    /**
     * Enregistrement
     */
    public function store(
        StoreCampagneRecensementRequest $request
    )
    {


        CampagneRecensement::create(

            $request->validated()

        );



        return redirect()

            ->route('campagnes.index')

            ->with(
                'success',
                'Campagne créée avec succès.'
            );

    }







    /**
     * Affichage détail
     */
    public function show(
        CampagneRecensement $campagne
    )
    {


        $campagne->load(
            'responsable',
            'affectations.user'
        );



        return view(
            'campagnes.show',
            compact(
                'campagne'
            )
        );

    }








    /**
     * Formulaire modification
     */
    public function edit(
        CampagneRecensement $campagne
    )
    {


        $responsables = User::orderBy('name')
            ->get();



        return view(
            'campagnes.edit',
            compact(
                'campagne',
                'responsables'
            )
        );

    }








    /**
     * Mise à jour
     */
    public function update(
        UpdateCampagneRecensementRequest $request,
        CampagneRecensement $campagne
    )
    {


        $campagne->update(

            $request->validated()

        );



        return redirect()

            ->route('campagnes.index')

            ->with(
                'success',
                'Campagne modifiée avec succès.'
            );

    }








    /**
     * Activer une campagne
     */
    public function activate(
        CampagneRecensement $campagne
    )
    {


        // Désactiver toutes les autres campagnes

        CampagneRecensement::where('active', true)

            ->update([

                'active' => false

            ]);




        // Activer la campagne choisie

        $campagne->update([

            'active' => true,

            'statut' => 'Active'

        ]);



        return back()

            ->with(
                'success',
                'Campagne activée avec succès.'
            );

    }







    /**
     * Suppression
     */
    public function destroy(
        CampagneRecensement $campagne
    )
    {


        $campagne->delete();



        return redirect()

            ->route('campagnes.index')

            ->with(
                'success',
                'Campagne supprimée avec succès.'
            );

    }



}