<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Http\Requests\StoreRegionRequest;
use App\Http\Requests\UpdateRegionRequest;
use Illuminate\Http\Request;

class RegionController extends Controller
{

    /**
     * Liste des régions
     */
    public function index(Request $request)
    {
        $search = $request->search;


        $regions = Region::query()

            ->when($search, function ($query) use ($search) {

                $query->where('nom', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");

            })

            ->orderBy('nom')

            ->paginate(10);


        return view('regions.index', compact(
            'regions',
            'search'
        ));
    }



    /**
     * Formulaire création
     */
    public function create()
    {
        return view('regions.create');
    }




    /**
     * Enregistrer une région
     */
    public function store(StoreRegionRequest $request)
    {

        Region::create($request->validated());


        return redirect()
            ->route('regions.index')
            ->with('success',
                'Région créée avec succès.'
            );
    }





    /**
     * Afficher détail
     */
    public function show(Region $region)
    {
        return view('regions.show', compact('region'));
    }






    /**
     * Formulaire modification
     */
    public function edit(Region $region)
    {
        return view('regions.edit', compact('region'));
    }






    /**
     * Mise à jour
     */
    public function update(
        UpdateRegionRequest $request,
        Region $region
    )
    {

        $region->update(
            $request->validated()
        );


        return redirect()
            ->route('regions.index')
            ->with('success',
                'Région modifiée avec succès.'
            );
    }







    /**
     * Suppression
     */
    public function destroy(Region $region)
    {

        $region->delete();


        return redirect()
            ->route('regions.index')
            ->with('success',
                'Région supprimée avec succès.'
            );
    }

}