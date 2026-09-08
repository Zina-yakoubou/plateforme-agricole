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
     * ================================================================
     * LISTE DES AFFECTATIONS
     * ================================================================
     *
     * Une affectation est propre à une préfecture.
     *
     * Le DPA ne voit donc que les affectations de sa préfecture.
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

        /*
        |--------------------------------------------------------------------------
        | AFFECTATIONS DE LA PRÉFECTURE
        |--------------------------------------------------------------------------
        |
        | Une affectation possède :
        |
        |   affectations.village_id
        |
        | La préfecture est donc déterminée par :
        |
        |   Affectation
        |       → Village
        |       → Canton
        |       → Commune
        |       → Préfecture
        |
        | On NE cherche PAS prefecture_id dans affectations
        | ni dans equipes.
        |
        */

        $query = Affectation::query()
            ->with([
                'campagne',
                'equipe.superviseur',
                'equipe.membres',
                'village.canton.commune.prefecture',
            ])
            ->whereHas(
                'village.canton.commune',
                function ($query) use ($prefectureId) {

                    $query->where(
                        'prefecture_id',
                        $prefectureId
                    );
                }
            );

        // ======================================================================
        // FILTRE : STATUT
        // ======================================================================

        if ($request->filled('statut')) {

            $query->where(
                'statut',
                $request->statut
            );
        }

        // ======================================================================
        // FILTRE : CAMPAGNE
        // ======================================================================

        if ($request->filled('campagne_id')) {

            $query->where(
                'campagne_id',
                $request->campagne_id
            );
        }

        // ======================================================================
        // FILTRE : ÉQUIPE
        // ======================================================================

        if ($request->filled('equipe_id')) {

            $query->where(
                'equipe_id',
                $request->equipe_id
            );
        }

        // ======================================================================
        // FILTRE : SUPERVISEUR
        // ======================================================================

        if ($request->filled('superviseur_id')) {

            $query->whereHas(
                'equipe',
                function ($q) use ($request) {

                    $q->where(
                        'superviseur_id',
                        $request->superviseur_id
                    );
                }
            );
        }

        // ======================================================================
        // FILTRE : CANTON
        // ======================================================================

        if ($request->filled('canton_id')) {

            $query->whereHas(
                'village',
                function ($q) use ($request) {

                    $q->where(
                        'canton_id',
                        $request->canton_id
                    );
                }
            );
        }

        // ======================================================================
        // FILTRE : VILLAGE
        // ======================================================================

        if ($request->filled('village_id')) {

            $query->where(
                'village_id',
                $request->village_id
            );
        }

        // ======================================================================
        // RECHERCHE
        // ======================================================================

        if ($request->filled('search')) {

            $search = trim(
                $request->search
            );

            $query->where(function ($q) use ($search) {

                $q->where(
                    'reference',
                    'like',
                    "%{$search}%"
                )

                ->orWhereHas(
                    'campagne',
                    function ($q) use ($search) {

                        $q->where(
                            'libelle',
                            'like',
                            "%{$search}%"
                        );

                        $q->orWhere(
                            'codeCampagne',
                            'like',
                            "%{$search}%"
                        );
                    }
                )

                ->orWhereHas(
                    'equipe',
                    function ($q) use ($search) {

                        $q->where(
                            'reference',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'nom',
                            'like',
                            "%{$search}%"
                        );
                    }
                )

                ->orWhereHas(
                    'village',
                    function ($q) use ($search) {

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
                    }
                );
            });
        }

        // ======================================================================
        // PAGINATION
        // ======================================================================

        $affectations = $query
            ->latest('idAffectation')
            ->paginate(15)
            ->withQueryString();

        // ======================================================================
        // CAMPAGNES DÉPLOYÉES DANS LA PRÉFECTURE
        // ======================================================================

        $campagnes = CampagneRecensement::query()
            ->whereHas(
                'deploiements',
                function ($query) use ($prefectureId) {

                    $query->where(
                        'prefecture_id',
                        $prefectureId
                    );
                }
            )
            ->orderByDesc('dateDebut')
            ->get();

        // ======================================================================
        // ÉQUIPES AYANT DES AFFECTATIONS DANS LA PRÉFECTURE
        // ======================================================================
        //
        // On ne dépend plus de equipes.prefecture_id.
        //
        // Une équipe est considérée comme concernée si elle possède
        // au moins une affectation dans un village de la préfecture.
        //

        $equipes = Equipe::query()
            ->whereHas(
                'affectations.village.canton.commune',
                function ($query) use ($prefectureId) {

                    $query->where(
                        'prefecture_id',
                        $prefectureId
                    );
                }
            )
            ->orderBy('nom')
            ->get();

        // ======================================================================
        // SUPERVISEURS DES ÉQUIPES AFFECTÉES DANS LA PRÉFECTURE
        // ======================================================================

        $superviseurs = User::query()
            ->whereHas(
                'equipesSupervisees',
                function ($query) use ($prefectureId) {

                    $query->whereHas(
                        'affectations.village.canton.commune',
                        function ($query) use ($prefectureId) {

                            $query->where(
                                'prefecture_id',
                                $prefectureId
                            );
                        }
                    );
                }
            )
            ->orderBy('name')
            ->get();

        // ======================================================================
        // CANTONS DE LA PRÉFECTURE
        // ======================================================================

        $cantons = Canton::query()
            ->whereHas(
                'commune',
                function ($query) use ($prefectureId) {

                    $query->where(
                        'prefecture_id',
                        $prefectureId
                    );
                }
            )
            ->orderBy('nom')
            ->get();

        // ======================================================================
        // VILLAGES DE LA PRÉFECTURE
        // ======================================================================

        $villages = Village::query()
            ->whereHas(
                'canton.commune',
                function ($query) use ($prefectureId) {

                    $query->where(
                        'prefecture_id',
                        $prefectureId
                    );
                }
            )
            ->orderBy('nom')
            ->get();

        return view(
            'dpa.affectations.index',
            compact(
                'affectations',
                'campagnes',
                'equipes',
                'superviseurs',
                'cantons',
                'villages'
            )
        );
    }

    /**
     * ================================================================
     * FORMULAIRE DE CRÉATION
     * ================================================================
     */
    public function create(
        Equipe $equipe
    ): View {

        $user = Auth::user();

        $prefectureId =
            $this->prefectureIdUtilisateur($user);

        if (!$prefectureId) {
            abort(
                403,
                'Aucune préfecture active n’est associée à votre compte.'
            );
        }

        /*
         * L'équipe doit appartenir à la préfecture du DPA.
         */
        $this->verifierEquipePrefecture(
            $equipe,
            $prefectureId
        );

        $campagnes = CampagneRecensement::query()
            ->whereIn(
                'statut',
                [
                    'planifiee',
                    'active',
                ]
            )
            ->whereHas(
                'deploiements',
                function ($query) use ($prefectureId) {

                    $query->where(
                        'prefecture_id',
                        $prefectureId
                    );
                }
            )
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
                    'idCampagne' =>
                        $campagne->idCampagne,

                    'codeCampagne' =>
                        $campagne->codeCampagne,

                    'libelle' =>
                        $campagne->libelle,

                    'statut' =>
                        $campagne->statut,

                    'portee' =>
                        $campagne->portee,
                ];
            })
            ->values()
            ->all();

        $villagesCampagneJs = [];

        foreach ($campagnes as $campagne) {

            /*
             * ========================================================
             * CAMPAGNE NATIONALE
             * ========================================================
             */
            if (
                $campagne->portee === 'nationale'
            ) {

                $villages = Village::query()
                    ->whereHas(
                        'canton.commune',
                        function ($query) use ($prefectureId) {

                            $query->where(
                                'prefecture_id',
                                $prefectureId
                            );
                        }
                    )
                    ->with([
                        'canton.commune',
                    ])
                    ->orderBy('nom')
                    ->get();

                foreach ($villages as $village) {

                    $commune =
                        optional(
                            $village->canton
                        )->commune;

                    $villagesCampagneJs[] = [
                        'campagne_id' =>
                            $campagne->idCampagne,

                        'commune_id' =>
                            optional($commune)->idCommune,

                        'commune_nom' =>
                            optional($commune)->nom,

                        'canton_id' =>
                            $village->canton_id,

                        'canton_nom' =>
                            optional($village->canton)->nom,

                        'idVillage' =>
                            $village->idVillage,

                        'nom' =>
                            $village->nom,

                        'code' =>
                            $village->code,
                    ];
                }

                continue;
            }

            /*
             * ========================================================
             * CAMPAGNE À PORTÉE TERRITORIALE
             * ========================================================
             */
            $villages = Village::query()
                ->whereHas(
                    'canton.commune',
                    function ($query) use ($prefectureId) {

                        $query->where(
                            'prefecture_id',
                            $prefectureId
                        );
                    }
                )
                ->with([
                    'canton.commune.prefecture',
                ])
                ->orderBy('nom')
                ->get();

            $zones = $campagne->zones;

            foreach ($villages as $village) {

                $commune =
                    optional(
                        $village->canton
                    )->commune;

                $prefecture =
                    optional(
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

                    /*
                     * Région
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

                    /*
                     * Préfecture
                     */
                    if (
                        !empty($zone->prefecture_id) &&
                        (int) $zone->prefecture_id ===
                        (int) $prefecture->idPrefecture
                    ) {
                        $couvertParCampagne = true;
                        break;
                    }

                    /*
                     * Commune
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

                    /*
                     * Canton
                     */
                    if (
                        !empty($zone->canton_id) &&
                        (int) $zone->canton_id ===
                        (int) $village->canton_id
                    ) {
                        $couvertParCampagne = true;
                        break;
                    }

                    /*
                     * Village
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

                if ($couvertParCampagne) {

                    $villagesCampagneJs[] = [
                        'campagne_id' =>
                            $campagne->idCampagne,

                        'commune_id' =>
                            optional($commune)->idCommune,

                        'commune_nom' =>
                            optional($commune)->nom,

                        'canton_id' =>
                            $village->canton_id,

                        'canton_nom' =>
                            optional($village->canton)->nom,

                        'idVillage' =>
                            $village->idVillage,

                        'nom' =>
                            $village->nom,

                        'code' =>
                            $village->code,
                    ];
                }
            }
        }

        $villagesCampagneJs = collect(
            $villagesCampagneJs
        )
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
                'equipe' =>
                    $equipe,

                'campagnes' =>
                    $campagnes,

                'campagnesJs' =>
                    $campagnesJs,

                'villagesCampagneJs' =>
                    $villagesCampagneJs,

                'villagesPlanifiesJs' =>
                    $villagesCampagneJs,

                'prefectureId' =>
                    $prefectureId,

                'affectation' =>
                    new Affectation(),
            ]
        );
    }


    /**
     * ================================================================
     * ENREGISTRER UNE AFFECTATION
     * ================================================================
     */
    public function store(
            StoreAffectationRequest $request,
            Equipe $equipe
        ): RedirectResponse {

        $user = Auth::user();

        $prefectureId =
            $this->prefectureIdUtilisateur($user);

        if (!$prefectureId) {
            abort(
                403,
                'Aucune préfecture active n’est associée à votre compte.'
            );
        }

        /*
        * L'équipe doit appartenir à la préfecture du DPA.
        */
        $this->verifierEquipePrefecture(
            $equipe,
            $prefectureId
        );

        $validated =
            $request->validated();

        /*
        * ============================================================
        * CAMPAGNE
        * ============================================================
        */
        $campagne = CampagneRecensement::query()
            ->whereKey(
                $validated['campagne_id']
            )
            ->whereIn(
                'statut',
                [
                    'planifiee',
                    'active',
                ]
            )
            ->whereHas(
                'deploiements',
                function ($query) use ($prefectureId) {

                    $query->where(
                        'prefecture_id',
                        $prefectureId
                    );
                }
            )
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

        /*
        * ============================================================
        * ÉQUIPE DÉJÀ AFFECTÉE
        * ============================================================
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

        /*
        * ============================================================
        * VILLAGES
        * ============================================================
        */
        $villageIds = collect(
            $validated['village_ids']
        )
            ->map(
                fn ($id) => (int) $id
            )
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

        /*
        * ============================================================
        * VILLAGES HORS PRÉFECTURE
        * ============================================================
        */
        $villagesHorsPrefecture =
            $villages->filter(
                function ($village) use (
                    $prefectureId
                ) {

                    $prefecture =
                        optional(
                            optional(
                                $village->canton
                            )->commune
                        )->prefecture;

                    return
                        !$prefecture ||
                        (int) $prefecture->idPrefecture !==
                        (int) $prefectureId;
                }
            );

        if (
            $villagesHorsPrefecture->isNotEmpty()
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'village_ids' =>
                        'Un ou plusieurs villages sélectionnés n’appartiennent pas à votre préfecture.',
                ]);
        }

        /*
        * ============================================================
        * DÉPLOIEMENT
        * ============================================================
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

        /*
        * ============================================================
        * VÉRIFICATION DES ZONES DE CAMPAGNE
        * ============================================================
        */
        $zones = $campagne->zones;

        if (
            $campagne->portee !==
            'nationale'
        ) {

            $villagesNonCouverts = [];

            foreach ($villages as $village) {

                $commune =
                    optional(
                        $village->canton
                    )->commune;

                $prefecture =
                    optional(
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

                    /*
                    * Région
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

                    /*
                    * Préfecture
                    */
                    if (
                        !empty($zone->prefecture_id) &&
                        (int) $zone->prefecture_id ===
                        (int) $prefecture->idPrefecture
                    ) {
                        $couvert = true;
                        break;
                    }

                    /*
                    * Commune
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

                    /*
                    * Canton
                    */
                    if (
                        !empty($zone->canton_id) &&
                        (int) $zone->canton_id ===
                        (int) $village->canton_id
                    ) {
                        $couvert = true;
                        break;
                    }

                    /*
                    * Village
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

            if (!empty($villagesNonCouverts)) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'village_ids' =>
                            'Un ou plusieurs villages sélectionnés ne sont pas couverts par les zones définies dans cette campagne.',
                    ]);
            }
        }

        /*
        * ============================================================
        * VILLAGES DÉJÀ AFFECTÉS DANS CETTE CAMPAGNE
        * ============================================================
        *
        * Règle métier :
        * Un village ne peut appartenir qu'à une seule équipe
        * pour une même campagne.
        */
        $villagesDejaAffectes = Affectation::query()
            ->where(
                'campagne_id',
                $campagne->idCampagne
            )
            ->where(
                'statut',
                'active'
            )
            ->whereIn(
                'village_id',
                $villageIds
            )
            ->with([
                'village',
                'equipe',
            ])
            ->get();

        if ($villagesDejaAffectes->isNotEmpty()) {

            $details = $villagesDejaAffectes
                ->map(
                    function (Affectation $affectation) {

                        return sprintf(
                            'le village « %s » est déjà affecté à l’équipe « %s »',
                            $affectation->village?->nom
                                ?? 'Village inconnu',
                            $affectation->equipe?->nom
                                ?? 'Équipe inconnue'
                        );
                    }
                )
                ->implode(', ');

            return back()
                ->withInput()
                ->withErrors([
                    'village_ids' =>
                        "Impossible d'effectuer cette affectation : {$details}.",
                ]);
        }

        /*
        * ============================================================
        * MEMBRES DE L'ÉQUIPE
        * ============================================================
        */
        $membresEquipe =
            $equipe
                ->membres()
                ->pluck('users.id');

        /*
        * ============================================================
        * AGENTS DÉJÀ OCCUPÉS
        * ============================================================
        */
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
                function ($query) use (
                    $membresEquipe
                ) {

                    $query->whereIn(
                        'users.id',
                        $membresEquipe
                    );
                }
            )
            ->with('equipe')
            ->get();

        if (
            $agentsOccupes->isNotEmpty()
        ) {

            $equipeOccupee =
                $agentsOccupes
                    ->first()
                    ->equipe;

            return back()
                ->withInput()
                ->withErrors([
                    'campagne_id' =>
                        "Un ou plusieurs agents de cette équipe sont déjà affectés dans l'équipe « {$equipeOccupee->nom} » pour cette campagne.",
                ]);
        }

        /*
        * ============================================================
        * CRÉATION DES AFFECTATIONS
        * ============================================================
        */
        DB::transaction(
            function () use (
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
            }
        );

        return redirect()
            ->route(
                'dpa.equipes.index'
            )
            ->with(
                'success',
                "L'équipe {$equipe->nom} a été affectée avec succès à la campagne {$campagne->libelle}."
            );
    }


    /**
     * ================================================================
     * AFFICHER UNE AFFECTATION
     * ================================================================
     *
     * Une affectation est propre à une préfecture.
     */
    public function show(
        Affectation $affectation
    ): View {

        $user = Auth::user();

        $prefectureId =
            $this->prefectureIdUtilisateur(
                $user
            );

        if (!$prefectureId) {
            abort(
                403,
                'Aucune préfecture active n’est associée à votre compte.'
            );
        }

        $affectation->load([
            'campagne',
            'equipe.superviseur',
            'equipe.membres',
            'village.canton.commune.prefecture',
        ]);

        /*
         * Vérification par équipe.
         */
        $this->verifierEquipePrefecture(
            $affectation->equipe,
            $prefectureId
        );

        /*
         * Vérification supplémentaire par village.
         */
        $villagePrefecture =
            optional(
                optional(
                    optional(
                        $affectation->village
                    )->canton
                )->commune
            )->prefecture;

        if (
            !$villagePrefecture ||
            (int) $villagePrefecture->idPrefecture !==
            (int) $prefectureId
        ) {

            abort(
                403,
                'Cette affectation n’appartient pas à votre préfecture.'
            );
        }

        return view(
            'dpa.affectations.show',
            compact(
                'affectation'
            )
        );
    }


    /**
     * ================================================================
     * DÉSACTIVER UNE AFFECTATION
     * ================================================================
     */
    public function destroy(
        Affectation $affectation
    ): RedirectResponse {

        $user = Auth::user();

        $prefectureId =
            $this->prefectureIdUtilisateur(
                $user
            );

        if (!$prefectureId) {
            abort(
                403,
                'Aucune préfecture active n’est associée à votre compte.'
            );
        }

        $affectation->load([
            'equipe.superviseur',
            'campagne',
            'village.canton.commune.prefecture',
        ]);

        /*
         * Vérification de l'équipe.
         */
        $this->verifierEquipePrefecture(
            $affectation->equipe,
            $prefectureId
        );

        /*
         * Vérification du village.
         */
        $villagePrefecture =
            optional(
                optional(
                    optional(
                        $affectation->village
                    )->canton
                )->commune
            )->prefecture;

        if (
            !$villagePrefecture ||
            (int) $villagePrefecture->idPrefecture !==
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
                    'Cette affectation est déjà désactivée.'
                );
        }

        $affectation->update([
            'statut' =>
                'annulee',

            'dateFin' =>
                now(),
        ]);

        return redirect()
            ->route(
                'dpa.affectations.index'
            )
            ->with(
                'success',
                "L'affectation de l'équipe {$affectation->equipe->libelle} a été désactivée. L'équipe est maintenant disponible pour une nouvelle affectation."
            );
    }


    /**
     * ================================================================
     * RÉACTIVER UNE AFFECTATION
     * ================================================================
     *
     * IMPORTANT :
     * On vérifie la préfecture avant toute modification.
     */
    public function reactiver(
        Affectation $affectation
    ): RedirectResponse {

        $user = Auth::user();

        $prefectureId =
            $this->prefectureIdUtilisateur(
                $user
            );

        if (!$prefectureId) {
            abort(
                403,
                'Aucune préfecture active n’est associée à votre compte.'
            );
        }

        $affectation->load([
            'equipe.superviseur',
            'campagne',
            'village.canton.commune.prefecture',
        ]);

        /*
         * Vérification de l'équipe.
         */
        $this->verifierEquipePrefecture(
            $affectation->equipe,
            $prefectureId
        );

        /*
         * Vérification du village.
         */
        $villagePrefecture =
            optional(
                optional(
                    optional(
                        $affectation->village
                    )->canton
                )->commune
            )->prefecture;

        if (
            !$villagePrefecture ||
            (int) $villagePrefecture->idPrefecture !==
            (int) $prefectureId
        ) {

            abort(
                403,
                "Cette affectation n'appartient pas à votre préfecture."
            );
        }

        if (
            $affectation->statut ===
            'active'
        ) {

            return redirect()
                ->route(
                    'dpa.affectations.index'
                )
                ->with(
                    'error',
                    'Cette affectation est déjà active.'
                );
        }

        /*
         * Avant de réactiver, on vérifie qu'un agent
         * de l'équipe n'est pas déjà engagé dans une autre
         * affectation active de la même campagne.
         */
        $membresEquipe =
            $affectation
                ->equipe
                ->membres()
                ->pluck('users.id');

        $autreAffectation =
            Affectation::query()
                ->where(
                    'campagne_id',
                    $affectation->campagne_id
                )
                ->where(
                    'statut',
                    'active'
                )
                ->where(
                    'idAffectation',
                    '!=',
                    $affectation->idAffectation
                )
                ->whereHas(
                    'equipe.membres',
                    function ($query) use (
                        $membresEquipe
                    ) {

                        $query->whereIn(
                            'users.id',
                            $membresEquipe
                        );
                    }
                )
                ->with('equipe')
                ->first();

        if ($autreAffectation) {

            $nomEquipe =
                $autreAffectation
                    ->equipe
                    ->libelle;

            return redirect()
                ->route(
                    'dpa.affectations.index'
                )
                ->with(
                    'error',
                    "Impossible de réactiver cette affectation : un ou plusieurs membres de l'équipe sont déjà affectés dans l'équipe « {$nomEquipe} » pour cette campagne."
                );
        }

        $affectation->update([
            'statut' =>
                'active',

            /*
             * Une réactivation signifie que l'affectation
             * est de nouveau en cours.
             */
            'dateFin' =>
                null,
        ]);

        return redirect()
            ->route(
                'dpa.affectations.index'
            )
            ->with(
                'success',
                "L'affectation de l'équipe {$affectation->equipe->libelle} a été réactivée avec succès."
            );
    }


    /**
     * ================================================================
     * RÉCUPÉRER LA PRÉFECTURE DE L'UTILISATEUR
     * ================================================================
     *
     * Priorité :
     *
     * 1. users.prefecture_id
     * 2. rattachement préfectoral actif
     */
    private function prefectureIdUtilisateur(
        $user
    ): ?int {

        if (!$user) {
            return null;
        }

        /*
         * Préférence au rattachement actif si disponible.
         */
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
                ->whereNull(
                    'dateFin'
                )
                ->first();

        if ($rattachement) {

            return (int)
                $rattachement->prefecture_id;
        }

        /*
         * Compatibilité avec users.prefecture_id.
         */
        if (
            !empty(
                $user->prefecture_id
            )
        ) {

            return (int)
                $user->prefecture_id;
        }

        return null;
    }


    /**
     * ================================================================
     * VÉRIFIER L'ÉQUIPE
     * ================================================================
     *
     * Une équipe appartient à une préfecture.
     *
     * Priorité :
     *
     * 1. prefecture_id de l'équipe
     * 2. préfecture du superviseur
     * 3. rattachement du superviseur
     */
    private function verifierEquipePrefecture(
        Equipe $equipe,
        int $prefectureId
    ): void {

        if (!$equipe) {
            abort(
                404,
                'Équipe introuvable.'
            );
        }

        /*
         * Si l'équipe possède son propre prefecture_id.
         */
        if (
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

        /*
         * Sinon, on utilise le superviseur.
         */
        $equipe->loadMissing(
            'superviseur'
        );

        $superviseur =
            $equipe->superviseur;

        if (!$superviseur) {

            abort(
                403,
                'Cette équipe ne possède pas de superviseur permettant de déterminer sa préfecture.'
            );
        }

        $prefectureSuperviseur =
            $this->prefectureIdUtilisateur(
                $superviseur
            );

        if (
            !$prefectureSuperviseur ||
            (int) $prefectureSuperviseur !==
            (int) $prefectureId
        ) {

            abort(
                403,
                'Cette équipe n’appartient pas à votre préfecture.'
            );
        }
    }


    /**
     * ================================================================
     * GÉNÉRER UNE RÉFÉRENCE
     * ================================================================
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



        public function mesAffectations(Request $request): View
    {
        $user = Auth::user();

        $search = trim($request->input('search', ''));

        $affectations = Affectation::with([
            'campagne',
            'equipe.superviseur',
            'village.canton.commune.prefecture',
        ])
        ->whereHas('equipe.membres', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })
        ->when($search !== '', function ($query) use ($search) {
            $query->where(function ($q) use ($search) {

                // Recherche sur la campagne
                $q->whereHas('campagne', function ($campagne) use ($search) {
                    $campagne->where('libelle', 'like', "%{$search}%")
                        ->orWhere('codeCampagne', 'like', "%{$search}%");
                })

                // Recherche sur le village
                ->orWhereHas('village', function ($village) use ($search) {
                    $village->where('nom', 'like', "%{$search}%");
                })

                // Recherche sur le canton
                ->orWhereHas('village.canton', function ($canton) use ($search) {
                    $canton->where('nom', 'like', "%{$search}%");
                })

                // Recherche sur la commune
                ->orWhereHas('village.canton.commune', function ($commune) use ($search) {
                    $commune->where('nom', 'like', "%{$search}%");
                })

                // Recherche sur le statut
                ->orWhere('statut', 'like', "%{$search}%");
            });
        })
        ->orderByDesc('dateDebut')
        ->paginate(10)
        ->withQueryString();

        return view('agent.affectations.index', compact(
            'affectations',
            'search'
        ));
    }


        public function detailAgent(Affectation $affectation): View
    {
        $user = Auth::user();

        // Sécurité : l'agent ne peut consulter que ses propres affectations
        $estMembre = $affectation->equipe()
            ->whereHas('membres', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->exists();

        abort_unless($estMembre, 403);

        $affectation->load([
            'campagne',
            'equipe.superviseur',
            'equipe.membres',
            'village.canton.commune.prefecture',
        ]);

        return view('agent.affectations.show', compact('affectation'));
    }



    /**
 * ================================================================
 * MES ZONES DE SUPERVISION
 * ================================================================
 */
public function mesZonesSupervision(
    Request $request
): View {

    $user = Auth::user();

    /*
     * Seul un superviseur peut accéder à cette page.
     */
    abort_unless(
        $user->isSuperviseur(),
        403,
        'Cette page est réservée aux superviseurs.'
    );

    $search = trim(
        $request->input('search', '')
    );

    $query = Affectation::query()
        ->with([
            'campagne',
            'equipe.superviseur',
            'equipe.membres',
            'village.canton.commune.prefecture',
        ])

        /*
         * Seulement les affectations actives.
         */
        ->where(
            'statut',
            'active'
        )

        /*
         * Seulement les équipes dont
         * l'utilisateur connecté est superviseur.
         */
        ->whereHas(
            'equipe',
            function ($query) use ($user) {

                $query->where(
                    'superviseur_id',
                    $user->id
                );
            }
        );

    /*
     * ============================================================
     * RECHERCHE
     * ============================================================
     */
    if ($search !== '') {

        $query->where(function ($q) use ($search) {

            /*
             * Campagne
             */
            $q->whereHas(
                'campagne',
                function ($campagne) use ($search) {

                    $campagne
                        ->where(
                            'libelle',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'codeCampagne',
                            'like',
                            "%{$search}%"
                        );
                }
            )

            /*
             * Équipe
             */
            ->orWhereHas(
                'equipe',
                function ($equipe) use ($search) {

                    $equipe
                        ->where(
                            'reference',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'libelle',
                            'like',
                            "%{$search}%"
                        );
                }
            )

            /*
             * Village
             */
            ->orWhereHas(
                'village',
                function ($village) use ($search) {

                    $village
                        ->where(
                            'nom',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'code',
                            'like',
                            "%{$search}%"
                        );
                }
            )

            /*
             * Canton
             */
            ->orWhereHas(
                'village.canton',
                function ($canton) use ($search) {

                    $canton->where(
                        'nom',
                        'like',
                        "%{$search}%"
                    );
                }
            )

            /*
             * Commune
             */
            ->orWhereHas(
                'village.canton.commune',
                function ($commune) use ($search) {

                    $commune->where(
                        'nom',
                        'like',
                        "%{$search}%"
                    );
                }
            );
        });
    }

    /*
     * ============================================================
     * PAGINATION
     * ============================================================
     */
    $affectations = $query
        ->orderBy(
            'village_id'
        )
        ->paginate(15)
        ->withQueryString();

    return view(
        'superviseur.zones.index',
        compact(
            'affectations',
            'search'
        )
    );
}


    /**
     * ================================================================
     * DÉTAIL D'UNE ZONE DE SUPERVISION
     * ================================================================
     */
    public function zoneSupervision(
        Affectation $affectation
    ): View {

        $user = Auth::user();

        abort_unless(
            $user->isSuperviseur(),
            403,
            'Cette page est réservée aux superviseurs.'
        );

        /*
        * Vérifier que l'affectation appartient
        * réellement à une équipe supervisée
        * par l'utilisateur connecté.
        */
        $autorise = $affectation
            ->equipe()
            ->where(
                'superviseur_id',
                $user->id
            )
            ->exists();

        abort_unless(
            $autorise,
            403,
            'Vous n’êtes pas autorisé à consulter cette zone.'
        );

        $affectation->load([
            'campagne',
            'equipe.superviseur',
            'equipe.membres',
            'village.canton.commune.prefecture',
        ]);

        return view(
            'superviseur.zones.show',
            compact(
                'affectation'
            )
        );
    }
}