<?php

namespace App\Http\Controllers\Dpa;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEquipeRequest;
use App\Http\Requests\UpdateEquipeRequest;
use App\Models\Equipe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EquipeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    // public function index(Request $request): View
    // {
    //     $search = $request->input('search');

    //     $equipes = Equipe::with([
    //         'superviseur',
    //         'membres',
    //     ])
    //         ->when($search, function ($query, $search) {

    //             $query->where(function ($q) use ($search) {

    //                 $q->where('reference', 'like', "%{$search}%")
    //                     ->orWhere('nom', 'like', "%{$search}%")
    //                     ->orWhereHas('superviseur', function ($superviseur) use ($search) {

    //                         $superviseur
    //                             ->where('name', 'like', "%{$search}%")
    //                             ->orWhere('telephone', 'like', "%{$search}%");
    //                     });
    //             });
    //         })
    //         ->when(
    //             $request->filled('statut'),
    //             fn ($query) =>
    //                 $query->where(
    //                     'statut',
    //                     $request->input('statut')
    //                 )
    //         )
    //         ->latest('idEquipe')
    //         ->paginate(10)
    //         ->withQueryString();

    //     return view(
    //         'dpa.equipes.index',
    //         compact(
    //             'equipes',
    //             'search'
    //         )
    //     );
    // }


    //     public function index(Request $request)
    // {
    //     $search = $request->input('search');

    //     $equipes = Equipe::query()
    //         ->with('superviseur')
    //         ->withCount('membres')

    //         ->when($search, function ($query, $search) {

    //             $query->where(function ($q) use ($search) {

    //                 $q->where('nom', 'like', '%' . $search . '%')
    //                     ->orWhere('reference', 'like', '%' . $search . '%')

    //                     ->orWhereHas('superviseur', function ($superviseur) use ($search) {

    //                         $superviseur
    //                             ->where('name', 'like', '%' . $search . '%')
    //                             ->orWhere('telephone', 'like', '%' . $search . '%');

    //                     });

    //             });

    //         })

    //         ->when($request->filled('statut'), function ($query) use ($request) {

    //             $query->where('statut', $request->statut);

    //         })

    //         ->latest()
    //         ->paginate(20)
    //         ->withQueryString();

    //     return view('dpa.equipes.index', compact(
    //         'equipes',
    //         'search'
    //     ));
    // }



        public function index(Request $request): View
    {
        $query = Equipe::with([
            'superviseur',
            'membres',
        ])
        ->withCount('membres');

        // ============================================================
        // RECHERCHE
        // ============================================================
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                // Référence de l'équipe
                $q->where('reference', 'like', "%{$search}%")

                    // Nom de l'équipe
                    ->orWhere('nom', 'like', "%{$search}%")

                    // Libellé si la colonne existe
                    ->orWhere('libelle', 'like', "%{$search}%")

                    // Superviseur
                    ->orWhereHas('superviseur', function ($q) use ($search) {

                        $q->where('name', 'like', "%{$search}%")
                        ->orWhere('telephone', 'like', "%{$search}%");

                    });
            });
        }

        // ============================================================
        // FILTRE STATUT
        // ============================================================
        if ($request->filled('statut')) {

            $query->where(
                'statut',
                strtoupper($request->statut)
            );
        }

        // ============================================================
        // PAGINATION
        // ============================================================
        $equipes = $query
            ->latest('idEquipe')
            ->paginate(15)
            ->withQueryString();

        return view('dpa.equipes.index', [
            'equipes' => $equipes,
            'search' => $request->search,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(): View
    {
        /*
        |--------------------------------------------------------------------------
        | SUPERVISEURS
        |--------------------------------------------------------------------------
        |
        | On recherche le rôle par son nom/code plutôt que de mettre
        | directement R03 dans la requête.
        |
        */

        $superviseurs = \App\Models\User::whereHas('role', function ($query) {
            $query->where('nom', 'Superviseur');
        })
            ->where('statut', true)
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | AGENTS RECENSEURS
        |--------------------------------------------------------------------------
        */

        $agents = \App\Models\User::whereHas('role', function ($query) {
            $query->where('nom', 'Agent recenseur');
        })
            ->where('statut', true)
            ->orderBy('name')
            ->get();


        return view(
            'dpa.equipes.create',
            compact(
                'superviseurs',
                'agents'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(StoreEquipeRequest $request): RedirectResponse
    {
        $equipe = DB::transaction(function () use ($request) {

            /*
            |--------------------------------------------------------------------------
            | CRÉATION DE L'ÉQUIPE
            |--------------------------------------------------------------------------
            */

            $equipe = Equipe::create([

                'reference' => $this->genererReference(),

                'nom' => $request->nom,

                'superviseur_id' => $request->superviseur_id,

                'statut' => 'ACTIVE',
            ]);


            /*
            |--------------------------------------------------------------------------
            | AJOUT DES AGENTS RECENSEURS
            |--------------------------------------------------------------------------
            */

            $equipe->membres()->sync(
                $request->membres
            );


            return $equipe;
        });


        return redirect()
            ->route(
                'dpa.equipes.index',
                $equipe
            )
            ->with(
                'success',
                'L’équipe a été créée avec succès.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(Equipe $equipe): View
    {
        $equipe->load([
            'superviseur',
            'membres',
        ]);

        return view(
            'dpa.equipes.show',
            compact('equipe')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Equipe $equipe): View
    {
        /*
        |--------------------------------------------------------------------------
        | SUPERVISEURS
        |--------------------------------------------------------------------------
        */

        $superviseurs = \App\Models\User::whereHas('role', function ($query) {
            $query->where('nom', 'Superviseur');
        })
            ->where('statut', true)
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | AGENTS RECENSEURS
        |--------------------------------------------------------------------------
        */

        $agents = \App\Models\User::whereHas('role', function ($query) {
            $query->where('nom', 'Agent recenseur');
        })
            ->where('statut', true)
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | MEMBRES ACTUELS
        |--------------------------------------------------------------------------
        */

        $membresActuels = $equipe->membres()
            ->pluck('users.id')
            ->toArray();


        return view(
            'dpa.equipes.edit',
            compact(
                'equipe',
                'superviseurs',
                'agents',
                'membresActuels'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        UpdateEquipeRequest $request,
        Equipe $equipe
    ): RedirectResponse {

        DB::transaction(function () use (
            $request,
            $equipe
        ) {

            /*
            |--------------------------------------------------------------------------
            | MISE À JOUR DE L'ÉQUIPE
            |--------------------------------------------------------------------------
            */

            $equipe->update([

                'nom' => $request->nom,

                'superviseur_id' =>
                    $request->superviseur_id,
            ]);


            /*
            |--------------------------------------------------------------------------
            | SYNCHRONISATION DES AGENTS
            |--------------------------------------------------------------------------
            */

            $equipe->membres()->sync(
                $request->membres
            );
        });


        return redirect()
            ->route(
                'dpa.equipes.show',
                $equipe
            )
            ->with(
                'success',
                'L’équipe a été modifiée avec succès.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(Equipe $equipe): RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | ON NE SUPPRIME PAS L'ÉQUIPE
        |--------------------------------------------------------------------------
        |
        | Comme pour les utilisateurs, on préfère conserver l'historique.
        |
        */

        $equipe->update([
            'statut' => 'INACTIVE',
        ]);


        return redirect()
            ->route('dpa.equipes.index')
            ->with(
                'success',
                'L’équipe a été désactivée avec succès.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | GÉNÉRER UNE RÉFÉRENCE
    |--------------------------------------------------------------------------
    */

    private function genererReference(): string
    {
        do {

            $reference =
                'EQ-' .
                now()->format('Y') .
                '-' .
                strtoupper(
                    substr(
                        bin2hex(
                            random_bytes(3)
                        ),
                        0,
                        6
                    )
                );

        } while (
            Equipe::where(
                'reference',
                $reference
            )->exists()
        );


        return $reference;
    }


        public function reactiver(Equipe $equipe): RedirectResponse
    {
        if ($equipe->statut === 'ACTIVE') {
            return redirect()
                ->route('dpa.equipes.index')
                ->with('error', 'Cette équipe est déjà active.');
        }

        $equipe->update([
            'statut' => 'ACTIVE',
        ]);

        return redirect()
            ->route('dpa.equipes.index')
            ->with('success', 'L’équipe a été réactivée avec succès.');
    }
}