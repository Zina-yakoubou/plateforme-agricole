<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAffectationRequest;
use App\Models\Affectation;
use App\Models\CampagneRecensement;
use App\Models\CampagneDeploiement;
use App\Models\Canton;
use App\Models\Equipe;
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
    //             'equipe',
    //             'village.canton.commune',
    //         ])
    //         ->whereHas('equipe', function ($query) use ($prefectureId) {
    //             $query->whereHas('superviseur', function ($q) use ($prefectureId) {
    //                 $q->whereHas('rattachementPrefecture', function ($r) use ($prefectureId) {
    //                     $r->where('prefecture_id', $prefectureId)
    //                         ->where('statut', 'actif')
    //                         ->whereNull('dateFin');
    //                 });
    //             });
    //         })
    //         ->when(
    //             $request->filled('campagne_id'),
    //             fn ($query) =>
    //                 $query->where(
    //                     'campagne_id',
    //                     $request->campagne_id
    //                 )
    //         )
    //         ->when(
    //             $request->filled('statut'),
    //             fn ($query) =>
    //                 $query->where(
    //                     'statut',
    //                     $request->statut
    //                 )
    //         )
    //         ->latest('idAffectation')
    //         ->paginate(15)
    //         ->withQueryString();

    //     return view(
    //         'dpa.affectations.index',
    //         compact('affectations')
    //     );
    // }




    /**
 * Liste des affectations de la préfecture du DPA.
 */
