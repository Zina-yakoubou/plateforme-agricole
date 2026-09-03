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
     * ID DE LA PRÉFECTURE DU DPA CONNECTÉ
     * =========================================================================
     */
    private function prefectureId(): int
    {
        $rattachement = Auth::user()->rattachementPrefectureActif;

        abort_if(
            ! $rattachement?->prefecture_id,
            403,
            "Aucune préfecture n'est rattachée à votre compte."
        );

        return (int) $rattachement->prefecture_id;
    }

    /**
     * =========================================================================
     * VÉRIFIER QU'UN UTILISATEUR APPARTIENT À LA PRÉFECTURE DU DPA
     * =========================================================================
     */
    private function verifierUtilisateur(User $user): void
    {
        $prefectureId = $this->prefectureId();

        $prefectureUtilisateur =
            $user->rattachementPrefectureActif?->prefecture_id;

        abort_if(
            ! $prefectureUtilisateur
                || (int) $prefectureUtilisateur !== $prefectureId,
            403,
            "Cet utilisateur n'appartient pas à votre préfecture."
        );
    }

    /**
     * =========================================================================
     * VÉRIFIER QU'UN COMPTE PEUT ÊTRE GÉRÉ PAR LE DPA
     * =========================================================================
     */
    private function verifierCompteGerable(User $user): void
    {
        /*
         * L'utilisateur doit appartenir à la même préfecture.
         */
        $this->verifierUtilisateur($user);

        /*
         * Le DPA ne peut pas modifier son propre compte
         * depuis cette interface.
         */
        abort_if(
            (int) $user->id === (int) Auth::id(),
            403,
            "Vous ne pouvez pas modifier votre propre compte depuis cette interface."
        );

        /*
         * Le DPA ne gère que :
         * - Superviseur
         * - Agent recenseur
         *
         * Aucun ID de rôle n'est utilisé ici.
         */
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
     * LISTE DES AGENTS / SUPERVISEURS
     * =========================================================================
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));
        $role   = $request->input('role');
        $statut = $request->input('statut');

        /*
         * Préfecture du DPA connecté.
         */
        $prefectureId = $this->prefectureId();

        /*
         * Rôles disponibles dans le filtre.
         *
         * Pas d'ID brut.
         */
        $roles = Role::query()
            ->whereIn('nom', [
                'Superviseur',
                'Agent recenseur',
            ])
            ->orderBy('nom')
            ->get();

        /*
         * ---------------------------------------------------------------------
         * UTILISATEURS DE LA PRÉFECTURE UNIQUEMENT
         * ---------------------------------------------------------------------
         *
         * On passe obligatoirement par le rattachement préfectoral actif.
         *
         * Un agent d'une autre préfecture ne sera donc pas retourné.
         */
        $agents = User::query()
            ->with([
                'role',
                'rattachementPrefectureActif.prefecture',
            ])

            /*
             * Préfecture du DPA.
             */
            ->whereHas(
                'rattachementPrefectureActif',
                function ($query) use ($prefectureId) {
                    $query
                        ->where('prefecture_id', $prefectureId)
                        ->where('statut', 'actif');
                }
            )

            /*
             * Seulement les comptes gérés par le DPA.
             */
            ->whereHas('role', function ($query) {
                $query->whereIn('nom', [
                    'Superviseur',
                    'Agent recenseur',
                ]);
            })

            /*
             * Recherche.
             */
            ->when(
                filled($search),
                function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere(
                                'telephone',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'email',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'login',
                                'like',
                                "%{$search}%"
                            );
                    });
                }
            )

            /*
             * Filtre par rôle.
             *
             * Le formulaire envoie le nom du rôle,
             * pas son ID.
             */
            ->when(
                filled($role),
                function ($query) use ($role) {
                    $query->whereHas(
                        'role',
                        function ($q) use ($role) {
                            $q->where('nom', $role);
                        }
                    );
                }
            )

            /*
             * Filtre actif / désactivé.
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
     * =========================================================================
     * FORMULAIRE DE CRÉATION
     * =========================================================================
     */
    public function create(): View
    {
        /*
         * Le DPA ne peut créer que ces deux types de comptes.
         *
         * Pas de DPA, pas d'administrateur.
         */
        $roles = Role::query()
            ->whereIn('nom', [
                'Superviseur',
                'Agent recenseur',
            ])
            ->orderBy('nom')
            ->get();

        /*
         * Préfecture du DPA.
         */
        $prefecture = Auth::user()
            ->rattachementPrefectureActif
            ?->prefecture;

        abort_if(
            ! $prefecture,
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
     * CRÉATION D'UN AGENT / SUPERVISEUR
     * =========================================================================
     */
    public function store(
        StoreDpaAgentRequest $request
    ): RedirectResponse {
        /*
         * On récupère la préfecture du DPA.
         *
         * Elle ne vient PAS du formulaire.
         */
        $prefectureId = $this->prefectureId();

        /*
         * Vérification supplémentaire du rôle demandé.
         *
         * Même si quelqu'un modifie le formulaire manuellement,
         * il ne pourra pas créer un administrateur ou un DPA.
         */
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

            /*
             * Création du compte.
             */
            $user = User::create([
                'name' => $request->name,
                'telephone' => $request->telephone,
                'email' => $request->email,
                'role_id' => $role->idRole,
                'password' => Hash::make($request->password),
                'statut' => true,
            ]);

            /*
             * RATTACHEMENT AUTOMATIQUE À LA PRÉFECTURE DU DPA.
             *
             * Le DPA n'envoie aucune prefecture_id depuis le formulaire.
             */
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
     * AFFICHER UN AGENT
     * =========================================================================
     */
    public function show(User $agent): View
    {
        /*
         * Impossible d'afficher un agent d'une autre préfecture.
         */
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
     * FORMULAIRE DE MODIFICATION
     * =========================================================================
     */
    public function edit(User $agent): View
    {
        /*
         * Même préfecture + compte gérable.
         */
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

        abort_if(
            ! $prefecture,
            403,
            "Aucune préfecture n'est rattachée à votre compte."
        );

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
     * MODIFICATION
     * =========================================================================
     */
    public function update(
        UpdateDpaAgentRequest $request,
        User $agent
    ): RedirectResponse {
        /*
         * Vérifie :
         * - même préfecture
         * - pas son propre compte
         * - rôle gérable
         */
        $this->verifierCompteGerable($agent);

        $data = $request->validated();

        /*
         * Si un mot de passe est fourni,
         * on le chiffre.
         */
        if (
            isset($data['password'])
            && filled($data['password'])
        ) {
            $data['password'] = Hash::make(
                $data['password']
            );
        } else {
            /*
             * Aucun nouveau mot de passe :
             * on conserve l'ancien.
             */
            unset($data['password']);
        }

        /*
         * Sécurité supplémentaire :
         *
         * On vérifie que le rôle demandé reste
         * dans les rôles autorisés.
         */
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
     * ACTIVER / DÉSACTIVER UN COMPTE
     * =========================================================================
     */
    public function toggleStatus(
        User $agent
    ): RedirectResponse {
        /*
         * Le compte doit appartenir à la préfecture du DPA.
         */
        $this->verifierCompteGerable($agent);

        $agent->update([
            'statut' => ! $agent->statut,
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
     * SUPPRESSION
     * =========================================================================
     *
     * La suppression physique des comptes est interdite.
     * On utilise la désactivation.
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