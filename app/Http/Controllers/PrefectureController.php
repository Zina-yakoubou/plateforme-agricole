<?php

namespace App\Http\Controllers;


use App\Models\Prefecture;
use App\Models\Region;
use App\Models\Affectation;

use App\Http\Requests\StorePrefectureRequest;
use App\Http\Requests\UpdatePrefectureRequest;

use Illuminate\Http\Request;



class PrefectureController extends Controller
{


    /**
     * Liste des préfectures
     */
    // public function index(Request $request)
    // {

    //     $search = $request->search;


    //     $prefectures = Prefecture::with('region')
    //         ->when($search, function($query) use ($search){

    //             $query->where('nom','like',"%{$search}%");

    //         })
    //         ->paginate(5);



    //     return view(
    //         'prefectures.index',
    //         compact(
    //             'prefectures',
    //             'search'
    //         )
    //     );

    // }


        public function index(Request $request)
    {
        $search = $request->input('search');

        $prefectures = Prefecture::with('region')
            ->withCount('communes')
            ->when($search, function ($query, $search) {
                $query->where('nom', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            })
            ->paginate(5);

        $regions = Region::orderBy('nom')->get(); // <-- à ajouter

        return view('prefectures.index', compact('prefectures', 'regions', 'search'));
    }



    /**
     * Formulaire création
     * Peut venir d'une région
     */
    // public function create(Request $request)
    // {


    //     $region = null;


    //     if($request->has('region'))
    //     {

    //         $region = Region::findOrFail(
    //             $request->region
    //         );

    //     }


    //     return view(
    //         'prefectures.create',
    //         compact('region')
    //     );

    // }


        public function create(Request $request)
    {

        $region = null;


        if ($request->has('region')) {

            $region = Region::findOrFail(
                $request->region
            );

        }


        $regions = Region::orderBy('nom')->get();



        return view(
            'prefectures.create',
            compact(
                'region',
                'regions'
            )
        );

    }





    /**
     * Enregistrement
     */
    public function store(StorePrefectureRequest $request)
    {


        Prefecture::create(
            $request->validated()
        );



        return redirect()
            ->route(
                'prefectures.index'
            )
            ->with(
                'success',
                'Préfecture ajoutée avec succès.'
            );

    }






    /**
     * Affichage détail
     */
    public function show(Prefecture $prefecture)
    {


        $prefecture->load(
            'region',
            'communes'
        );

        $directeur = Affectation::where('prefecture_id', $prefecture->idPrefecture)
        ->whereHas('user.role', function ($query) {
            $query->where('nom', 'Directeur préfectoral');
        })
        ->with('user')
        ->first();

        $agents = Affectation::where('prefecture_id', $prefecture->idPrefecture)
        ->whereHas('user.role', function ($query) {
            $query->where('nom', 'Agent recenseur');
        })
        ->with('user')
        ->get();



        return view(
            'prefectures.show',
            compact(
                'prefecture',
                'directeur',
                'agents'
            )
        );

    }







    /**
     * Formulaire modification
     */
    public function edit(Prefecture $prefecture)
    {


        $regions = Region::all();


        return view(
            'prefectures.edit',
            compact(
                'prefecture',
                'regions'
            )
        );

    }







    /**
     * Mise à jour
     */
    public function update(
        UpdatePrefectureRequest $request,
        Prefecture $prefecture
    )
    {


        $prefecture->update(
            $request->validated()
        );



        return redirect()
            ->route(
                'prefectures.index'
            )
            ->with(
                'success',
                'Préfecture modifiée avec succès.'
            );

    }







    /**
     * Suppression
     */
    public function destroy(Prefecture $prefecture)
    {


        $prefecture->delete();



        return redirect()
            ->route(
                'prefectures.index'
            )
            ->with(
                'success',
                'Préfecture supprimée avec succès.'
            );

    }


    

}