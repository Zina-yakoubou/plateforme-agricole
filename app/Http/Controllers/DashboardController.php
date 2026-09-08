<?php

namespace App\Http\Controllers;

use App\Models\Affectation;
use App\Models\CampagneRecensement;
use App\Models\Commune;
use App\Models\Equipe;
use App\Models\RattachementPrefecture;
use App\Models\User;
use App\Models\Village;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tableau de bord principal.
     *
     * Un seul dashboard pour tous les profils.
     *
     * - Administrateur : vision globale
     * - DPA : vision de sa préfecture
     * - Agent recenseur : vision de ses équipes et affectations
     */
    public function index(): View
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | CAMPAGNE ACTUELLE
        |--------------------------------------------------------------------------
        |
        | Pour Admin et DPA :
        |   priorité à la campagne active.
        |
        | Pour Agent :
        |   la campagne active sera déterminée à partir de ses affectations.
        |
        */

        $campagne = CampagneRecensement::query()
            ->where('statut', 'active')
            ->orderByDesc('dateDebut')
            ->first();

        if (!$campagne) {
            $campagne = CampagneRecensement::query()
                ->where('statut', 'planifiee')
                ->orderByDesc('dateDebut')
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | ADMINISTRATEUR
        |--------------------------------------------------------------------------
        */

        if ($user->isAdmin()) {
            return $this->dashboardAdministrateur($campagne);
        }

        /*
        |--------------------------------------------------------------------------
        | DPA
        |--------------------------------------------------------------------------
        */

        if ($user->isDpa()) {
            return $this->dashboardDpa($user, $campagne);
        }

        /*
        |--------------------------------------------------------------------------
        | AGENT RECENSEUR
        |--------------------------------------------------------------------------
        */

        if ($user->isAgent()) {
            return $this->dashboardAgent($user);
        }

        /*
        |--------------------------------------------------------------------------
        | AUTRES UTILISATEURS
        |--------------------------------------------------------------------------
        */

        return view('dashboard', [
            'typeDashboard' => 'standard',
            'campagne' => $campagne,
        ]);
    }


    /**
     * =========================================================================
     * DASHBOARD ADMINISTRATEUR
     * =========================================================================
     */
    private function dashboardAdministrateur(
        ?CampagneRecensement $campagne
    ): View {

        /*
        |--------------------------------------------------------------------------
        | STATISTIQUES GÉNÉRALES
        |--------------------------------------------------------------------------
        */

        $nombreCampagnes = CampagneRecensement::count();

        $nombreEquipes = Equipe::count();

        $nombreAgents = $this->nombreAgents();

        $nombreVillages = Village::count();


        /*
        |--------------------------------------------------------------------------
        | AFFECTATIONS
        |--------------------------------------------------------------------------
        */

        $nombreAffectations = Affectation::query()
            ->when(
                $campagne,
                fn ($query) =>
                    $query->where(
                        'campagne_id',
                        $campagne->idCampagne
                    )
            )
            ->count();

        $affectationsActives = Affectation::query()
            ->where('statut', 'active')
            ->when(
                $campagne,
                fn ($query) =>
                    $query->where(
                        'campagne_id',
                        $campagne->idCampagne
                    )
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | VILLAGES AFFECTÉS
        |--------------------------------------------------------------------------
        */

        $villagesAffectes = Affectation::query()
            ->when(
                $campagne,
                fn ($query) =>
                    $query->where(
                        'campagne_id',
                        $campagne->idCampagne
                    )
            )
            ->distinct('village_id')
            ->count('village_id');


        /*
        |--------------------------------------------------------------------------
        | PROGRESSION
        |--------------------------------------------------------------------------
        */

        $progression = $nombreVillages > 0
            ? round(
                ($villagesAffectes / $nombreVillages) * 100
            )
            : 0;

        $progression = min($progression, 100);


        /*
        |--------------------------------------------------------------------------
        | SITUATION PAR PRÉFECTURE
        |--------------------------------------------------------------------------
        */

        $prefectures = collect();

        if ($campagne) {

            $prefectures = \App\Models\Prefecture::query()
                ->orderBy('nom')
                ->get()
                ->map(function ($prefecture) use ($campagne) {

                    /*
                    | Les équipes sont comptées à partir de leurs
                    | affectations dans la préfecture.
                    |
                    | On ne dépend donc pas de equipes.prefecture_id.
                    */
                    $equipes = Equipe::query()
                        ->whereHas(
                            'affectations.village.canton.commune',
                            function ($query) use ($prefecture) {
                                $query->where(
                                    'prefecture_id',
                                    $prefecture->idPrefecture
                                );
                            }
                        )
                        ->whereHas(
                            'affectations',
                            function ($query) use ($campagne) {
                                $query->where(
                                    'campagne_id',
                                    $campagne->idCampagne
                                );
                            }
                        )
                        ->count();


                    /*
                    | Nombre total de villages de la préfecture.
                    */
                    $villages = Village::query()
                        ->whereHas(
                            'canton.commune',
                            function ($query) use ($prefecture) {
                                $query->where(
                                    'prefecture_id',
                                    $prefecture->idPrefecture
                                );
                            }
                        )
                        ->count();


                    /*
                    | Villages affectés pendant la campagne.
                    */
                    $villagesAffectes = Affectation::query()
                        ->where(
                            'campagne_id',
                            $campagne->idCampagne
                        )
                        ->whereHas(
                            'village.canton.commune',
                            function ($query) use ($prefecture) {
                                $query->where(
                                    'prefecture_id',
                                    $prefecture->idPrefecture
                                );
                            }
                        )
                        ->distinct('village_id')
                        ->count('village_id');


                    $progression = $villages > 0
                        ? round(
                            ($villagesAffectes / $villages) * 100
                        )
                        : 0;


                    return [
                        'id' => $prefecture->idPrefecture,
                        'nom' => $prefecture->nom,
                        'equipes' => $equipes,
                        'villages' => $villages,
                        'villages_affectes' => $villagesAffectes,
                        'progression' => min($progression, 100),
                    ];
                });
        }


        return view('dashboard', [
            'typeDashboard' => 'admin',

            'campagne' => $campagne,

            'nombreCampagnes' => $nombreCampagnes,
            'nombreEquipes' => $nombreEquipes,
            'nombreAgents' => $nombreAgents,
            'nombreVillages' => $nombreVillages,

            'nombreAffectations' => $nombreAffectations,
            'affectationsActives' => $affectationsActives,

            'villagesAffectes' => $villagesAffectes,
            'progression' => $progression,

            'prefectures' => $prefectures,
        ]);
    }


    /**
     * =========================================================================
     * DASHBOARD DPA
     * =========================================================================
     */
    private function dashboardDpa(
        User $user,
        ?CampagneRecensement $campagne
    ): View {

        /*
        |--------------------------------------------------------------------------
        | PRÉFECTURE DU DPA
        |--------------------------------------------------------------------------
        */

        $prefectureId = $this->prefectureIdUtilisateur($user);

        if (!$prefectureId) {
            abort(
                403,
                'Aucune préfecture active n’est associée à votre compte.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ÉQUIPES
        |--------------------------------------------------------------------------
        |
        | Une équipe est rattachée au territoire par ses affectations.
        | On ne dépend donc pas de equipes.prefecture_id.
        |
        */

        $equipesQuery = Equipe::query()
            ->whereHas(
                'affectations.village.canton.commune',
                function ($query) use ($prefectureId) {
                    $query->where(
                        'prefecture_id',
                        $prefectureId
                    );
                }
            );

        if ($campagne) {
            $equipesQuery->whereHas(
                'affectations',
                function ($query) use ($campagne) {
                    $query->where(
                        'campagne_id',
                        $campagne->idCampagne
                    );
                }
            );
        }

        $nombreEquipes = $equipesQuery->count();


        /*
        |--------------------------------------------------------------------------
        | AGENTS
        |--------------------------------------------------------------------------
        */

        $nombreAgents = $this->nombreAgents(
            $prefectureId
        );


        /*
        |--------------------------------------------------------------------------
        | SUPERVISEURS
        |--------------------------------------------------------------------------
        */

        $nombreSuperviseurs = $this->nombreSuperviseurs(
            $prefectureId
        );


        /*
        |--------------------------------------------------------------------------
        | VILLAGES
        |--------------------------------------------------------------------------
        */

        $nombreVillages = Village::query()
            ->whereHas(
                'canton.commune',
                function ($query) use ($prefectureId) {
                    $query->where(
                        'prefecture_id',
                        $prefectureId
                    );
                }
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | AFFECTATIONS
        |--------------------------------------------------------------------------
        */

        $affectationsQuery = Affectation::query()
            ->whereHas(
                'village.canton.commune',
                function ($query) use ($prefectureId) {
                    $query->where(
                        'prefecture_id',
                        $prefectureId
                    );
                }
            )
            ->when(
                $campagne,
                fn ($query) =>
                    $query->where(
                        'campagne_id',
                        $campagne->idCampagne
                    )
            );


        $nombreAffectations = (clone $affectationsQuery)
            ->count();


        $affectationsActives = (clone $affectationsQuery)
            ->where('statut', 'active')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | VILLAGES AFFECTÉS
        |--------------------------------------------------------------------------
        */

        $villagesAffectes = (clone $affectationsQuery)
            ->distinct('village_id')
            ->count('village_id');


        /*
        |--------------------------------------------------------------------------
        | VILLAGES NON AFFECTÉS
        |--------------------------------------------------------------------------
        */

        $villagesNonAffectes = max(
            0,
            $nombreVillages - $villagesAffectes
        );


        /*
        |--------------------------------------------------------------------------
        | PROGRESSION
        |--------------------------------------------------------------------------
        */

        $progression = $nombreVillages > 0
            ? round(
                ($villagesAffectes / $nombreVillages) * 100
            )
            : 0;

        $progression = min($progression, 100);


        /*
        |--------------------------------------------------------------------------
        | PROGRESSION PAR COMMUNE
        |--------------------------------------------------------------------------
        */

        $communes = Commune::query()
            ->where(
                'prefecture_id',
                $prefectureId
            )
            ->orderBy('nom')
            ->get()
            ->map(function ($commune) use ($campagne) {

                $villages = Village::query()
                    ->whereHas(
                        'canton',
                        function ($query) use ($commune) {
                            $query->where(
                                'commune_id',
                                $commune->idCommune
                            );
                        }
                    )
                    ->count();


                $villagesAffectes = Affectation::query()
                    ->when(
                        $campagne,
                        fn ($query) =>
                            $query->where(
                                'campagne_id',
                                $campagne->idCampagne
                            )
                    )
                    ->whereHas(
                        'village.canton',
                        function ($query) use ($commune) {
                            $query->where(
                                'commune_id',
                                $commune->idCommune
                            );
                        }
                    )
                    ->distinct('village_id')
                    ->count('village_id');


                $progression = $villages > 0
                    ? round(
                        ($villagesAffectes / $villages) * 100
                    )
                    : 0;


                return [
                    'nom' => $commune->nom,
                    'villages' => $villages,
                    'villages_affectes' => $villagesAffectes,
                    'progression' => min($progression, 100),
                ];
            });


        return view('dashboard', [
            'typeDashboard' => 'dpa',

            'campagne' => $campagne,

            'prefecture' => $user->prefectureActuelle,

            'nombreEquipes' => $nombreEquipes,
            'nombreAgents' => $nombreAgents,
            'nombreSuperviseurs' => $nombreSuperviseurs,
            'nombreVillages' => $nombreVillages,

            'nombreAffectations' => $nombreAffectations,
            'affectationsActives' => $affectationsActives,

            'villagesAffectes' => $villagesAffectes,
            'villagesNonAffectes' => $villagesNonAffectes,

            'progression' => $progression,

            'communes' => $communes,
        ]);
    }


    /**
     * =========================================================================
     * DASHBOARD AGENT RECENSEUR
     * =========================================================================
     *
     * IMPORTANT :
     *
     * Une affectation appartient à une ÉQUIPE.
     *
     * L'agent est relié à l'équipe via :
     *
     *       users
     *          ↓
     *       equipe_membres
     *          ↓
     *       equipes
     *          ↓
     *       affectations
     *          ↓
     *       villages
     *
     * L'agent peut donc avoir :
     * - plusieurs équipes dans le temps ;
     * - plusieurs affectations ;
     * - participé à plusieurs campagnes.
     */
    private function dashboardAgent(User $user): View
    {
        /*
        |--------------------------------------------------------------------------
        | MES AFFECTATIONS
        |--------------------------------------------------------------------------
        |
        | On récupère toutes les affectations des équipes auxquelles
        | l'agent appartient.
        |
        */

        $mesAffectations = Affectation::query()
            ->with([
                'campagne',
                'equipe.superviseur',
                'equipe.membres',
                'village.canton.commune.prefecture',
            ])
            ->whereHas(
                'equipe.membres',
                function ($query) use ($user) {
                    $query->where(
                        'users.id',
                        $user->id
                    );
                }
            )
            ->latest('idAffectation')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | MES CAMPAGNES
        |--------------------------------------------------------------------------
        |
        | On ne suppose surtout pas que l'agent n'a participé qu'à
        | une seule campagne.
        |
        | Les campagnes sont déduites de ses affectations.
        |
        */

        $mesCampagnes = $mesAffectations
            ->pluck('campagne')
            ->filter()
            ->unique('idCampagne')
            ->sortByDesc('dateDebut')
            ->values();


        /*
        |--------------------------------------------------------------------------
        | CAMPAGNE ACTUELLE DE L'AGENT
        |--------------------------------------------------------------------------
        |
        | Priorité :
        |
        | 1. campagne active dans laquelle l'agent a une affectation ;
        | 2. à défaut, dernière campagne planifiée à laquelle il participe ;
        | 3. à défaut, dernière campagne connue de son historique.
        |
        */

        $campagne = $mesCampagnes
            ->firstWhere('statut', 'active');

        if (!$campagne) {
            $campagne = $mesCampagnes
                ->firstWhere('statut', 'planifiee');
        }

        if (!$campagne) {
            $campagne = $mesCampagnes->first();
        }


        /*
        |--------------------------------------------------------------------------
        | AFFECTATIONS DE LA CAMPAGNE ACTUELLE
        |--------------------------------------------------------------------------
        */

        $affectationsCampagne = $campagne
            ? $mesAffectations
                ->where(
                    'campagne_id',
                    $campagne->idCampagne
                )
                ->values()
            : collect();


        /*
        |--------------------------------------------------------------------------
        | MES ÉQUIPES
        |--------------------------------------------------------------------------
        |
        | Un agent peut appartenir à plusieurs équipes.
        |
        */

        $mesEquipes = $mesAffectations
            ->pluck('equipe')
            ->filter()
            ->unique('idEquipe')
            ->values();


        /*
        |--------------------------------------------------------------------------
        | ÉQUIPE ACTUELLE
        |--------------------------------------------------------------------------
        |
        | Pour l'affichage principal, on prend l'équipe qui possède
        | une affectation dans la campagne actuelle.
        |
        */

        $equipe = $affectationsCampagne
            ->pluck('equipe')
            ->filter()
            ->unique('idEquipe')
            ->first();


        /*
        |--------------------------------------------------------------------------
        | STATISTIQUES
        |--------------------------------------------------------------------------
        */

        $nombreAffectations = $affectationsCampagne->count();

        $affectationsActives = $affectationsCampagne
            ->where('statut', 'active')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | VILLAGES
        |--------------------------------------------------------------------------
        */

        $nombreVillages = $affectationsCampagne
            ->pluck('village_id')
            ->filter()
            ->unique()
            ->count();


        /*
        |--------------------------------------------------------------------------
        | PROGRESSION
        |--------------------------------------------------------------------------
        |
        | Pour l'instant, comme pour le DPA :
        | la progression représente les villages couverts par
        | les affectations.
        |
        */

        $progression = 0;

        if ($nombreVillages > 0) {

            /*
            | Ici on garde une base simple.
            | Lorsque le module de collecte sera disponible,
            | cette valeur sera remplacée par la vraie progression
            | des villages recensés.
            */
            $progression = $affectationsActives > 0
                ? round(
                    ($affectationsActives / $nombreAffectations) * 100
                )
                : 0;

            $progression = min($progression, 100);
        }


        /*
        |--------------------------------------------------------------------------
        | MES ZONES DE TRAVAIL
        |--------------------------------------------------------------------------
        |
        | On utilise les affectations de la campagne actuelle.
        |
        */

        $mesZones = $affectationsCampagne
            ->map(function ($affectation) {

                $village = $affectation->village;

                if (!$village) {
                    return null;
                }

                return [
                    'id' => $village->idVillage,

                    'village' => $village->nom,

                    'canton' => $village->canton?->nom,

                    'commune' => $village->canton?->commune?->nom,

                    'prefecture' =>
                        $village->canton?->commune?->prefecture?->nom,

                    'statut' => $affectation->statut,

                    'reference' => $affectation->reference,

                    'dateDebut' => $affectation->dateDebut,

                    'dateFin' => $affectation->dateFin,

                    'equipe' => $affectation->equipe?->nom,
                ];
            })
            ->filter()
            ->unique('id')
            ->values();


        /*
        |--------------------------------------------------------------------------
        | RETOUR VUE
        |--------------------------------------------------------------------------
        */

        return view('dashboard', [

            'typeDashboard' => 'agent',

            /*
            | Campagne actuellement suivie par l'agent.
            */
            'campagne' => $campagne,

            /*
            | Équipe principale dans la campagne actuelle.
            */
            'equipe' => $equipe,

            /*
            | Toutes les équipes connues de l'agent.
            */
            'mesEquipes' => $mesEquipes,

            /*
            | Affectations de la campagne actuelle.
            */
            'mesAffectations' => $affectationsCampagne,

            /*
            | Historique des campagnes.
            */
            'mesCampagnes' => $mesCampagnes,

            /*
            | Zones/villages de travail.
            */
            'mesZones' => $mesZones,

            /*
            | Statistiques.
            */
            'nombreAffectations' => $nombreAffectations,

            'affectationsActives' => $affectationsActives,

            'nombreVillages' => $nombreVillages,

            'progression' => $progression,
        ]);
    }


    /**
     * =========================================================================
     * NOMBRE D'AGENTS
     * =========================================================================
     *
     * Le rôle est recherché par son nom.
     * Aucun ID de rôle n'est utilisé.
     */
    private function nombreAgents(
        ?int $prefectureId = null
    ): int {

        $query = User::query()
            ->whereHas(
                'role',
                function ($query) {
                    $query->where(
                        'nom',
                        'Agent recenseur'
                    );
                }
            );

        /*
        |--------------------------------------------------------------------------
        | Si une préfecture est précisée
        |--------------------------------------------------------------------------
        */

        if ($prefectureId !== null) {

            /*
            | On utilise le rattachement préfectoral.
            |
            | Cela évite de dépendre de users.prefecture_id.
            */
            $query->whereHas(
                'rattachementsPrefecture',
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
            );
        }

        return $query->count();
    }


    /**
     * =========================================================================
     * NOMBRE DE SUPERVISEURS
     * =========================================================================
     */
    private function nombreSuperviseurs(
        int $prefectureId
    ): int {

        return User::query()
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
                'rattachementsPrefecture',
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
            ->count();
    }


    /**
     * =========================================================================
     * PRÉFECTURE DE L'UTILISATEUR
     * =========================================================================
     */
    private function prefectureIdUtilisateur(
        User $user
    ): ?int {

        /*
        | Priorité au rattachement actif.
        */
        $rattachement = RattachementPrefecture::query()
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
            return (int) $rattachement->prefecture_id;
        }

        /*
        | Compatibilité si users.prefecture_id existe.
        */
        if (!empty($user->prefecture_id)) {
            return (int) $user->prefecture_id;
        }

        return null;
    }
}