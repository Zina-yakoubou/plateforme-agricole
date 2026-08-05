<?php

namespace App\Http\Controllers;

use App\Models\Commune;
use App\Models\Prefecture;
use Illuminate\Http\Request;

use App\Http\Requests\StoreCommuneRequest;
use App\Http\Requests\UpdateCommuneRequest;

class CommuneController extends Controller
{
    /**
     * Liste des communes
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $communes = Commune::with('prefecture.region')
            ->when($search, function ($query) use ($search) {

                $query->where('nom', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");

            })
            ->paginate(10);

        return view(
            'communes.index',
            compact(
                'communes',
                'search'
            )
        );
    }

    /**
     * Formulaire création
     * Peut venir d'une préfecture
     */
    // public function create(Request $request)
    // {
    //     $prefecture = null;

    //     if ($request->has('prefecture')) {

    //         $prefecture = Prefecture::findOrFail(
    //             $request->prefecture
    //         );

    //     }

    //     $prefectures = Prefecture::with('region')
    //         ->orderBy('nom')
    //         ->get();

    //     return view(
    //         'communes.create',
    //         compact(
    //             'prefecture',
    //             'prefectures'
    //         )
    //     );
    // }
        public function create(Request $request)
    {
        $prefecture = null;

        if ($request->has('prefecture')) {

            $prefecture = Prefecture::findOrFail(
                $request->prefecture
            );

        }

        $prefectures = Prefecture::orderBy('nom')->get();

        return view(
            'communes.create',
            compact(
                'prefecture',
                'prefectures'
            )
        );
    }

    /**
     * Enregistrement
     */
    public function store(StoreCommuneRequest $request)
    {
        $commune = Commune::create(
            $request->validated()
        );

        // Si on vient du détail d'une préfecture
        if ($request->filled('prefecture_id')) {

            return redirect()
                ->route(
                    'prefectures.show',
                    $request->prefecture_id
                )
                ->with(
                    'success',
                    'Commune ajoutée avec succès.'
                );

        }

        return redirect()
            ->route('communes.index')
            ->with(
                'success',
                'Commune ajoutée avec succès.'
            );
    }

    /**
     * Affichage détail
     */
    public function show(Commune $commune)
    {
        $commune->load(
            'prefecture.region',
            'cantons'
        );

        return view(
            'communes.show',
            compact('commune')
        );
    }

    /**
     * Formulaire modification
     */
    public function edit(Commune $commune)
    {
        $prefectures = Prefecture::with('region')
            ->orderBy('nom')
            ->get();

        return view(
            'communes.edit',
            compact(
                'commune',
                'prefectures'
            )
        );
    }

    /**
     * Mise à jour
     */
    public function update(
        UpdateCommuneRequest $request,
        Commune $commune
    ) {

        $commune->update(
            $request->validated()
        );

        return redirect()
            ->route(
                'communes.index'
            )
            ->with(
                'success',
                'Commune modifiée avec succès.'
            );
    }

    /**
     * Suppression
     */
    public function destroy(Commune $commune)
    {
        $commune->delete();

        return redirect()
            ->route(
                'communes.index'
            )
            ->with(
                'success',
                'Commune supprimée avec succès.'
            );
    }
}