<?php

namespace App\Http\Controllers;

use App\Models\Affectation;
use App\Models\CampagneDeploiement;
use App\Models\CampagneRecensement;
use App\Models\Commune;
use App\Models\Equipe;
use App\Models\Prefecture;
use App\Models\RattachementPrefecture;
use App\Models\User;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class StatistiqueController extends Controller
{
    /**
     * =========================================================================
     * PAGE PRINCIPALE DES STATISTIQUES
     * =========================================================================
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | CAMPAGNES
        |--------------------------------------------------------------------------
        */

        $campagnes = CampagneRecensement::query()
            ->orderByDesc('dateDebut')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | ADMINISTRATEUR
        |--------------------------------------------------------------------------
        */

        if ($user->isAdmin()) {

            $campagneId = $request->input('campagne_id');

            if ($campagneId) {
                $campagne = $campagnes->firstWhere(
                    'idCampagne',
                    (int) $campagneId
                );
            } else {
                $campagne = $campagnes->firstWhere(
                    'statut',
                    'active'
                );

                if (!$campagne) {
                    $campagne = $campagnes->first();
                }
            }

            return $this->statistiquesAdministrateur(
                $campagne,
                $campagnes
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DPA
        |--------------------------------------------------------------------------
        */

        if ($user->isDpa()) {

            $prefectureId = $this->prefectureIdUtilisateur($user);

            if (!$prefectureId) {
                abort(
                    403,
                    'Aucune préfecture active n’est associée à votre compte.'
                );
            }

            /*
            | Le DPA ne doit recevoir que les campagnes concernant
            | sa préfecture.
            */
            $campagnes = $this->campagnesPourPrefecture(
                $prefectureId
            );

            $campagneId = $request->input('campagne_id');

            if ($campagneId) {

                $campagne = $campagnes->firstWhere(
                    'idCampagne',
                    (int) $campagneId
                );

            } else {

                $campagne = $campagnes->firstWhere(
                    'statut',
                    'active'
                );

                if (!$campagne) {
                    $campagne = $campagnes->first();
                }
            }

            return $this->statistiquesDpa(
                $user,
                $prefectureId,
                $campagne,
                $campagnes
            );
        }

        /*
        |--------------------------------------------------------------------------
        | AUTRES RÔLES
        |--------------------------------------------------------------------------
        */

        abort(403, 'Vous n’êtes pas autorisé à consulter les statistiques.');
    }


    /**
     * =========================================================================
     * STATISTIQUES ADMINISTRATEUR
     * =========================================================================
     */
    private function statistiquesAdministrateur(
        $campagne,
        $campagnes
    ): View {

        /*
        |--------------------------------------------------------------------------
        | STATISTIQUES GÉNÉRALES
        |--------------------------------------------------------------------------
        */

        $nombrePrefectures = Prefecture::count();

        $nombreEquipes = Equipe::count();

        $nombreAgents = $this->nombreAgents();

        $nombreVillages = Village::count();


        /*
        |--------------------------------------------------------------------------
        | AFFECTATIONS DE LA CAMPAGNE
        |--------------------------------------------------------------------------
        */

        $affectations = Affectation::query()
            ->when(
                $campagne,
                function ($query) use ($campagne) {
                    $query->where(
                        'campagne_id',
                        $campagne->idCampagne
                    );
                }
            );


        $nombreAffectations = (clone $affectations)->count();

        $affectationsActives = (clone $affectations)
            ->where('statut', 'active')
            ->count();

        $affectationsAnnulees = (clone $affectations)
            ->where('statut', 'annulee')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | VILLAGES AFFECTÉS
        |--------------------------------------------------------------------------
        */

        $villagesAffectes = (clone $affectations)
            ->select('village_id')
            ->distinct()
            ->count();


        /*
        |--------------------------------------------------------------------------
        | PROGRESSION GLOBALE
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
        | STATISTIQUES PAR PRÉFECTURE
        |--------------------------------------------------------------------------
        */

        $prefectures = Prefecture::query()
            ->orderBy('nom')
            ->get()
            ->map(function ($prefecture) use ($campagne) {

                /*
                |------------------------------------------------------------------
                | ÉQUIPES
                |------------------------------------------------------------------
                */

                if ($this->colonneEquipePrefectureExiste()) {

                    $equipes = Equipe::query()
                        ->where(
                            'prefecture_id',
                            $prefecture->idPrefecture
                        )
                        ->count();

                } else {

                    $equipes = Equipe::query()
                        ->whereHas(
                            'superviseur',
                            function ($query) use ($prefecture) {

                                $query->where(
                                    'prefecture_id',
                                    $prefecture->idPrefecture
                                );
                            }
                        )
                        ->count();
                }


                /*
                |------------------------------------------------------------------
                | VILLAGES
                |------------------------------------------------------------------
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
                |------------------------------------------------------------------
                | VILLAGES AFFECTÉS
                |------------------------------------------------------------------
                */

                $villagesAffectes = Affectation::query()
                    ->when(
                        $campagne,
                        function ($query) use ($campagne) {

                            $query->where(
                                'campagne_id',
                                $campagne->idCampagne
                            );
                        }
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
                    ->select('village_id')
                    ->distinct()
                    ->count();


                /*
                |------------------------------------------------------------------
                | AFFECTATIONS
                |------------------------------------------------------------------
                */

                $affectations = Affectation::query()
                    ->when(
                        $campagne,
                        function ($query) use ($campagne) {

                            $query->where(
                                'campagne_id',
                                $campagne->idCampagne
                            );
                        }
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
                    ->count();


                /*
                |------------------------------------------------------------------
                | PROGRESSION
                |------------------------------------------------------------------
                */

                $progression = $villages > 0
                    ? round(
                        ($villagesAffectes / $villages) * 100
                    )
                    : 0;


                return [
                    'nom' => $prefecture->nom,
                    'equipes' => $equipes,
                    'villages' => $villages,
                    'villages_affectes' => $villagesAffectes,
                    'villages_non_affectes' =>
                        max(0, $villages - $villagesAffectes),
                    'affectations' => $affectations,
                    'progression' => min($progression, 100),
                ];
            });


        /*
        |--------------------------------------------------------------------------
        | HISTORIQUE DES CAMPAGNES
        |--------------------------------------------------------------------------
        */

        $historique = $campagnes->map(
            function ($campagneHistorique) {

                $villages = Village::count();

                $villagesAffectes = Affectation::query()
                    ->where(
                        'campagne_id',
                        $campagneHistorique->idCampagne
                    )
                    ->select('village_id')
                    ->distinct()
                    ->count();


                $progression = $villages > 0
                    ? round(
                        ($villagesAffectes / $villages) * 100
                    )
                    : 0;


                return [
                    'libelle' => $campagneHistorique->libelle,
                    'statut' => $campagneHistorique->statut,
                    'dateDebut' => $campagneHistorique->dateDebut,
                    'dateFin' => $campagneHistorique->dateFin,
                    'villages_affectes' => $villagesAffectes,
                    'progression' => min($progression, 100),
                ];
            }
        );


        /*
        |--------------------------------------------------------------------------
        | VUE
        |--------------------------------------------------------------------------
        */

        return view(
            'statistiques.index',
            compact(
                'campagne',
                'campagnes',
                'nombrePrefectures',
                'nombreEquipes',
                'nombreAgents',
                'nombreVillages',
                'nombreAffectations',
                'affectationsActives',
                'affectationsAnnulees',
                'villagesAffectes',
                'progression',
                'prefectures',
                'historique'
            )
        );
    }


    /**
     * =========================================================================
     * STATISTIQUES DPA
     * =========================================================================
     */
    private function statistiquesDpa(
        $user,
        int $prefectureId,
        $campagne,
        $campagnes
    ): View {

        /*
        |--------------------------------------------------------------------------
        | PRÉFECTURE
        |--------------------------------------------------------------------------
        */

        $prefecture = $user->prefectureActuelle;

        if (!$prefecture) {
            abort(
                403,
                'La préfecture de rattachement est introuvable.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ÉQUIPES
        |--------------------------------------------------------------------------
        */

        if ($this->colonneEquipePrefectureExiste()) {

            $nombreEquipes = Equipe::query()
                ->where(
                    'prefecture_id',
                    $prefectureId
                )
                ->count();

        } else {

            $nombreEquipes = Equipe::query()
                ->whereHas(
                    'superviseur',
                    function ($query) use ($prefectureId) {

                        $query->where(
                            'prefecture_id',
                            $prefectureId
                        );
                    }
                )
                ->count();
        }


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
        | VILLAGES DE LA PRÉFECTURE
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

        $affectations = Affectation::query()
            ->when(
                $campagne,
                function ($query) use ($campagne) {

                    $query->where(
                        'campagne_id',
                        $campagne->idCampagne
                    );
                }
            )
            ->whereHas(
                'village.canton.commune',
                function ($query) use ($prefectureId) {

                    $query->where(
                        'prefecture_id',
                        $prefectureId
                    );
                }
            );


        $nombreAffectations = (clone $affectations)
            ->count();

        $affectationsActives = (clone $affectations)
            ->where('statut', 'active')
            ->count();

        $affectationsAnnulees = (clone $affectations)
            ->where('statut', 'annulee')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | VILLAGES AFFECTÉS
        |--------------------------------------------------------------------------
        */

        $villagesAffectes = (clone $affectations)
            ->select('village_id')
            ->distinct()
            ->count();


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
        | STATISTIQUES PAR COMMUNE
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

                /*
                |------------------------------------------------------------------
                | VILLAGES
                |------------------------------------------------------------------
                */

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


                /*
                |------------------------------------------------------------------
                | VILLAGES AFFECTÉS
                |------------------------------------------------------------------
                */

                $villagesAffectes = Affectation::query()
                    ->when(
                        $campagne,
                        function ($query) use ($campagne) {

                            $query->where(
                                'campagne_id',
                                $campagne->idCampagne
                            );
                        }
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
                    ->select('village_id')
                    ->distinct()
                    ->count();


                /*
                |------------------------------------------------------------------
                | AFFECTATIONS
                |------------------------------------------------------------------
                */

                $affectations = Affectation::query()
                    ->when(
                        $campagne,
                        function ($query) use ($campagne) {

                            $query->where(
                                'campagne_id',
                                $campagne->idCampagne
                            );
                        }
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
                    ->count();


                /*
                |------------------------------------------------------------------
                | PROGRESSION
                |------------------------------------------------------------------
                */

                $progression = $villages > 0
                    ? round(
                        ($villagesAffectes / $villages) * 100
                    )
                    : 0;


                return [
                    'nom' => $commune->nom,
                    'villages' => $villages,
                    'villages_affectes' => $villagesAffectes,
                    'villages_non_affectes' =>
                        max(0, $villages - $villagesAffectes),
                    'affectations' => $affectations,
                    'progression' => min($progression, 100),
                ];
            });


        /*
        |--------------------------------------------------------------------------
        | HISTORIQUE DES CAMPAGNES DE LA PRÉFECTURE
        |--------------------------------------------------------------------------
        */

        $historique = $campagnes->map(
            function ($campagneHistorique) use ($prefectureId) {

                /*
                | Villages de la préfecture
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
                    ->count();


                /*
                | Villages affectés
                */

                $villagesAffectes = Affectation::query()
                    ->where(
                        'campagne_id',
                        $campagneHistorique->idCampagne
                    )
                    ->whereHas(
                        'village.canton.commune',
                        function ($query) use ($prefectureId) {

                            $query->where(
                                'prefecture_id',
                                $prefectureId
                            );
                        }
                    )
                    ->select('village_id')
                    ->distinct()
                    ->count();


                /*
                | Progression
                */

                $progression = $villages > 0
                    ? round(
                        ($villagesAffectes / $villages) * 100
                    )
                    : 0;


                return [
                    'libelle' => $campagneHistorique->libelle,
                    'statut' => $campagneHistorique->statut,
                    'dateDebut' => $campagneHistorique->dateDebut,
                    'dateFin' => $campagneHistorique->dateFin,
                    'villages_affectes' => $villagesAffectes,
                    'progression' => min($progression, 100),
                ];
            }
        );


        /*
        |--------------------------------------------------------------------------
        | VUE
        |--------------------------------------------------------------------------
        */

        return view(
            'statistiques.index',
            compact(
                'campagne',
                'campagnes',
                'prefecture',
                'nombreEquipes',
                'nombreAgents',
                'nombreSuperviseurs',
                'nombreVillages',
                'nombreAffectations',
                'affectationsActives',
                'affectationsAnnulees',
                'villagesAffectes',
                'villagesNonAffectes',
                'progression',
                'communes',
                'historique'
            )
        );
    }


    /**
     * =========================================================================
     * CAMPAGNES DISPONIBLES POUR UNE PRÉFECTURE
     * =========================================================================
     *
     * Un DPA ne doit voir que les campagnes qui ont un déploiement
     * dans sa préfecture.
     */
    private function campagnesPourPrefecture(int $prefectureId)
    {
        return CampagneRecensement::query()
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
    }


    /**
     * =========================================================================
     * NOMBRE D'AGENTS
     * =========================================================================
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
        | ADMIN
        |--------------------------------------------------------------------------
        */

        if ($prefectureId === null) {
            return $query->count();
        }


        /*
        |--------------------------------------------------------------------------
        | DPA
        |--------------------------------------------------------------------------
        */

        $query->where(
            function ($query) use ($prefectureId) {

                /*
                | users.prefecture_id
                */

                if (Schema::hasColumn('users', 'prefecture_id')) {

                    $query->where(
                        'prefecture_id',
                        $prefectureId
                    );
                }


                /*
                | rattachement actif
                */

                $query->orWhereHas(
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
        );

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

        $query = User::query()
            ->whereHas(
                'role',
                function ($query) {

                    $query->where(
                        'nom',
                        'Superviseur'
                    );
                }
            );


        $query->where(
            function ($query) use ($prefectureId) {

                /*
                | users.prefecture_id
                */

                if (Schema::hasColumn('users', 'prefecture_id')) {

                    $query->where(
                        'prefecture_id',
                        $prefectureId
                    );
                }


                /*
                | rattachement actif
                */

                $query->orWhereHas(
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
        );


        return $query->count();
    }


    /**
     * =========================================================================
     * PRÉFECTURE DE L'UTILISATEUR
     * =========================================================================
     */
    private function prefectureIdUtilisateur($user): ?int
    {
        /*
        |--------------------------------------------------------------------------
        | users.prefecture_id
        |--------------------------------------------------------------------------
        */

        if (
            Schema::hasColumn('users', 'prefecture_id') &&
            !empty($user->prefecture_id)
        ) {
            return (int) $user->prefecture_id;
        }


        /*
        |--------------------------------------------------------------------------
        | rattachement actif
        |--------------------------------------------------------------------------
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


        return $rattachement
            ? (int) $rattachement->prefecture_id
            : null;
    }


    /**
     * =========================================================================
     * COLONNE ÉQUIPE → PRÉFECTURE
     * =========================================================================
     */
    private function colonneEquipePrefectureExiste(): bool
    {
        return Schema::hasColumn(
            'equipes',
            'prefecture_id'
        );
    }
}