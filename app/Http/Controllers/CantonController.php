<?php

namespace App\Http\Controllers;

use App\Models\Canton;
use App\Models\Commune;
use App\Http\Requests\StoreCantonRequest;
use App\Http\Requests\UpdateCantonRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CantonController extends Controller
{
    /**
     * Liste des cantons
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $user = Auth::user();

        $cantons = Canton::with('commune')
            ->when($user->isDpa(), function ($query) use ($user) {

                $query->whereHas('commune', function ($q) use ($user) {

                    $q->where(
                        'prefecture_id',
                        $user->prefecture_id
                    );

                });

            })
            ->orderBy('nom')
            ->when($search, function ($query, $search) {

                $query->where(function ($q) use ($search) {

                    $q->where('nom', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhereHas('commune', function ($q) use ($search) {

                            $q->where('nom', 'like', "%{$search}%");

                        });

                });

            })
            ->paginate(5);

        /*
        |--------------------------------------------------------------------------
        | Communes disponibles
        |--------------------------------------------------------------------------
        |
        | Admin → toutes les communes
        | DPA   → uniquement les communes de sa préfecture
        |
        */

        $communes = Commune::when(
            $user->isDpa(),
            function ($query) use ($user) {

                $query->where(
                    'prefecture_id',
                    $user->prefecture_id
                );

            }
        )
        ->orderBy('nom')
        ->get();

        return view(
            'cantons.index',
            compact(
                'cantons',
                'communes'
            )
        );
    }


    /**
     * Formulaire création
     */
    public function create(Request $request)
    {
        $user = auth()->user();

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

            /*
            |--------------------------------------------------------------------------
            | Sécurité DPA
            |--------------------------------------------------------------------------
            */

            if (
                $user->isDpa() &&
                $commune->prefecture_id !== $user->prefecture_id
            ) {

                abort(403);

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Création normale depuis le menu Cantons
        |--------------------------------------------------------------------------
        */

        $communes = Commune::when(
            $user->isDpa(),
            function ($query) use ($user) {

                $query->where(
                    'prefecture_id',
                    $user->prefecture_id
                );

            }
        )
        ->orderBy('nom')
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
        $user = auth()->user();

        $data = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Sécurité DPA
        |--------------------------------------------------------------------------
        |
        | Le DPA ne peut créer un canton que dans une
        | commune appartenant à sa préfecture.
        |
        */

        if ($user->isDpa()) {

            $commune = Commune::findOrFail(
                $data['commune_id']
            );

            if (
                $commune->prefecture_id !== $user->prefecture_id
            ) {

                abort(403);

            }

        }

        Canton::create(
            $data
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
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Sécurité DPA
        |--------------------------------------------------------------------------
        */

        if ($user->isDpa()) {

            $canton->load('commune');

            if (
                !$canton->commune ||
                $canton->commune->prefecture_id !== $user->prefecture_id
            ) {

                abort(403);

            }

        }

        $canton->load([
            'commune',
            'villages'
        ]);

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
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Vérification du canton
        |--------------------------------------------------------------------------
        */

        $canton->load('commune');

        if ($user->isDpa()) {

            if (
                !$canton->commune ||
                $canton->commune->prefecture_id !== $user->prefecture_id
            ) {

                abort(403);

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Communes disponibles
        |--------------------------------------------------------------------------
        */

        $communes = Commune::when(
            $user->isDpa(),
            function ($query) use ($user) {

                $query->where(
                    'prefecture_id',
                    $user->prefecture_id
                );

            }
        )
        ->orderBy('nom')
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
        $user = auth()->user();

        $data = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Vérification du canton actuel
        |--------------------------------------------------------------------------
        */

        $canton->load('commune');

        if ($user->isDpa()) {

            if (
                !$canton->commune ||
                $canton->commune->prefecture_id !== $user->prefecture_id
            ) {

                abort(403);

            }


            /*
            |--------------------------------------------------------------------------
            | Vérification de la nouvelle commune
            |--------------------------------------------------------------------------
            */

            $commune = Commune::findOrFail(
                $data['commune_id']
            );

            if (
                $commune->prefecture_id !== $user->prefecture_id
            ) {

                abort(403);

            }

        }


        $canton->update(
            $data
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
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Sécurité DPA
        |--------------------------------------------------------------------------
        */

        if ($user->isDpa()) {

            $canton->load('commune');

            if (
                !$canton->commune ||
                $canton->commune->prefecture_id !== $user->prefecture_id
            ) {

                abort(403);

            }

        }


        $canton->delete();


        return redirect()
            ->route('cantons.index')
            ->with(
                'success',
                'Canton supprimé avec succès.'
            );
    }
}