<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Prefecture;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * =========================================================
     * LISTE DES UTILISATEURS
     * =========================================================
     */
    // public function index(Request $request): View
    // {
    //     $search = $request->input('search');

    //     $users = User::with([
    //         'role',
    //         'rattachementPrefectureActif.prefecture',
    //     ])
    //         ->when($search, function ($query) use ($search) {

    //             $query->where(function ($q) use ($search) {

    //                 $q->where('name', 'like', "%{$search}%")
    //                     ->orWhere('email', 'like', "%{$search}%")
    //                     ->orWhere('telephone', 'like', "%{$search}%")
    //                      ->orWhere('telephone', 'like', "%{$search}%");


    //             });

    //         })
    //         ->latest()
    //         ->paginate(10)
    //         ->withQueryString();

    //     $roles = Role::orderBy('nom')->get();

    //     return view(
    //         'users.index',
    //         compact(
    //             'users',
    //             'roles',
    //             'search'
    //         )
    //     );
    // }


        public function index(Request $request): View
    {
        $search = $request->input('search');

        $users = User::with([
            'role',
            'rattachementPrefectureActif.prefecture',
        ])
            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('telephone', 'like', "%{$search}%")
                        ->orWhereHas('role', function ($roleQuery) use ($search) {
                            $roleQuery->where('nom', 'like', "%{$search}%");
                        });

                });

            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $roles = Role::orderBy('nom')->get();

        return view(
            'users.index',
            compact(
                'users',
                'roles',
                'search'
            )
        );
    }


    /**
     * =========================================================
     * FORMULAIRE DE CRÉATION
     * =========================================================
     *
     * ADMINISTRATEUR :
     * - DPA
     * - Superviseur
     * - Technicien
     *
     * DPA :
     * - CACH
     * - Agent recenseur
     */
    public function create(Request $request): View
    {
        $userConnecte = auth()->user();

        /*
         * =====================================================
         * ADMINISTRATEUR
         * =====================================================
         */
        if ($userConnecte->isAdmin()) {

            $roles = Role::whereIn('idRole', [
                'R02',
                'R03',
                'R04',
            ])
                ->orderBy('nom')
                ->get();

            $role = null;

            if ($request->filled('role')) {

                $role = match ($request->role) {

                    'dpa' => $roles->firstWhere(
                        'idRole',
                        'R02'
                    ),

                    'superviseur' => $roles->firstWhere(
                        'idRole',
                        'R03'
                    ),

                    'technicien' => $roles->firstWhere(
                        'idRole',
                        'R04'
                    ),

                    default => null,
                };
            }

            return view(
                'users.create',
                compact(
                    'roles',
                    'role'
                )
            );
        }


        /*
         * =====================================================
         * DPA
         * =====================================================
         */
        if ($userConnecte->isDpa()) {

            $roles = Role::whereIn('idRole', [
                'R05',
                'R06',
            ])
                ->orderBy('nom')
                ->get();

            $role = null;

            if ($request->filled('role')) {

                $role = match ($request->role) {

                    'cach' => $roles->firstWhere(
                        'idRole',
                        'R05'
                    ),

                    'agent' => $roles->firstWhere(
                        'idRole',
                        'R06'
                    ),

                    default => null,
                };
            }

            return view(
                'users.create',
                compact(
                    'roles',
                    'role'
                )
            );
        }

        abort(403);
    }


    /**
     * =========================================================
     * CRÉER UN UTILISATEUR
     * =========================================================
     */
    public function store(
        StoreUserRequest $request
    ): RedirectResponse {

        $userConnecte = auth()->user();

        $roleId = $request->role_id;


        /*
         * =====================================================
         * AUTORISATION DU RÔLE
         * =====================================================
         */

        if ($userConnecte->isAdmin()) {

            if (!in_array($roleId, [
                'R02',
                'R03',
                'R04',
            ], true)) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Le rôle sélectionné n’est pas autorisé.'
                    );
            }

        } elseif ($userConnecte->isDpa()) {

            if (!in_array($roleId, [
                'R05',
                'R06',
            ], true)) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Le rôle sélectionné n’est pas autorisé.'
                    );
            }

        } else {

            abort(403);
        }


        /*
         * =====================================================
         * CRÉATION DU COMPTE
         * =====================================================
         *
         * IMPORTANT :
         * La préfecture n'est PAS enregistrée dans users.
         *
         * Le rattachement à une préfecture est une opération
         * distincte qui sera enregistrée dans
         * rattachements_prefecture.
         */
        User::create([

            'name' => $request->name,

            'email' => $request->email,

            'telephone' => $request->telephone,

            'password' => Hash::make(
                $request->password
            ),

            'role_id' => $roleId,

            'statut' => true,

        ]);


        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'Utilisateur créé avec succès.'
            );
    }


    /**
     * =========================================================
     * AFFICHER UN UTILISATEUR
     * =========================================================
     */
    public function show(User $user): View
    {
        $user->load([
            'role',
            'rattachementsPrefecture.prefecture',
            'rattachementPrefectureActif.prefecture',
        ]);

        $prefectures = Prefecture::orderBy('nom')->get();

        return view(
            'users.show',
            compact(
                'user',
                'prefectures'
            )
        );
    }


    /**
     * =========================================================
     * FORMULAIRE DE MODIFICATION
     * =========================================================
     */
    public function edit(User $user): View
    {
        $roles = Role::orderBy('nom')->get();

        return view(
            'users.edit',
            compact(
                'user',
                'roles'
            )
        );
    }


    /**
     * =========================================================
     * MODIFIER UN UTILISATEUR
     * =========================================================
     */
    public function update(
        UpdateUserRequest $request,
        User $user
    ): RedirectResponse {

        $data = $request->validated();

        if (
            isset($data['password'])
            && !empty($data['password'])
        ) {

            $data['password'] = Hash::make(
                $data['password']
            );

        } else {

            unset($data['password']);
        }

        $user->update($data);

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'Utilisateur modifié avec succès.'
            );
    }


    /**
     * =========================================================
     * RATTACHER / MUTER UN UTILISATEUR
     * =========================================================
     *
     * Cette méthode conserve entièrement l'historique.
     *
     * Exemple :
     *
     * Ancien :
     * Mô
     * 01/08/2026 → 15/08/2026
     * terminé
     *
     * Nouveau :
     * Tône
     * 16/08/2026 → NULL
     * actif
     */
    public function rattacherPrefecture(
        Request $request,
        User $user
    ): RedirectResponse {

        /*
         * Seul l'administrateur effectue les mutations
         * préfectorales.
         */
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }


        /*
         * =====================================================
         * VALIDATION
         * =====================================================
         */
        $validated = $request->validate([

            'prefecture_id' => [
                'required',
                'exists:prefectures,idPrefecture',
            ],

        ]);


        /*
         * =====================================================
         * TRANSACTION
         * =====================================================
         *
         * Les deux opérations doivent réussir ensemble :
         *
         * 1. terminer l'ancien rattachement
         * 2. créer le nouveau rattachement
         */
        DB::transaction(function () use (
            $user,
            $validated
        ) {

            $dateDebutNouveau = now()->startOfDay();


            /*
             * =================================================
             * RATTACHEMENT ACTUEL
             * =================================================
             */
            $rattachementActuel =
                $user->rattachementPrefectureActif;


            /*
             * Si l'utilisateur est déjà rattaché
             * à cette même préfecture, rien à faire.
             */
            if (
                $rattachementActuel
                && (int) $rattachementActuel->prefecture_id
                    === (int) $validated['prefecture_id']
            ) {

                return;
            }


            /*
             * =================================================
             * TERMINER L'ANCIEN RATTACHEMENT
             * =================================================
             */
            if ($rattachementActuel) {

                $rattachementActuel->update([

                    'dateFin' => $dateDebutNouveau
                        ->copy()
                        ->subDay(),

                    'statut' => 'termine',

                ]);
            }


            /*
             * =================================================
             * CRÉER LE NOUVEAU RATTACHEMENT
             * =================================================
             */
            $user->rattachementsPrefecture()->create([

                'prefecture_id' =>
                    $validated['prefecture_id'],

                'dateDebut' =>
                    $dateDebutNouveau,

                'dateFin' =>
                    null,

                'statut' =>
                    'actif',

            ]);
        });


        return back()->with(
            'success',
            'Le rattachement à la nouvelle préfecture a été effectué avec succès.'
        );
    }


    /**
     * =========================================================
     * SUPPRIMER UN UTILISATEUR
     * =========================================================
     */
    public function destroy(
        User $user
    ): RedirectResponse {

        if (auth()->id() === $user->id) {

            return redirect()
                ->route('users.index')
                ->with(
                    'error',
                    'Vous ne pouvez pas supprimer votre propre compte.'
                );
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'Utilisateur supprimé avec succès.'
            );
    }


    /**
     * =========================================================
     * ACTIVER / DÉSACTIVER UN COMPTE
     * =========================================================
     */
    public function toggleStatus(
        User $user
    ): RedirectResponse {

        if (auth()->id() === $user->id) {

            return back()
                ->with(
                    'error',
                    'Vous ne pouvez pas désactiver votre propre compte.'
                );
        }

        $user->statut = !$user->statut;

        $user->save();

        return back()
            ->with(
                'success',
                'Le statut du compte a été modifié avec succès.'
            );
    }
}