<?php

namespace App\Http\Controllers;

use App\Models\Canton;
use App\Models\Commune;
use App\Http\Requests\StoreCantonRequest;
use App\Http\Requests\UpdateCantonRequest;
use Illuminate\Http\Request;

class CantonController extends Controller
{
    /**
     * Liste des cantons
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $cantons = Canton::with('commune')
            ->when($search, function ($query) use ($search) {

                $query->where('nom', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhereHas('commune', function ($q) use ($search) {

                        $q->where('nom', 'like', "%{$search}%");

                    });

            })
            ->orderBy('nom')
            ->paginate(5);


        return view('cantons.index', compact('cantons'));
    }


    /**
     * Formulaire création
     */
    public function create(Request $request)
    {
        $commune = null;


        /*
        |--------------------------------------------------------------------------
        | Création depuis le détail d'une commune
        |--------------------------------------------------------------------------
        */

        if ($request->has('commune')) {

            $commune = Commune::findOrFail(
                $request->commune
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Création normale depuis le menu Cantons
        |--------------------------------------------------------------------------
        */

        $communes = Commune::orderBy('nom')
            ->get();


        return view(
            'cantons.create',
            compact(
                'commune',
                'communes'
            )
        );
    }



    /**
     * Enregistrement
     */
    public function store(StoreCantonRequest $request)
    {

        Canton::create(
            $request->validated()
        );


        return redirect()
            ->route('cantons.index')
            ->with(
                'success',
                'Canton ajouté avec succès.'
            );

    }



    /**
     * Affichage détail canton
     */
    public function show(Canton $canton)
    {

        $canton->load('commune');


        return view(
            'cantons.show',
            compact('canton')
        );

    }



    /**
     * Formulaire modification
     */
    public function edit(Canton $canton)
    {

        $communes = Commune::orderBy('nom')
            ->get();


        return view(
            'cantons.edit',
            compact(
                'canton',
                'communes'
            )
        );

    }



    /**
     * Mise à jour
     */
    public function update(
        UpdateCantonRequest $request,
        Canton $canton
    )
    {

        $canton->update(
            $request->validated()
        );


        return redirect()
            ->route('cantons.index')
            ->with(
                'success',
                'Canton modifié avec succès.'
            );

    }



    /**
     * Suppression
     */
    public function destroy(Canton $canton)
    {

        $canton->delete();


        return redirect()
            ->route('cantons.index')
            ->with(
                'success',
                'Canton supprimé avec succès.'
            );

    }
}