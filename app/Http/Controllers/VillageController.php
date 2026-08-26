<?php

namespace App\Http\Controllers;

use App\Models\Village;
use App\Models\Canton;

use App\Http\Requests\StoreVillageRequest;
use App\Http\Requests\UpdateVillageRequest;

use Illuminate\Http\Request;

class VillageController extends Controller
{
    /**
     * Liste des villages.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $user = auth()->user();

        $villages = Village::with(
                'canton.commune.prefecture'
            )

            ->when($user->isDpa(), function ($query) use ($user) {

                $query->whereHas(
                    'canton.commune',
                    function ($q) use ($user) {

                        $q->where(
                            'prefecture_id',
                            $user->prefecture_id
                        );

                    }
                );

            })

            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where(
                        'nom',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'code',
                        'like',
                        "%{$search}%"
                    );

                });

            })

            ->orderBy('nom')

            ->paginate(10);


        /*
        |--------------------------------------------------------------------------
        | Cantons disponibles
        |--------------------------------------------------------------------------
        |
        | Admin → tous les cantons
        | DPA   → uniquement les cantons de sa préfecture
        |
        */

        $cantons = Canton::when(
            $user->isDpa(),
            function ($query) use ($user) {

                $query->whereHas(
                    'commune',
                    function ($q) use ($user) {

                        $q->where(
                            'prefecture_id',
                            $user->prefecture_id
                        );

                    }
                );

            }
        )
        ->orderBy('nom')
        ->get();


        return view(
            'villages.index',
            compact(
                'villages',
                'cantons',
                'search'
            )
        );
    }


    /**
     * Formulaire de création.
     */
    public function create(Request $request)
    {
        $user = auth()->user();

        $canton = null;


        /*
        |--------------------------------------------------------------------------
        | Création depuis le détail d'un canton
        |--------------------------------------------------------------------------
        */

        if ($request->has('canton')) {

            $canton = Canton::with(
                'commune'
            )->findOrFail(
                $request->canton
            );


            /*
            |--------------------------------------------------------------------------
            | Sécurité DPA
            |--------------------------------------------------------------------------
            */

            if (
                $user->isDpa() &&
                (
                    !$canton->commune ||
                    $canton->commune->prefecture_id !== $user->prefecture_id
                )
            ) {

                abort(403);

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Cantons disponibles
        |--------------------------------------------------------------------------
        */

        $cantons = Canton::when(
            $user->isDpa(),
            function ($query) use ($user) {

                $query->whereHas(
                    'commune',
                    function ($q) use ($user) {

                        $q->where(
                            'prefecture_id',
                            $user->prefecture_id
                        );

                    }
                );

            }
        )
        ->orderBy('nom')
        ->get();


        return view(
            'villages.create',
            compact(
                'canton',
                'cantons'
            )
        );
    }


    /**
     * Enregistrement.
     */
    public function store(StoreVillageRequest $request)
    {
        $user = auth()->user();

        $data = $request->validated();


        /*
        |--------------------------------------------------------------------------
        | Sécurité DPA
        |--------------------------------------------------------------------------
        |
        | Le DPA ne peut créer un village que dans
        | un canton appartenant à sa préfecture.
        |
        */

        if ($user->isDpa()) {

            $canton = Canton::with(
                'commune'
            )->findOrFail(
                $data['canton_id']
            );


            if (
                !$canton->commune ||
                $canton->commune->prefecture_id !== $user->prefecture_id
            ) {

                abort(403);

            }

        }


        Village::create(
            $data
        );


        return redirect()
            ->route('villages.index')
            ->with(
                'success',
                'Village ajouté avec succès.'
            );
    }


    /**
     * Détail.
     */
    public function show(Village $village)
    {
        $user = auth()->user();

        $village->load(
            'canton.commune.prefecture.region',
            'maisons'
        );


        /*
        |--------------------------------------------------------------------------
        | Sécurité DPA
        |--------------------------------------------------------------------------
        */

        if ($user->isDpa()) {

            if (
                !$village->canton ||
                !$village->canton->commune ||
                $village->canton->commune->prefecture_id
                    !== $user->prefecture_id
            ) {

                abort(403);

            }

        }


        return view(
            'villages.show',
            compact(
                'village'
            )
        );
    }


    /**
     * Formulaire de modification.
     */
    public function edit(Village $village)
    {
        $user = auth()->user();

        $village->load(
            'canton.commune'
        );


        /*
        |--------------------------------------------------------------------------
        | Vérification du village
        |--------------------------------------------------------------------------
        */

        if ($user->isDpa()) {

            if (
                !$village->canton ||
                !$village->canton->commune ||
                $village->canton->commune->prefecture_id
                    !== $user->prefecture_id
            ) {

                abort(403);

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Cantons disponibles
        |--------------------------------------------------------------------------
        */

        $cantons = Canton::when(
            $user->isDpa(),
            function ($query) use ($user) {

                $query->whereHas(
                    'commune',
                    function ($q) use ($user) {

                        $q->where(
                            'prefecture_id',
                            $user->prefecture_id
                        );

                    }
                );

            }
        )
        ->orderBy('nom')
        ->get();


        return view(
            'villages.edit',
            compact(
                'village',
                'cantons'
            )
        );
    }


    /**
     * Mise à jour.
     */
    public function update(
        UpdateVillageRequest $request,
        Village $village
    )
    {
        $user = auth()->user();

        $data = $request->validated();


        /*
        |--------------------------------------------------------------------------
        | Vérification du village actuel
        |--------------------------------------------------------------------------
        */

        $village->load(
            'canton.commune'
        );


        if ($user->isDpa()) {

            if (
                !$village->canton ||
                !$village->canton->commune ||
                $village->canton->commune->prefecture_id
                    !== $user->prefecture_id
            ) {

                abort(403);

            }


            /*
            |--------------------------------------------------------------------------
            | Vérification du nouveau canton
            |--------------------------------------------------------------------------
            */

            $canton = Canton::with(
                'commune'
            )->findOrFail(
                $data['canton_id']
            );


            if (
                !$canton->commune ||
                $canton->commune->prefecture_id !== $user->prefecture_id
            ) {

                abort(403);

            }

        }


        $village->update(
            $data
        );


        return redirect()
            ->route('villages.index')
            ->with(
                'success',
                'Village modifié avec succès.'
            );
    }


    /**
     * Suppression.
     */
    public function destroy(Village $village)
    {
        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Sécurité DPA
        |--------------------------------------------------------------------------
        */

        if ($user->isDpa()) {

            $village->load(
                'canton.commune'
            );


            if (
                !$village->canton ||
                !$village->canton->commune ||
                $village->canton->commune->prefecture_id
                    !== $user->prefecture_id
            ) {

                abort(403);

            }

        }


        if ($village->maisons()->exists()) {

            return back()->with(
                'error',
                'Impossible de supprimer ce village car il contient des maisons.'
            );

        }


        $village->delete();


        return redirect()
            ->route('villages.index')
            ->with(
                'success',
                'Village supprimé avec succès.'
            );
    }
}