public function index(Request $request): View
{
    $user = Auth::user();

    $prefectureId = $this->prefectureIdUtilisateur($user);

    if (!$prefectureId) {
        abort(
            403,
            'Aucune préfecture active n’est associée à votre compte.'
        );
    }

    $affectations = Affectation::query()
        ->with([
            'campagne',
            'equipe.superviseur',
            'equipe.membres',
            'village.canton.commune',
        ])

        /**
         * Les affectations sont filtrées par les équipes
         * appartenant à la préfecture du DPA.
         */
        ->whereHas('equipe', function ($query) use ($prefectureId) {

            $query->where(function ($q) use ($prefectureId) {

                /**
                 * Cas où l'équipe possède directement
                 * prefecture_id.
                 */
                $q->where(
                    'prefecture_id',
                    $prefectureId
                )

                /**
                 * Si prefecture_id n'est pas renseigné,
                 * on passe par le superviseur.
                 */
                ->orWhereHas(
                    'superviseur',
                    function ($superviseur) use ($prefectureId) {

                        $superviseur->where(
                            'prefecture_id',
                            $prefectureId
                        );
                    }
                );
            });
        })

        /**
         * Filtre campagne.
         */
        ->when(
            $request->filled('campagne_id'),
            function ($query) use ($request) {

                $query->where(
                    'campagne_id',
                    $request->campagne_id
                );
            }
        )

        /**
         * Filtre statut.
         */
        ->when(
            $request->filled('statut'),
            function ($query) use ($request) {

                $query->where(
                    'statut',
                    $request->statut
                );
            }
        )

        ->latest('idAffectation')

        ->paginate(15)

        ->withQueryString();

    return view(
        'dpa.affectations.index',
        compact('affectations')
    );
}

    /**
     * Formulaire de création d'une affectation.
     *
     * Les villages disponibles sont désormais déterminés
     * par les zones définies dans la campagne.
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

        /**
         * Campagnes disponibles dans la préfecture du DPA.
         *
         * Une campagne doit être :
         * - planifiée ou active
         * - déployée dans la préfecture
         */
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

                /**
                 * Les zones de la campagne sont maintenant
                 * la source de vérité territoriale.
                 */
                'zones.region',
                'zones.prefecture',
                'zones.commune',
                'zones.canton',
                'zones.village',
            ])
            ->orderByDesc('dateDebut')
            ->get();

        /**
         * Cantons de la préfecture du DPA.
         *
         * On conserve cette liste pour le formulaire.
         */
        $cantons = Canton::query()
            ->whereHas('commune', function ($query) use ($prefectureId) {
                $query->where(
                    'prefecture_id',
                    $prefectureId
                );
            })
            ->with('commune')
            ->orderBy('nom')
            ->get();

        /**
         * Préparation des campagnes pour AlpineJS.
         */
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

        /**
         * Préparation des cantons pour AlpineJS.
         */
       $cantonsJs = $cantons
        ->map(function ($canton) {

            return [
                'idCanton' => $canton->idCanton,
                'nom' => $canton->nom,

                'commune_id' =>
                    $canton->commune_id ?? null,

                'commune_nom' =>
                    optional($canton->commune)->nom,
            ];
        })
        ->values()
        ->all();

        /**
         * Préparation des villages couverts par les campagnes.
         *
         * IMPORTANT :
         *
         * On ne récupère plus les villages depuis
         * PlanificationTerritoire.
         *
         * Les zones de la campagne sont désormais utilisées :
         *
         * - région       => tous les villages de la région
         * - préfecture   => tous les villages de la préfecture
         * - commune      => tous les villages de la commune
         * - canton       => tous les villages du canton
         * - village      => uniquement ce village
         *
         * Une campagne nationale couvre tous les villages.
         */
        $villagesCampagneJs = [];

        foreach ($campagnes as $campagne) {

            /**
             * Campagne nationale :
             * tous les villages de la préfecture sont disponibles.
             */
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

                    $villagesCampagneJs[] = [
                        'campagne_id' => $campagne->idCampagne,
                        'canton_id' => $village->canton_id,
                        'idVillage' => $village->idVillage,
                        'nom' => $village->nom,
                        'code' => $village->code,
                    ];
                }

                continue;
            }

            /**
             * Pour les campagnes régionale et préfectorale,
             * on récupère les villages de la préfecture du DPA.
             */
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

            /**
             * Zones de la campagne.
             */
            $zones = $campagne->zones;

            foreach ($villages as $village) {

                $commune = optional(
                    $village->canton
                )->commune;

                $prefecture = optional(
                    $commune
                )->prefecture;

                /**
                 * Sécurité :
                 * le village doit appartenir à la préfecture
                 * du DPA.
                 */
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

                    /**
                     * Zone régionale.
                     *
                     * Si la région de la campagne correspond
                     * à la région de la préfecture du village,
                     * le village est couvert.
                     */
                    if (
                        !empty($zone->region_id) &&
                        !empty($prefecture->region_id) &&
                        (int) $zone->region_id ===
                        (int) $prefecture->region_id
                    ) {
                        $couvertParCampagne = true;
                        break;
                    }

                    /**
                     * Zone préfecture.
                     */
                    if (
                        !empty($zone->prefecture_id) &&
                        (int) $zone->prefecture_id ===
                        (int) $prefecture->idPrefecture
                    ) {
                        $couvertParCampagne = true;
                        break;
                    }

                    /**
                     * Zone commune.
                     */
                    if (
                        !empty($zone->commune_id) &&
                        $commune &&
                        (int) $zone->commune_id ===
                        (int) $commune->idCommune
                    ) {
                        $couvertParCampagne = true;
                        break;
                    }

                    /**
                     * Zone canton.
                     */
                    if (
                        !empty($zone->canton_id) &&
                        (int) $zone->canton_id ===
                        (int) $village->canton_id
                    ) {
                        $couvertParCampagne = true;
                        break;
                    }

                    /**
                     * Zone village.
                     */
                    if (
                        !empty($zone->village_id) &&
                        (int) $zone->village_id ===
                        (int) $village->idVillage
                    ) {
                        $couvertParCampagne = true;
                        break;
                    }
                }

                /**
                 * On ne transmet au formulaire que les villages
                 * réellement couverts par la campagne.
                 */
                if ($couvertParCampagne) {

                    $villagesCampagneJs[] = [
                        'campagne_id' => $campagne->idCampagne,
                        'canton_id' => $village->canton_id,
                        'idVillage' => $village->idVillage,
                        'nom' => $village->nom,
                        'code' => $village->code,
                    ];
                }
            }
        }

        /**
         * Suppression des doublons éventuels.
         */
        $villagesCampagneJs = collect($villagesCampagneJs)
            ->unique(function ($village) {
                return
                    $village['campagne_id'] .
                    '-' .
                    $village['canton_id'] .
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

                'cantons' => $cantons,

                'campagnesJs' => $campagnesJs,

                'cantonsJs' => $cantonsJs,

                /**
                 * Nouveau nom correspondant à la logique :
                 * villages couverts par la campagne.
                 */
                'villagesCampagneJs' => $villagesCampagneJs,

                /**
                 * Conservé également pour éviter de casser
                 * immédiatement une vue qui utilise encore
                 * l'ancien nom.
                 */
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

        /**
         * Vérification de l'équipe.
         */
        $this->verifierEquipePrefecture(
            $equipe,
            $prefectureId
        );

        $validated = $request->validated();

        /**
         * Récupération de la campagne.
         *
         * La campagne doit :
         * - exister
         * - être planifiée ou active
         * - être déployée dans la préfecture
         */
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

        /**
         * Une équipe ne peut avoir qu'une affectation active
         * pour une même campagne.
         */
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

        /**
         * Vérification du canton.
         */
        $canton = Canton::query()
            ->whereKey($validated['canton_id'])
            ->whereHas('commune', function ($query) use ($prefectureId) {
                $query->where(
                    'prefecture_id',
                    $prefectureId
                );
            })
            ->with('commune.prefecture')
            ->first();

        if (!$canton) {

            return back()
                ->withInput()
                ->withErrors([
                    'canton_id' =>
                        'Le canton sélectionné n’appartient pas à votre préfecture.',
                ]);
        }

        /**
         * Villages sélectionnés.
         */
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

        /**
         * Vérification :
         * les villages appartiennent bien au canton choisi.
         */
        $villages = Village::query()
            ->whereIn(
                'idVillage',
                $villageIds
            )
            ->where(
                'canton_id',
                $canton->idCanton
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
                        'Un ou plusieurs villages ne correspondent pas au canton sélectionné.',
                ]);
        }

        /**
         * Vérification du déploiement.
         */
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

        /**
         * ==========================================================
         * VÉRIFICATION DES ZONES DE LA CAMPAGNE
         * ==========================================================
         *
         * C'est ici que se trouve la correction principale.
         *
         * On ne regarde PLUS :
         *
         * PlanificationPrefectorale
         * PlanificationTerritoire
         *
         * La campagne est la source de vérité.
         */

        $zones = $campagne->zones;

        /**
         * Campagne nationale :
         * tous les villages sont autorisés.
         */
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

                /**
                 * Le village doit appartenir
                 * à la préfecture du DPA.
                 */
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

                    /**
                     * Région.
                     */
                    if (
                        !empty($zone->region_id) &&
                        !empty($prefecture->region_id) &&
                        (int) $zone->region_id ===
                        (int) $prefecture->region_id
                    ) {
                        $couvert = true;
                        break;
                    }

                    /**
                     * Préfecture.
                     */
                    if (
                        !empty($zone->prefecture_id) &&
                        (int) $zone->prefecture_id ===
                        (int) $prefecture->idPrefecture
                    ) {
                        $couvert = true;
                        break;
                    }

                    /**
                     * Commune.
                     */
                    if (
                        !empty($zone->commune_id) &&
                        $commune &&
                        (int) $zone->commune_id ===
                        (int) $commune->idCommune
                    ) {
                        $couvert = true;
                        break;
                    }

                    /**
                     * Canton.
                     */
                    if (
                        !empty($zone->canton_id) &&
                        (int) $zone->canton_id ===
                        (int) $village->canton_id
                    ) {
                        $couvert = true;
                        break;
                    }

                    /**
                     * Village.
                     */
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

            /**
             * Au moins un village n'est pas dans
             * les zones de la campagne.
             */
            if (!empty($villagesNonCouverts)) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'village_ids' =>
                            'Un ou plusieurs villages sélectionnés ne sont pas couverts par les zones définies dans cette campagne.',
                    ]);
            }
        }

        /**
         * ==========================================================
         * VÉRIFICATION DES AGENTS DE L'ÉQUIPE
         * ==========================================================
         */

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

        /**
         * ==========================================================
         * CRÉATION DES AFFECTATIONS
         * ==========================================================
         *
         * Une affectation est créée pour chaque village.
         */
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

                    /**
                     * Les dates restent compatibles
                     * avec le fait qu'elles peuvent être nullable.
                     */
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
                'dpa.equipes.show',
                $equipe
            )
            ->with(
                'success',
                "L'équipe {$equipe->nom} a été affectée avec succès à la campagne {$campagne->libelle}."
            );
    }

    /**
     * Affichage d'une affectation.
     */
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

    /**
     * Désactivation d'une affectation.
     *
     * On ne supprime pas l'affectation.
     */
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
                'dpa.equipes.show',
                $affectation->equipe
            )
            ->with(
                'success',
                "L'affectation de l'équipe {$affectation->equipe->nom} a été désactivée. L'équipe est maintenant disponible pour une nouvelle affectation."
            );
    }

    /**
     * Récupère la préfecture active de l'utilisateur.
     */
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

    /**
     * Vérifie que l'équipe appartient
     * à la préfecture du DPA.
     */
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

    /**
     * Génère une référence unique d'affectation.
     */
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
}