<?php

namespace App\Http\Controllers\Dpa;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDpaAgentRequest;
use App\Http\Requests\UpdateDpaAgentRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class DpaAgentController extends Controller
{
    /**
     * =========================================================================
     * PRÉFECTURE DU DPA CONNECTÉ
     * =========================================================================
     */
    private function prefectureId(): int
    {
        $rattachement = Auth::user()->rattachementPrefectureActif;

        abort_if(
            !$rattachement?->prefecture_id,
            403,
            "Aucune préfecture n'est rattachée à votre compte."
        );

        return (int) $rattachement->prefecture_id;
    }

    /**
     * =========================================================================
     * VÉRIFIER L'APPARTENANCE À LA PRÉFECTURE
     * =========================================================================
     */
    private function verifierUtilisateur(User $user): void
    {
        $prefectureId = $this->prefectureId();

        $rattachement = $user->rattachementPrefectureActif;

        abort_if(
            !$rattachement ||
            (int) $rattachement->prefecture_id !== $prefectureId,
            403,
            "Cet utilisateur n'appartient pas à votre préfecture."
        );
    }

    /**
     * =========================================================================
     * VÉRIFIER QU'UN COMPTE EST GÉRABLE
     * =========================================================================
     */
    private function verifierCompteGerable(User $user): void
    {
        $this->verifierUtilisateur($user);

        abort_if(
            (int) $user->id === (int) Auth::id(),
            403,
            "Vous ne pouvez pas modifier votre propre compte depuis cette interface."
        );

        abort_unless(
            in_array(
                $user->role?->nom,
                [
                    'Superviseur',
                    'Agent recenseur',
                ],
                true
            ),
            403,
            "Ce compte ne peut pas être géré depuis cette interface."
        );
    }

    /**
     * =========================================================================
     * INDEX
     * =========================================================================
     */
    public function index(Request $request): View
    {
        $prefectureId = $this->prefectureId();

        $search = trim((string) $request->input('search'));
        $role   = $request->input('role');
        $statut = $request->input('statut');

        $roles = Role::query()
            ->whereIn('nom', [
                'Superviseur',
                'Agent recenseur',
            ])
            ->orderBy('nom')
            ->get();

        $agents = User::query()
            ->with([
                'role',
                'rattachementPrefectureActif.prefecture',
            ])

            /*
             * UNIQUEMENT LA PRÉFECTURE DU DPA
             */
            ->whereHas(
                'rattachementPrefectureActif',
                function ($query) use ($prefectureId) {
                    $query
                        ->where('prefecture_id', $prefectureId)
                        ->where('statut', 'actif')
                        ->whereNull('dateFin');
                }
            )

            /*
             * UNIQUEMENT SUPERVISEUR / AGENT
             */
            ->whereHas('role', function ($query) {
                $query->whereIn('nom', [
                    'Superviseur',
                    'Agent recenseur',
                ]);
            })

            /*
             * RECHERCHE
             */
            ->when(
                filled($search),
                function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('telephone', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('login', 'like', "%{$search}%");
                    });
                }
            )

            /*
             * FILTRE RÔLE
             */
            ->when(
                filled($role),
                function ($query) use ($role) {
                    $query->whereHas('role', function ($q) use ($role) {
                        $q->where('nom', $role);
                    });
                }
            )

            /*
             * FILTRE STATUT
             */
            ->when(
                $statut !== null && $statut !== '',
                function ($query) use ($statut) {
                    $query->where(
                        'statut',
                        (bool) $statut
                    );
                }
            )

            ->orderBy('name')
            ->paginate(7)
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
     * =========================================================================
     * CREATE
     * =========================================================================
     */
    public function create(): View
    {
        $roles = Role::query()
            ->whereIn('nom', [
                'Superviseur',
                'Agent recenseur',
            ])
            ->orderBy('nom')
            ->get();

        $prefecture = Auth::user()
            ->rattachementPrefectureActif
            ?->prefecture;

        abort_if(
            !$prefecture,
            403,
            "Aucune préfecture n'est rattachée à votre compte."
        );

        return view(
            'dpa.agents.create',
            compact(
                'roles',
                'prefecture'
            )
        );
    }

    /**
     * =========================================================================
     * STORE
     * =========================================================================
     */
    public function store(
        StoreDpaAgentRequest $request
    ): RedirectResponse {

        $prefectureId = $this->prefectureId();

        $role = Role::query()
            ->whereKey($request->role_id)
            ->firstOrFail();

        abort_unless(
            in_array(
                $role->nom,
                [
                    'Superviseur',
                    'Agent recenseur',
                ],
                true
            ),
            403,
            "Ce rôle ne peut pas être créé depuis cette interface."
        );

        DB::transaction(function () use (
            $request,
            $prefectureId,
            $role
        ) {

            $user = User::create([
                'name' => $request->name,
                'telephone' => $request->telephone,
                'email' => $request->email,
                'role_id' => $role->idRole,
                'password' => Hash::make($request->password),
                'statut' => true,
            ]);

            $user->rattachementsPrefecture()->create([
                'prefecture_id' => $prefectureId,
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
     * =========================================================================
     * SHOW
     * =========================================================================
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
     * =========================================================================
     * EDIT
     * =========================================================================
     */
    public function edit(User $agent): View
    {
        $this->verifierCompteGerable($agent);

        $roles = Role::query()
            ->whereIn('nom', [
                'Superviseur',
                'Agent recenseur',
            ])
            ->orderBy('nom')
            ->get();

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
     * =========================================================================
     * UPDATE
     * =========================================================================
     */
    public function update(
        UpdateDpaAgentRequest $request,
        User $agent
    ): RedirectResponse {

        $this->verifierCompteGerable($agent);

        $data = $request->validated();

        if (
            isset($data['password']) &&
            filled($data['password'])
        ) {
            $data['password'] = Hash::make(
                $data['password']
            );
        } else {
            unset($data['password']);
        }

        if (isset($data['role_id'])) {

            $role = Role::query()
                ->whereKey($data['role_id'])
                ->firstOrFail();

            abort_unless(
                in_array(
                    $role->nom,
                    [
                        'Superviseur',
                        'Agent recenseur',
                    ],
                    true
                ),
                403,
                "Ce rôle ne peut pas être attribué depuis cette interface."
            );
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
     * =========================================================================
     * TOGGLE STATUS
     * =========================================================================
     */
    public function toggleStatus(
        User $agent
    ): RedirectResponse {

        $this->verifierCompteGerable($agent);

        $agent->update([
            'statut' => !$agent->statut,
        ]);

        return back()->with(
            'success',
            $agent->statut
                ? 'Le compte a été réactivé.'
                : 'Le compte a été désactivé.'
        );
    }

    /**
     * =========================================================================
     * DESTROY
     * =========================================================================
     */
    public function destroy(
        User $agent
    ): RedirectResponse {

        $this->verifierCompteGerable($agent);

        return back()->with(
            'error',
            'La suppression est interdite. Désactivez plutôt le compte.'
        );
    }
}