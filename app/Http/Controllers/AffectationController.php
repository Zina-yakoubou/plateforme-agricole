<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAffectationRequest;
use App\Models\Affectation;
use App\Models\CampagneRecensement;
use App\Models\CampagneDeploiement;
use App\Models\Canton;
use App\Models\Equipe;
use App\Models\User;
use App\Models\Village;
use App\Models\RattachementPrefecture;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AffectationController extends Controller
{
    /**
     * Liste des affectations de la préfecture du DPA.
     */
    // public function index(Request $request): View
    // {
    //     $user = Auth::user();

    //     $prefectureId = $this->prefectureIdUtilisateur($user);

    //     if (!$prefectureId) {
    //         abort(
    //             403,
    //             'Aucune préfecture active n’est associée à votre compte.'
    //         );
    //     }

    //     $affectations = Affectation::query()
    //         ->with([
    //             'campagne',
    //             'equipe.superviseur',
    //             'equipe.membres',
    //             'village.canton.commune',
    //         ])
    //         ->whereHas('equipe', function ($query) use ($prefectureId) {

    //             $query->where(function ($q) use ($prefectureId) {

    //                 $q->where(
    //                     'prefecture_id',
    //                     $prefectureId
    //                 )
    //                 ->orWhereHas(
    //                     'superviseur',
    //                     function ($superviseur) use ($prefectureId) {

    //                         $superviseur->where(
    //                             'prefecture_id',
    //                             $prefectureId
    //                         );
    //                     }
    //                 );
    //             });
    //         })
    //         ->when(
    //             $request->filled('campagne_id'),
    //             function ($query) use ($request) {

    //                 $query->where(
    //                     'campagne_id',
    //                     $request->campagne_id
    //                 );
    //             }
    //         )
    //         ->when(
    //             $request->filled('statut'),
    //             function ($query) use ($request) {

    //                 $query->where(
    //                     'statut',
    //                     $request->statut
    //                 );
    //             }
    //         )
    //         ->latest('idAffectation')
    //         ->paginate(15)
    //         ->withQueryString();

    //     return view(
    //         'dpa.affectations.index',
    //         compact('affectations')
    //     );
    // }



    // public function index(Request $request): View
    // {
    //     $user = Auth::user();
    //     $prefectureId = $this->prefectureIdUtilisateur($user);

    //     if (!$prefectureId) {
    //         abort(403, "Aucune préfecture active n'est associée à votre compte.");
    //     }

    //     $affectations = Affectation::with([
    //             'campagne',
    //             'equipe.superviseur',
    //             'equipe.membres',
    //             'village.canton.commune',
    //         ])
    //         ->where('prefecture_id', $prefectureId)
    //         ->when($request->filled('campagne_id'), function ($query) use ($request) {
    //             $query->where('campagne_id', $request->campagne_id);
    //         })
    //         ->when($request->filled('statut'), function ($query) use ($request) {
    //             $query->where('statut', $request->statut);
    //         })
    //         ->orderByDesc('idAffectation')
    //         ->paginate(15)
    //         ->withQueryString();
    //         $test = Affectation::query()
    //     ->orderByDesc('idAffectation')
    //     ->get();

    // dd($test);

    //     return view('dpa.affectations.index', compact('affectations'));
    // }


    public function index(Request $request): View
    {
        $query = Affectation::with([
            'campagne',
            'equipe.superviseur',
            'equipe.membres',
            'village.canton.commune',
        ]);

        // ============================================================
        // FILTRE : STATUT
        // ============================================================
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // ============================================================
        // FILTRE : CAMPAGNE
        // ============================================================
        if ($request->filled('campagne_id')) {
            $query->where('campagne_id', $request->campagne_id);
        }

        // ============================================================
        // FILTRE : ÉQUIPE
        // ============================================================
        if ($request->filled('equipe_id')) {
            $query->where('equipe_id', $request->equipe_id);
        }

        // ============================================================
        // FILTRE : SUPERVISEUR
        // ============================================================
        if ($request->filled('superviseur_id')) {
            $query->whereHas('equipe', function ($q) use ($request) {
                $q->where('superviseur_id', $request->superviseur_id);
            });
        }

        // ============================================================
        // FILTRE : CANTON
        // ============================================================
        if ($request->filled('canton_id')) {
            $query->whereHas('village', function ($q) use ($request) {
                $q->where('canton_id', $request->canton_id);
            });
        }

        // ============================================================
        // FILTRE : VILLAGE
        // ============================================================
        if ($request->filled('village_id')) {
            $query->where('village_id', $request->village_id);
        }

        // ============================================================
        // RECHERCHE
        // ============================================================
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('reference', 'like', "%{$search}%")

                    ->orWhereHas('campagne', function ($q) use ($search) {
                        $q->where('libelle', 'like', "%{$search}%");
                    })

                    ->orWhereHas('equipe', function ($q) use ($search) {
                        $q->where('reference', 'like', "%{$search}%")
                        ->orWhere('libelle', 'like', "%{$search}%");
                    })

                    ->orWhereHas('village', function ($q) use ($search) {
                        $q->where('nom', 'like', "%{$search}%");
                    });
            });
        }

        // ============================================================
        // PAGINATION
        // ============================================================
        $affectations = $query
            ->latest('idAffectation')
            ->paginate(15)
            ->withQueryString();

        // ============================================================
        // DONNÉES POUR LES FILTRES
        // ============================================================
        $campagnes = CampagneRecensement::orderBy('dateDebut', 'desc')
            ->get();

        $equipes = Equipe::orderBy('libelle')
            ->get();

        $superviseurs = User::whereHas('equipesSupervisees')
            ->orderBy('name')
            ->get();

        $cantons = Canton::orderBy('nom')
            ->get();

        $villages = Village::orderBy('nom')
            ->get();

        return view('dpa.affectations.index', compact(
            'affectations',
            'campagnes',
            'equipes',
            'superviseurs',
            'cantons',
            'villages'
        ));
    }

    /**
     * Formulaire de création d'une affectation.
     *
     * Chaque village transmis au formulaire porte les
     * informations de sa commune et de son canton, pour
     * permettre une sélection à n'importe quel niveau
     * (préfecture entière, commune, canton, ou village)
     * sans obliger l'utilisateur à descendre jusqu'au
     * village pour chaque choix.
     */
    public function create(Equipe $equipe): View
    {
        $user = Auth::user();

        $prefectureId = $this->prefectureIdUtilisateur($user);

        if (!$prefectureId) {
            abort(
                403,
                'Aucune préfecture active n’est associée à votre compte.'
            );
        }

        $this->verifierEquipePrefecture(
            $equipe,
            $prefectureId
        );

        $campagnes = CampagneRecensement::query()
            ->whereIn('statut', [
                'planifiee',
                'active',
            ])
            ->whereHas('deploiements', function ($query) use ($prefectureId) {
                $query->where(
                    'prefecture_id',
                    $prefectureId
                );
            })
            ->with([
                'deploiements' => function ($query) use ($prefectureId) {
                    $query->where(
                        'prefecture_id',
                        $prefectureId
                    );
                },

                'zones.region',
                'zones.prefecture',
                'zones.commune',
                'zones.canton',
                'zones.village',
            ])
            ->orderByDesc('dateDebut')
            ->get();

        $campagnesJs = $campagnes
            ->map(function ($campagne) {

                return [
                    'idCampagne'   => $campagne->idCampagne,
                    'codeCampagne' => $campagne->codeCampagne,
                    'libelle'      => $campagne->libelle,
                    'statut'       => $campagne->statut,
                    'portee'       => $campagne->portee,
                ];
            })
            ->values()
            ->all();

        $villagesCampagneJs = [];

        foreach ($campagnes as $campagne) {

            if ($campagne->portee === 'nationale') {

                $villages = Village::query()
                    ->whereHas('canton.commune', function ($query) use ($prefectureId) {
                        $query->where(
                            'prefecture_id',
                            $prefectureId
                        );
                    })
                    ->with([
                        'canton.commune',
                    ])
                    ->orderBy('nom')
                    ->get();

                foreach ($villages as $village) {

                    $commune = optional($village->canton)->commune;

                    $villagesCampagneJs[] = [
                        'campagne_id'  => $campagne->idCampagne,
                        'commune_id'   => optional($commune)->idCommune,
                        'commune_nom'  => optional($commune)->nom,
                        'canton_id'    => $village->canton_id,
                        'canton_nom'   => optional($village->canton)->nom,
                        'idVillage'    => $village->idVillage,
                        'nom'          => $village->nom,
                        'code'         => $village->code,
                    ];
                }

                continue;
            }

            $villages = Village::query()
                ->whereHas('canton.commune', function ($query) use ($prefectureId) {
                    $query->where(
                        'prefecture_id',
                        $prefectureId
                    );
                })
                ->with([
                    'canton.commune.prefecture',
                ])
                ->orderBy('nom')
                ->get();

            $zones = $campagne->zones;

            foreach ($villages as $village) {

                $commune = optional(
                    $village->canton
                )->commune;

                $prefecture = optional(
                    $commune
                )->prefecture;

                if (!$prefecture) {
                    continue;
                }

                if (
                    (int) $prefecture->idPrefecture !==
                    (int) $prefectureId
                ) {
                    continue;
                }

                $couvertParCampagne = false;

                foreach ($zones as $zone) {

                    if (
                        !empty($zone->region_id) &&
                        !empty($prefecture->region_id) &&
                        (int) $zone->region_id ===
                        (int) $prefecture->region_id
                    ) {
                        $couvertParCampagne = true;
                        break;
                    }

                    if (
                        !empty($zone->prefecture_id) &&
                        (int) $zone->prefecture_id ===
                        (int) $prefecture->idPrefecture
                    ) {
                        $couvertParCampagne = true;
                        break;
                    }

                    if (
                        !empty($zone->commune_id) &&
                        $commune &&
                        (int) $zone->commune_id ===
                        (int) $commune->idCommune
                    ) {
                        $couvertParCampagne = true;
                        break;
                    }

                    if (
                        !empty($zone->canton_id) &&
                        (int) $zone->canton_id ===
                        (int) $village->canton_id
                    ) {
                        $couvertParCampagne = true;
                        break;
                    }

                    if (
                        !empty($zone->village_id) &&
                        (int) $zone->village_id ===
                        (int) $village->idVillage
                    ) {
                        $couvertParCampagne = true;
                        break;
                    }
                }

                if ($couvertParCampagne) {

                    $villagesCampagneJs[] = [
                        'campagne_id'  => $campagne->idCampagne,
                        'commune_id'   => optional($commune)->idCommune,
                        'commune_nom'  => optional($commune)->nom,
                        'canton_id'    => $village->canton_id,
                        'canton_nom'   => optional($village->canton)->nom,
                        'idVillage'    => $village->idVillage,
                        'nom'          => $village->nom,
                        'code'         => $village->code,
                    ];
                }
            }
        }

        $villagesCampagneJs = collect($villagesCampagneJs)
            ->unique(function ($village) {
                return
                    $village['campagne_id'] .
                    '-' .
                    $village['idVillage'];
            })
            ->sortBy('nom')
            ->values()
            ->all();

        return view(
            'dpa.affectations.create',
            [
                'equipe' => $equipe,

                'campagnes' => $campagnes,

                'campagnesJs' => $campagnesJs,

                'villagesCampagneJs' => $villagesCampagneJs,

                'villagesPlanifiesJs' => $villagesCampagneJs,

                'prefectureId' => $prefectureId,

                'affectation' => new Affectation(),
            ]
        );
    }

    /**
     * Enregistre une affectation.
     */
    public function store(
        StoreAffectationRequest $request,
        Equipe $equipe
    ): RedirectResponse {

        $user = Auth::user();

        $prefectureId = $this->prefectureIdUtilisateur($user);

        if (!$prefectureId) {
            abort(
                403,
                'Aucune préfecture active n’est associée à votre compte.'
            );
        }

        $this->verifierEquipePrefecture(
            $equipe,
            $prefectureId
        );

        $validated = $request->validated();

        $campagne = CampagneRecensement::query()
            ->whereKey($validated['campagne_id'])
            ->whereIn('statut', [
                'planifiee',
                'active',
            ])
            ->whereHas('deploiements', function ($query) use ($prefectureId) {
                $query->where(
                    'prefecture_id',
                    $prefectureId
                );
            })
            ->with([
                'zones',
            ])
            ->first();

        if (!$campagne) {

            return back()
                ->withInput()
                ->withErrors([
                    'campagne_id' =>
                        'La campagne sélectionnée n’est pas déployée dans votre préfecture.',
                ]);
        }

        $affectationActive = Affectation::query()
            ->where(
                'equipe_id',
                $equipe->idEquipe
            )
            ->where(
                'campagne_id',
                $campagne->idCampagne
            )
            ->where(
                'statut',
                'active'
            )
            ->exists();

        if ($affectationActive) {

            return back()
                ->withInput()
                ->withErrors([
                    'campagne_id' =>
                        'Cette équipe est déjà affectée à cette campagne. Désactivez son affectation avant de la reconduire.',
                ]);
        }

        $villageIds = collect(
            $validated['village_ids']
        )
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        if (empty($villageIds)) {

            return back()
                ->withInput()
                ->withErrors([
                    'village_ids' =>
                        'Veuillez sélectionner au moins un village.',
                ]);
        }

        $villages = Village::query()
            ->whereIn(
                'idVillage',
                $villageIds
            )
            ->with([
                'canton.commune.prefecture',
            ])
            ->get();

        if (
            $villages->count() !==
            count($villageIds)
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'village_ids' =>
                        'Un ou plusieurs villages sélectionnés sont invalides.',
                ]);
        }

        $villagesHorsPrefecture = $villages->filter(
            function ($village) use ($prefectureId) {

                $prefecture = optional(
                    optional($village->canton)->commune
                )->prefecture;

                return
                    !$prefecture ||
                    (int) $prefecture->idPrefecture !== (int) $prefectureId;
            }
        );

        if ($villagesHorsPrefecture->isNotEmpty()) {

            return back()
                ->withInput()
                ->withErrors([
                    'village_ids' =>
                        'Un ou plusieurs villages sélectionnés n’appartiennent pas à votre préfecture.',
                ]);
        }

        $deploiement = CampagneDeploiement::query()
            ->where(
                'campagne_id',
                $campagne->idCampagne
            )
            ->where(
                'prefecture_id',
                $prefectureId
            )
            ->first();

        if (!$deploiement) {

            return back()
                ->withInput()
                ->withErrors([
                    'campagne_id' =>
                        'La campagne n’est pas déployée dans votre préfecture.',
                ]);
        }

        $zones = $campagne->zones;

        if ($campagne->portee !== 'nationale') {

            $villagesNonCouverts = [];

            foreach ($villages as $village) {

                $commune = optional(
                    $village->canton
                )->commune;

                $prefecture = optional(
                    $commune
                )->prefecture;

                if (!$prefecture) {

                    $villagesNonCouverts[] =
                        $village->idVillage;

                    continue;
                }

                if (
                    (int) $prefecture->idPrefecture !==
                    (int) $prefectureId
                ) {

                    $villagesNonCouverts[] =
                        $village->idVillage;

                    continue;
                }

                $couvert = false;

                foreach ($zones as $zone) {

                    if (
                        !empty($zone->region_id) &&
                        !empty($prefecture->region_id) &&
                        (int) $zone->region_id ===
                        (int) $prefecture->region_id
                    ) {
                        $couvert = true;
                        break;
                    }

                    if (
                        !empty($zone->prefecture_id) &&
                        (int) $zone->prefecture_id ===
                        (int) $prefecture->idPrefecture
                    ) {
                        $couvert = true;
                        break;
                    }

                    if (
                        !empty($zone->commune_id) &&
                        $commune &&
                        (int) $zone->commune_id ===
                        (int) $commune->idCommune
                    ) {
                        $couvert = true;
                        break;
                    }

                    if (
                        !empty($zone->canton_id) &&
                        (int) $zone->canton_id ===
                        (int) $village->canton_id
                    ) {
                        $couvert = true;
                        break;
                    }

                    if (
                        !empty($zone->village_id) &&
                        (int) $zone->village_id ===
                        (int) $village->idVillage
                    ) {
                        $couvert = true;
                        break;
                    }
                }

                if (!$couvert) {

                    $villagesNonCouverts[] =
                        $village->idVillage;
                }
            }

            if (!empty($villagesNonCouverts)) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'village_ids' =>
                            'Un ou plusieurs villages sélectionnés ne sont pas couverts par les zones définies dans cette campagne.',
                    ]);
            }
        }

        $membresEquipe = $equipe
            ->membres()
            ->pluck('users.id');

        $agentsOccupes = Affectation::query()
            ->where(
                'campagne_id',
                $campagne->idCampagne
            )
            ->where(
                'statut',
                'active'
            )
            ->whereHas(
                'equipe.membres',
                function ($query) use ($membresEquipe) {

                    $query->whereIn(
                        'users.id',
                        $membresEquipe
                    );
                }
            )
            ->with('equipe')
            ->get();

        if ($agentsOccupes->isNotEmpty()) {

            $equipeOccupee =
                $agentsOccupes->first()->equipe;

            return back()
                ->withInput()
                ->withErrors([
                    'campagne_id' =>
                        "Un ou plusieurs agents de cette équipe sont déjà affectés dans l'équipe « {$equipeOccupee->nom} » pour cette campagne.",
                ]);
        }

        DB::transaction(function () use (
            $validated,
            $villages,
            $equipe
        ) {

            foreach ($villages as $village) {

                Affectation::create([
                    'reference' =>
                        $this->genererReference(),

                    'campagne_id' =>
                        $validated['campagne_id'],

                    'equipe_id' =>
                        $equipe->idEquipe,

                    'village_id' =>
                        $village->idVillage,

                    'dateDebut' =>
                        $validated['dateDebut']
                        ?? now(),

                    'dateFin' =>
                        $validated['dateFin']
                        ?? null,

                    'statut' =>
                        'active',

                    'observations' =>
                        $validated['observations']
                        ?? null,
                ]);
            }
        });

        return redirect()
            ->route(
                'dpa.equipes.index',
                $equipe
            )
            ->with(
                'success',
                "L'équipe {$equipe->nom} a été affectée avec succès à la campagne {$campagne->libelle}."
            );
    }

    public function show(
        Affectation $affectation
    ): View {

        $affectation->load([
            'campagne',
            'equipe',
            'village.canton.commune',
        ]);

        $prefectureId =
            $this->prefectureIdUtilisateur(
                Auth::user()
            );

        if (!$prefectureId) {
            abort(403);
        }

        $villagePrefecture = optional(
            $affectation
                ->village
                ?->canton
                ?->commune
        )->prefecture_id;

        if (
            $villagePrefecture !== null &&
            (int) $villagePrefecture !==
            (int) $prefectureId
        ) {

            abort(
                403,
                'Cette affectation n’appartient pas à votre préfecture.'
            );
        }

        return view(
            'dpa.affectations.show',
            compact('affectation')
        );
    }

    public function destroy(
        Affectation $affectation
    ): RedirectResponse {

        $user = Auth::user();

        $prefectureId =
            $this->prefectureIdUtilisateur($user);

        if (!$prefectureId) {
            abort(403);
        }

        $affectation->load([
            'equipe',
            'campagne',
            'village.canton.commune',
        ]);

        $villagePrefecture = optional(
            $affectation
                ->village
                ?->canton
                ?->commune
        )->prefecture_id;

        if (
            $villagePrefecture !== null &&
            (int) $villagePrefecture !==
            (int) $prefectureId
        ) {

            abort(
                403,
                "Cette affectation n'appartient pas à votre préfecture."
            );
        }

        if (
            $affectation->statut ===
            'annulee'
        ) {

            return back()
                ->with(
                    'info',
                    "Cette affectation est déjà désactivée."
                );
        }

        $affectation->update([
            'statut' => 'annulee',
            'dateFin' => now(),
        ]);

        return redirect()
            ->route(
                'dpa.affectations.index',
                $affectation->equipe
            )
            ->with(
                'success',
                "L'affectation de l'équipe {$affectation->equipe->nom} a été désactivée. L'équipe est maintenant disponible pour une nouvelle affectation."
            );
    }

    private function prefectureIdUtilisateur(
        $user
    ): ?int {

        if (!empty($user->prefecture_id)) {

            return (int) $user->prefecture_id;
        }

        $rattachement =
            RattachementPrefecture::query()
                ->where(
                    'user_id',
                    $user->id
                )
                ->where(
                    'statut',
                    'actif'
                )
                ->whereNull('dateFin')
                ->first();

        return $rattachement
            ? (int) $rattachement->prefecture_id
            : null;
    }

    private function verifierEquipePrefecture(
        Equipe $equipe,
        int $prefectureId
    ): void {

        if (
            isset($equipe->prefecture_id) &&
            $equipe->prefecture_id !== null
        ) {

            if (
                (int) $equipe->prefecture_id !==
                (int) $prefectureId
            ) {

                abort(
                    403,
                    'Cette équipe n’appartient pas à votre préfecture.'
                );
            }

            return;
        }

        if (
            method_exists(
                $equipe,
                'superviseur'
            )
        ) {

            $superviseur =
                $equipe
                    ->load('superviseur')
                    ->superviseur;

            if ($superviseur) {

                $prefectureSuperviseur =
                    $this->prefectureIdUtilisateur(
                        $superviseur
                    );

                if (
                    $prefectureSuperviseur &&
                    (int) $prefectureSuperviseur !==
                    (int) $prefectureId
                ) {

                    abort(
                        403,
                        'Cette équipe n’appartient pas à votre préfecture.'
                    );
                }
            }
        }
    }

    private function genererReference(): string
    {
        do {

            $reference =
                'AFF-' .
                now()->format('Y') .
                '-' .
                strtoupper(
                    Str::random(6)
                );

        } while (
            Affectation::where(
                'reference',
                $reference
            )->exists()
        );

        return $reference;
    }




        public function reactiver(Affectation $affectation): RedirectResponse
    {
        if ($affectation->statut === 'active') {
            return redirect()
                ->route('dpa.affectations.index')
                ->with('error', 'Cette affectation est déjà active.');
        }

        $affectation->update([
            'statut' => 'active',
        ]);

        return redirect()
            ->route('dpa.affectations.index')
            ->with('success', 'L’affectation a été réactivée avec succès.');
    }
}