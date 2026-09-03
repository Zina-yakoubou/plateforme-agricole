<?php

namespace App\Http\Controllers\Dpa;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDpaAgentRequest;
use App\Http\Requests\UpdateDpaAgentRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class DpaAgentController extends Controller
{
    /**
     * Préfecture du DPA connecté.
     */
    private function prefectureId(): int
    {
        return Auth::user()
            ->rattachementPrefectureActif
            ->prefecture_id;
    }

    /**
     * Vérifie que le DPA agit uniquement sur sa préfecture.
     */
    private function verifierUtilisateur(User $user): void
    {
        abort_if(
            $user->rattachementPrefectureActif?->prefecture_id !== $this->prefectureId(),
            403,
            "Cet utilisateur n'appartient pas à votre préfecture."
        );
    }

    /**
     * =========================================================
     * LISTE DES AGENTS ET SUPERVISEURS
     * =========================================================
     */
    // public function index(Request $request): View
    // {
    //     $search = $request->search;
    //     $role = $request->role;
    //     $statut = $request->statut;

    //     $roles = Role::whereIn('nom', [
    //         'Superviseur',
    //         'Agent recenseur',
    //     ])->orderBy('nom')->get();

    //     $agents = User::with([
    //             'role',
    //             'rattachementPrefectureActif.prefecture',
    //         ])
    //         ->whereHas('rattachementPrefectureActif', function ($query) {
    //             $query->where('prefecture_id', $this->prefectureId())
    //                   ->where('statut', 'actif');
    //         })
    //         ->whereIn('role_id', $roles->pluck('idRole'))
    //         ->when($search, function ($query) use ($search) {

    //             $query->where(function ($q) use ($search) {

    //                 $q->where('name', 'like', "%{$search}%")
    //                   ->orWhere('telephone', 'like', "%{$search}%")
    //                   ->orWhere('email', 'like', "%{$search}%");

    //             });

    //         })
    //         ->when($role, function ($query) use ($role) {
    //             $query->where('role_id', $role);
    //         })
    //         ->when($statut !== null && $statut !== '', function ($query) use ($statut) {
    //             $query->where('statut', $statut);
    //         })
    //         ->latest()
    //         ->paginate(15)
    //         ->withQueryString();

    //     return view(
    //         'dpa.agents.index',
    //         compact(
    //             'agents',
    //             'roles',
    //             'search',
    //             'role',
    //             'statut'
    //         )
    //     );
    // }





    /**
 * =========================================================
 * LISTE DU DPA, DES AGENTS ET DES SUPERVISEURS
 * =========================================================
 */
public function index(Request $request): View
{
    $search = $request->search;
    $role = $request->role;
    $statut = $request->statut;

    /*
     * Rôles autorisés dans la liste :
     * - DPA
     * - Superviseur
     * - Agent recenseur
     */
    $roles = Role::whereIn('nom', [
        'DPA',
        'Superviseur',
        'Agent recenseur',
    ])
        ->orderBy('nom')
        ->get();

    /*
     * Utilisateurs rattachés à la préfecture du DPA.
     */
    $agents = User::with([
            'role',
            'rattachementPrefectureActif.prefecture',
        ])

        ->whereHas('rattachementPrefectureActif', function ($query) {
            $query
                ->where('prefecture_id', $this->prefectureId())
                ->where('statut', 'actif');
        })

        /*
         * On garde uniquement :
         * - le DPA
         * - les superviseurs
         * - les agents recenseurs
         */
        ->whereIn('role_id', $roles->pluck('idRole'))

        /*
         * Recherche
         */
        ->when($search, function ($query) use ($search) {

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('telephone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('login', 'like', "%{$search}%");

            });

        })

        /*
         * Filtre par rôle
         */
        ->when($role, function ($query) use ($role) {
            $query->where('role_id', $role);
        })

        /*
         * Filtre par statut
         */
        ->when(
            $statut !== null && $statut !== '',
            function ($query) use ($statut) {
                $query->where('statut', $statut);
            }
        )

        ->latest()
        ->paginate(15)
        ->withQueryString();

    return view(
        'dpa.agents.index',
        compact(
            'agents',
            'roles',
            'search',
            'role',
            'statut'
        )
    );
}

    /**
     * =========================================================
     * FORMULAIRE DE CRÉATION
     * =========================================================
     */
    public function create(): View
    {
        $roles = Role::whereIn('nom', [
            'Superviseur',
            'Agent recenseur',
        ])->orderBy('nom')->get();

        $prefecture = Auth::user()
            ->rattachementPrefectureActif
            ?->prefecture;

        return view(
            'dpa.agents.create',
            compact(
                'roles',
                'prefecture'
            )
        );
    }

    /**
     * =========================================================
     * ENREGISTRER UN COMPTE
     * =========================================================
     */
    public function store(
        StoreDpaAgentRequest $request
    ): RedirectResponse {

        DB::transaction(function () use ($request) {

            $user = User::create([

                'name' => $request->name,

                'telephone' => $request->telephone,

                'email' => $request->email,

                'role_id' => $request->role_id,

                'password' => Hash::make(
                    $request->password
                ),

                'statut' => true,

            ]);

            /*
             * Rattachement automatique à la préfecture du DPA.
             */
            $user->rattachementsPrefecture()->create([

                'prefecture_id' => $this->prefectureId(),

                'dateDebut' => now()->startOfDay(),

                'dateFin' => null,

                'statut' => 'actif',

            ]);

        });

        return redirect()
            ->route('dpa.agents.index')
            ->with(
                'success',
                'Le compte a été créé avec succès.'
            );
    }

    /**
     * =========================================================
     * AFFICHER UN AGENT
     * =========================================================
     */
    public function show(User $agent): View
    {
        $this->verifierUtilisateur($agent);

        $agent->load([
            'role',
            'rattachementPrefectureActif.prefecture',
        ]);

        return view(
            'dpa.agents.show',
            compact('agent')
        );
    }

    /**
     * =========================================================
     * FORMULAIRE MODIFICATION
     * =========================================================
     */
    public function edit(User $agent): View
    {
        $this->verifierUtilisateur($agent);

        $roles = Role::whereIn('nom', [
            'Superviseur',
            'Agent recenseur',
        ])->orderBy('nom')->get();

        $prefecture = Auth::user()
            ->rattachementPrefectureActif
            ?->prefecture;

        return view(
            'dpa.agents.edit',
            compact(
                'agent',
                'roles',
                'prefecture'
            )
        );
    }

    /**
     * =========================================================
     * METTRE À JOUR
     * =========================================================
     */
    public function update(
        UpdateDpaAgentRequest $request,
        User $agent
    ): RedirectResponse {

        $this->verifierUtilisateur($agent);

        $data = $request->validated();

        if (!empty($data['password'])) {

            $data['password'] = Hash::make(
                $data['password']
            );

        } else {

            unset($data['password']);

        }

        $agent->update($data);

        return redirect()
            ->route('dpa.agents.index')
            ->with(
                'success',
                'Le compte a été modifié avec succès.'
            );
    }

    /**
     * =========================================================
     * ACTIVER / DÉSACTIVER
     * =========================================================
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        $this->verifierUtilisateur($user);

        $user->update([
            'statut' => ! $user->statut,
        ]);

        return back()->with(
            'success',
            $user->statut
                ? 'Le compte a été réactivé.'
                : 'Le compte a été désactivé.'
        );
    }

    /**
     * =========================================================
     * PAS DE SUPPRESSION PHYSIQUE
     * =========================================================
     */
    public function destroy(User $agent): RedirectResponse
    {
        $this->verifierUtilisateur($agent);

        return back()->with(
            'error',
            'La suppression est interdite. Désactivez plutôt le compte.'
        );
    }
}