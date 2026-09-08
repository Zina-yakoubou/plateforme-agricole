<?php

namespace App\Http\Controllers\Dpa;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEquipeRequest;
use App\Http\Requests\UpdateEquipeRequest;
use App\Models\Equipe;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EquipeController extends Controller
{
    /**
     * =========================================================================
     * PRÉFECTURE DU DPA
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
     * VÉRIFIER UNE ÉQUIPE
     * =========================================================================
     */
    private function verifierEquipePrefecture(
        Equipe $equipe
    ): void {

        $prefectureId = $this->prefectureId();

        /*
         * Si l'équipe possède directement une préfecture,
         * elle doit correspondre à celle du DPA.
         */
        if (
            $equipe->prefecture_id !== null &&
            (int) $equipe->prefecture_id !== $prefectureId
        ) {
            abort(
                403,
                "Cette équipe n'appartient pas à votre préfecture."
            );
        }

        /*
         * Sinon on passe par le superviseur.
         */
        $equipe->loadMissing('superviseur');

        $superviseur = $equipe->superviseur;

        abort_if(
            !$superviseur,
            403,
            "Le superviseur de cette équipe est introuvable."
        );

        $rattachement =
            $superviseur->rattachementPrefectureActif;

        abort_if(
            !$rattachement ||
            (int) $rattachement->prefecture_id !== $prefectureId,
            403,
            "Cette équipe n'appartient pas à votre préfecture."
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

        $query = Equipe::query()
            ->with([
                'superviseur',
                'membres',
            ])
            ->withCount('membres')

            /*
             * ÉQUIPE DU DPA UNIQUEMENT
             */
            ->where(function ($q) use ($prefectureId) {

                /*
                 * Si prefecture_id est renseigné.
                 */
                $q->where('prefecture_id', $prefectureId)

                    /*
                     * Sinon appartenance via le superviseur.
                     */
                    ->orWhereHas(
                        'superviseur.rattachementPrefectureActif',
                        function ($q) use ($prefectureId) {
                            $q->where(
                                'prefecture_id',
                                $prefectureId
                            )
                            ->where(
                                'statut',
                                'actif'
                            )
                            ->whereNull(
                                'dateFin'
                            );
                        }
                    );
            });

        /*
         * RECHERCHE
         */
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'reference',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'nom',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'libelle',
                    'like',
                    "%{$search}%"
                )
                ->orWhereHas(
                    'superviseur',
                    function ($q) use ($search) {

                        $q->where(
                            'name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'telephone',
                            'like',
                            "%{$search}%"
                        );
                    }
                );
            });
        }

        /*
         * STATUT
         */
        if ($request->filled('statut')) {

            $query->where(
                'statut',
                strtoupper(
                    $request->statut
                )
            );
        }

        $equipes = $query
            ->latest('idEquipe')
            ->paginate(15)
            ->withQueryString();

        return view(
            'dpa.equipes.index',
            [
                'equipes' => $equipes,
                'search' => $request->search,
            ]
        );
    }

    /**
     * =========================================================================
     * CREATE
     * =========================================================================
     */
    public function create(): View
    {
        $prefectureId = $this->prefectureId();

        /*
         * SUPERVISEURS DE LA PRÉFECTURE
         */
        $superviseurs = User::query()
            ->with('rattachementPrefectureActif')
            ->whereHas('role', function ($query) {
                $query->where(
                    'nom',
                    'Superviseur'
                );
            })
            ->whereHas(
                'rattachementPrefectureActif',
                function ($query) use ($prefectureId) {
                    $query
                        ->where(
                            'prefecture_id',
                            $prefectureId
                        )
                        ->where(
                            'statut',
                            'actif'
                        )
                        ->whereNull(
                            'dateFin'
                        );
                }
            )
            ->where(
                'statut',
                true
            )
            ->orderBy('name')
            ->get();

        /*
         * AGENTS DE LA PRÉFECTURE
         */
        $agents = User::query()
            ->whereHas('role', function ($query) {
                $query->where(
                    'nom',
                    'Agent recenseur'
                );
            })
            ->whereHas(
                'rattachementPrefectureActif',
                function ($query) use ($prefectureId) {
                    $query
                        ->where(
                            'prefecture_id',
                            $prefectureId
                        )
                        ->where(
                            'statut',
                            'actif'
                        )
                        ->whereNull(
                            'dateFin'
                        );
                }
            )
            ->where(
                'statut',
                true
            )
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

    /**
     * =========================================================================
     * STORE
     * =========================================================================
     */
    public function store(
        StoreEquipeRequest $request
    ): RedirectResponse {

        $prefectureId = $this->prefectureId();

        /*
         * Le superviseur doit appartenir à la préfecture.
         */
        $superviseur = User::query()
            ->whereKey(
                $request->superviseur_id
            )
            ->whereHas(
                'role',
                function ($query) {
                    $query->where(
                        'nom',
                        'Superviseur'
                    );
                }
            )
            ->whereHas(
                'rattachementPrefectureActif',
                function ($query) use ($prefectureId) {
                    $query
                        ->where(
                            'prefecture_id',
                            $prefectureId
                        )
                        ->where(
                            'statut',
                            'actif'
                        )
                        ->whereNull(
                            'dateFin'
                        );
                }
            )
            ->where(
                'statut',
                true
            )
            ->first();

        abort_if(
            !$superviseur,
            403,
            "Le superviseur sélectionné n'appartient pas à votre préfecture."
        );

        /*
         * Tous les membres doivent appartenir à la préfecture.
         */
        $membres = User::query()
            ->whereIn(
                'id',
                $request->membres ?? []
            )
            ->whereHas(
                'role',
                function ($query) {
                    $query->where(
                        'nom',
                        'Agent recenseur'
                    );
                }
            )
            ->whereHas(
                'rattachementPrefectureActif',
                function ($query) use ($prefectureId) {
                    $query
                        ->where(
                            'prefecture_id',
                            $prefectureId
                        )
                        ->where(
                            'statut',
                            'actif'
                        )
                        ->whereNull(
                            'dateFin'
                        );
                }
            )
            ->where(
                'statut',
                true
            )
            ->pluck('id');

        $demandesMembres = collect(
            $request->membres ?? []
        )->map(
            fn ($id) => (int) $id
        )->unique();

        abort_if(
            $membres->count() !== $demandesMembres->count(),
            403,
            "Un ou plusieurs agents sélectionnés n'appartiennent pas à votre préfecture."
        );

        $equipe = DB::transaction(
            function () use (
                $request,
                $membres,
                $prefectureId,
                $superviseur
            ) {

                $equipe = Equipe::create([
                    'reference' =>
                        $this->genererReference(),

                    'nom' =>
                        $request->nom,

                    /*
                     * On conserve la préfecture de l'équipe
                     * si cette colonne existe dans ta table.
                     */
                    'prefecture_id' =>
                        $prefectureId,

                    'superviseur_id' =>
                        $superviseur->id,

                    'statut' =>
                        'ACTIVE',
                ]);

                $equipe->membres()->sync(
                    $membres
                );

                return $equipe;
            }
        );

        return redirect()
            ->route(
                'dpa.equipes.index',
                $equipe
            )
            ->with(
                'success',
                "L'équipe a été créée avec succès."
            );
    }

    /**
     * =========================================================================
     * SHOW
     * =========================================================================
     */
    public function show(
        Equipe $equipe
    ): View {

        $this->verifierEquipePrefecture(
            $equipe
        );

        $equipe->load([
            'superviseur',
            'membres',
        ]);

        return view(
            'dpa.equipes.show',
            compact('equipe')
        );
    }

    /**
     * =========================================================================
     * EDIT
     * =========================================================================
     */
    public function edit(
        Equipe $equipe
    ): View {

        $this->verifierEquipePrefecture(
            $equipe
        );

        $prefectureId = $this->prefectureId();

        $superviseurs = User::query()
            ->whereHas('role', function ($query) {
                $query->where(
                    'nom',
                    'Superviseur'
                );
            })
            ->whereHas(
                'rattachementPrefectureActif',
                function ($query) use ($prefectureId) {
                    $query
                        ->where(
                            'prefecture_id',
                            $prefectureId
                        )
                        ->where(
                            'statut',
                            'actif'
                        )
                        ->whereNull(
                            'dateFin'
                        );
                }
            )
            ->where(
                'statut',
                true
            )
            ->orderBy('name')
            ->get();

        $agents = User::query()
            ->whereHas('role', function ($query) {
                $query->where(
                    'nom',
                    'Agent recenseur'
                );
            })
            ->whereHas(
                'rattachementPrefectureActif',
                function ($query) use ($prefectureId) {
                    $query
                        ->where(
                            'prefecture_id',
                            $prefectureId
                        )
                        ->where(
                            'statut',
                            'actif'
                        )
                        ->whereNull(
                            'dateFin'
                        );
                }
            )
            ->where(
                'statut',
                true
            )
            ->orderBy('name')
            ->get();

        $membresActuels = $equipe
            ->membres()
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

    /**
     * =========================================================================
     * UPDATE
     * =========================================================================
     */
    public function update(
        UpdateEquipeRequest $request,
        Equipe $equipe
    ): RedirectResponse {

        $this->verifierEquipePrefecture(
            $equipe
        );

        $prefectureId = $this->prefectureId();

        $superviseur = User::query()
            ->whereKey(
                $request->superviseur_id
            )
            ->whereHas(
                'role',
                fn ($q) =>
                    $q->where(
                        'nom',
                        'Superviseur'
                    )
            )
            ->whereHas(
                'rattachementPrefectureActif',
                function ($q) use ($prefectureId) {
                    $q->where(
                        'prefecture_id',
                        $prefectureId
                    )
                    ->where(
                        'statut',
                        'actif'
                    )
                    ->whereNull(
                        'dateFin'
                    );
                }
            )
            ->first();

        abort_if(
            !$superviseur,
            403,
            "Le superviseur sélectionné n'appartient pas à votre préfecture."
        );

        $membres = User::query()
            ->whereIn(
                'id',
                $request->membres ?? []
            )
            ->whereHas(
                'role',
                fn ($q) =>
                    $q->where(
                        'nom',
                        'Agent recenseur'
                    )
            )
            ->whereHas(
                'rattachementPrefectureActif',
                function ($q) use ($prefectureId) {
                    $q->where(
                        'prefecture_id',
                        $prefectureId
                    )
                    ->where(
                        'statut',
                        'actif'
                    )
                    ->whereNull(
                        'dateFin'
                    );
                }
            )
            ->pluck('id');

        abort_if(
            $membres->count() !== count(
                array_unique(
                    array_map(
                        'intval',
                        $request->membres ?? []
                    )
                )
            ),
            403,
            "Un ou plusieurs agents sélectionnés n'appartiennent pas à votre préfecture."
        );

        DB::transaction(function () use (
            $request,
            $equipe,
            $superviseur,
            $membres
        ) {

            $equipe->update([
                'nom' =>
                    $request->nom,

                'superviseur_id' =>
                    $superviseur->id,
            ]);

            $equipe->membres()->sync(
                $membres
            );
        });

        return redirect()
            ->route(
                'dpa.equipes.show',
                $equipe
            )
            ->with(
                'success',
                "L'équipe a été modifiée avec succès."
            );
    }

    /**
     * =========================================================================
     * DESTROY
     * =========================================================================
     */
    public function destroy(
        Equipe $equipe
    ): RedirectResponse {

        $this->verifierEquipePrefecture(
            $equipe
        );

        $equipe->update([
            'statut' => 'INACTIVE',
        ]);

        return redirect()
            ->route(
                'dpa.equipes.index'
            )
            ->with(
                'success',
                "L'équipe a été désactivée avec succès."
            );
    }

    /**
     * =========================================================================
     * RÉACTIVER
     * =========================================================================
     */
    public function reactiver(
        Equipe $equipe
    ): RedirectResponse {

        $this->verifierEquipePrefecture(
            $equipe
        );

        if ($equipe->statut === 'ACTIVE') {
            return redirect()
                ->route(
                    'dpa.equipes.index'
                )
                ->with(
                    'error',
                    "Cette équipe est déjà active."
                );
        }

        $equipe->update([
            'statut' => 'ACTIVE',
        ]);

        return redirect()
            ->route(
                'dpa.equipes.index'
            )
            ->with(
                'success',
                "L'équipe a été réactivée avec succès."
            );
    }

    /**
     * =========================================================================
     * RÉFÉRENCE
     * =========================================================================
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


    

    public function mesEquipes(Request $request): View
    {
        $user = Auth::user();

        $search = trim($request->input('search', ''));

        $equipes = Equipe::query()
            ->with([
                'superviseur',
                'membres',
                'affectations.village.canton.commune',
                'affectations.campagne',
            ])
            ->where('superviseur_id', $user->id)

            ->when($search !== '', function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where('reference', 'like', "%{$search}%")
                        ->orWhere('libelle', 'like', "%{$search}%")
                        ->orWhere('nom', 'like', "%{$search}%");
                });
            })

            ->orderBy('libelle')
            ->paginate(10)
            ->withQueryString();

        return view(
            'superviseur.equipes.index',
            compact('equipes', 'search')
        );
    }



        public function showSuperviseur(Equipe $equipe): View
    {
        $user = Auth::user();

        abort_unless(
            $equipe->superviseur_id === $user->id,
            403
        );

        $equipe->load([
            'superviseur',
            'membres',
            'affectations.campagne',
            'affectations.village.canton.commune.prefecture',
        ]);

        return view(
            'superviseur.equipes.show',
            compact('equipe')
        );
    }
}