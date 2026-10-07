<?php

namespace App\Http\Controllers;

use App\Models\Affectation;
use App\Models\CampagneRecensement;
use App\Models\Canton;
use App\Models\Equipe;
use App\Models\Exploitant;
use App\Models\Exploitation;
use App\Models\Menage;
use App\Models\Parcelle;
use App\Models\RattachementPrefecture;
use App\Models\Recensement;
use App\Models\User;
use App\Models\Village;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tableau de bord principal.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | FILTRES
        |--------------------------------------------------------------------------
        */

        $campagneId = $request->filled('campagne_id')
            ? (int) $request->input('campagne_id')
            : null;

        $cantonId = $request->filled('canton_id')
            ? (int) $request->input('canton_id')
            : null;

        $villageId = $request->filled('village_id')
            ? (int) $request->input('village_id')
            : null;

        /*
        |--------------------------------------------------------------------------
        | CAMPAGNES
        |--------------------------------------------------------------------------
        */

        $campagnes = CampagneRecensement::query()
            ->orderByDesc('annee')
            ->orderByDesc('idCampagne')
            ->get();

        /*
        | Si aucune campagne n'est sélectionnée :
        | - campagne active en priorité ;
        | - sinon première campagne disponible.
        */
        if (!$campagneId) {
            $campagneSelectionnee = $campagnes
                ->firstWhere('statut', 'active')
                ?? $campagnes->first();

            $campagneId = $campagneSelectionnee?->idCampagne;
        }

        $campagne = $campagneId
            ? CampagneRecensement::find($campagneId)
            : null;

        /*
        | Alias conservé pour éviter toute erreur si une ancienne partie
        | du Blade utilise encore $campagneActive.
        */
        $campagneActive = $campagne;

        /*
        |--------------------------------------------------------------------------
        | CANTONS
        |--------------------------------------------------------------------------
        */

        $cantons = Canton::query()
            ->orderBy('nom')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | VILLAGES
        |--------------------------------------------------------------------------
        */

        $villagesQuery = Village::query()
            ->orderBy('nom');

        if ($cantonId) {
            $villagesQuery->whereHas(
                'canton',
                fn (Builder $query) => $query->where(
                    'idCanton',
                    $cantonId
                )
            );
        }

        $villages = $villagesQuery->get();

        /*
        |--------------------------------------------------------------------------
        | ROLE
        |--------------------------------------------------------------------------
        */

        $role = $user->role?->nom ?? 'Utilisateur';

        /*
        |--------------------------------------------------------------------------
        | TABLEAU DE BORD PAR ROLE
        |--------------------------------------------------------------------------
        */

        return match ($role) {
            'Administrateur' => $this->dashboardAdministrateur(
                $request,
                $user,
                $role,
                $campagnes,
                $campagne,
                $campagneActive,
                $cantons,
                $villages,
                $campagneId,
                $cantonId,
                $villageId
            ),

            'DPA' => $this->dashboardDpa(
                $request,
                $user,
                $role,
                $campagnes,
                $campagne,
                $campagneActive,
                $cantons,
                $villages,
                $campagneId,
                $cantonId,
                $villageId
            ),

            'Superviseur' => $this->dashboardSuperviseur(
                $request,
                $user,
                $role,
                $campagnes,
                $campagne,
                $campagneActive,
                $cantons,
                $villages,
                $campagneId,
                $cantonId,
                $villageId
            ),

            'Agent recenseur' => $this->dashboardAgent(
                $request,
                $user,
                $role,
                $campagnes,
                $campagne,
                $campagneActive,
                $cantons,
                $villages,
                $campagneId,
                $cantonId,
                $villageId
            ),

            default => $this->dashboardAdministrateur(
                $request,
                $user,
                $role,
                $campagnes,
                $campagne,
                $campagneActive,
                $cantons,
                $villages,
                $campagneId,
                $cantonId,
                $villageId
            ),
        };
    }

    /**
     * Base des recensements avec les filtres sélectionnés.
     */
    private function requeteRecensements(
        ?int $campagneId,
        ?int $cantonId,
        ?int $villageId
    ): Builder {
        $query = Recensement::query();

        if ($campagneId) {
            $query->where('campagne_id', $campagneId);
        }

        if ($cantonId) {
            $query->whereHas(
                'affectation.village.canton',
                fn (Builder $q) => $q->where(
                    'idCanton',
                    $cantonId
                )
            );
        }

        if ($villageId) {
            $query->whereHas(
                'affectation',
                fn (Builder $q) => $q->where(
                    'village_id',
                    $villageId
                )
            );
        }

        return $query;
    }

    /**
     * Calcule les statistiques principales à partir d'une requête
     * de recensements déjà filtrée.
     */
    private function statistiquesRecensement(
        Builder $recensements
    ): array {
        $total = (clone $recensements)->count();

        $brouillons = (clone $recensements)
            ->where('statut', 'brouillon')
            ->count();

        $enCours = (clone $recensements)
            ->where('statut', 'en_cours')
            ->count();

        $termines = (clone $recensements)
            ->where('statut', 'termine')
            ->count();

        $valides = (clone $recensements)
            ->where('statut', 'valide')
            ->count();

        /*
        | Un dossier est considéré comme traité lorsqu'il est terminé
        | ou validé.
        */
        $traites = $termines + $valides;

        $progression = $total > 0
            ? round(($traites / $total) * 100, 1)
            : 0;

        /*
        | Une maison recensée correspond à une maison possédant
        | un recensement dans le périmètre sélectionné.
        */
        $maisonsRecensees = (clone $recensements)
            ->whereNotNull('maison_id')
            ->distinct('maison_id')
            ->count('maison_id');

        return [
            'total' => $total,
            'brouillons' => $brouillons,
            'enCours' => $enCours,
            'termines' => $termines,
            'valides' => $valides,
            'traites' => $traites,
            'progression' => $progression,
            'maisonsRecensees' => $maisonsRecensees,
        ];
    }

    /**
     * Identifiants des recensements filtrés.
     */
    private function idsRecensements(Builder $recensements): Builder
    {
        return (clone $recensements)
            ->select('idRecensement');
    }

    /**
     * Statistiques ménages / exploitants / exploitations / parcelles.
     */
    private function statistiquesAgricoles(
        Builder $recensements
    ): array {
        $ids = $this->idsRecensements($recensements);

        $menages = Menage::query()
            ->whereIn('recensement_id', $ids)
            ->count();

        $exploitants = Exploitant::query()
            ->whereHas(
                'menage',
                fn (Builder $query) => $query->whereIn(
                    'recensement_id',
                    $this->idsRecensements($recensements)
                )
            )
            ->count();

        $exploitations = Exploitation::query()
            ->whereHas(
                'exploitant.menage',
                fn (Builder $query) => $query->whereIn(
                    'recensement_id',
                    $this->idsRecensements($recensements)
                )
            )
            ->count();

        $parcellesQuery = Parcelle::query()
            ->whereHas(
                'exploitation.exploitant.menage',
                fn (Builder $query) => $query->whereIn(
                    'recensement_id',
                    $this->idsRecensements($recensements)
                )
            );

        $parcelles = (clone $parcellesQuery)->count();

        $superficieTotale = (float) (
            (clone $parcellesQuery)->sum('superficie')
        );

        return [
            'menages' => $menages,
            'exploitants' => $exploitants,
            'exploitations' => $exploitations,
            'parcelles' => $parcelles,
            'superficieTotale' => $superficieTotale,
        ];
    }

    /**
     * Progression par village.
     */
    private function progressionVillages(
        Builder $recensements,
        ?int $campagneId,
        ?int $cantonId,
        ?int $villageId
    ): array {
        $affectationsQuery = Affectation::query()
            ->with('village');

        if ($campagneId) {
            $affectationsQuery->where(
                'campagne_id',
                $campagneId
            );
        }

        if ($cantonId) {
            $affectationsQuery->whereHas(
                'village.canton',
                fn (Builder $query) => $query->where(
                    'idCanton',
                    $cantonId
                )
            );
        }

        if ($villageId) {
            $affectationsQuery->where(
                'village_id',
                $villageId
            );
        }

        $affectations = $affectationsQuery
            ->get()
            ->unique('village_id');

        $recensementsCollection = (clone $recensements)
            ->with('affectation.village')
            ->get();

        $resultats = [];

        foreach ($affectations as $affectation) {
            $village = $affectation->village;

            if (!$village) {
                continue;
            }

            $villageRecensements = $recensementsCollection
                ->filter(
                    fn (Recensement $recensement) =>
                        $recensement->affectation?->village_id
                        === $village->idVillage
                );

            $total = $villageRecensements->count();

            $traites = $villageRecensements
                ->whereIn('statut', ['termine', 'valide'])
                ->count();

            $progression = $total > 0
                ? round(($traites / $total) * 100, 1)
                : 0;

            $resultats[] = [
                'village' => $village,
                'total' => $total,
                'traites' => $traites,
                'progression' => $progression,
            ];
        }

        usort(
            $resultats,
            fn (array $a, array $b) =>
                $b['progression'] <=> $a['progression']
        );

        return $resultats;
    }

    /**
     * Tableau de bord Administrateur.
     */
    private function dashboardAdministrateur(
        Request $request,
        User $user,
        string $role,
        $campagnes,
        $campagne,
        $campagneActive,
        $cantons,
        $villages,
        ?int $campagneId,
        ?int $cantonId,
        ?int $villageId
    ): View {
        $recensements = $this->requeteRecensements(
            $campagneId,
            $cantonId,
            $villageId
        );

        $statsRecensement = $this->statistiquesRecensement(
            $recensements
        );

        $statsAgricoles = $this->statistiquesAgricoles(
            $recensements
        );

        $utilisateursTotal = User::count();

        $utilisateursActifs = User::whereNotNull(
            'last_login_at'
        )->count();

        /*
        | Si last_login_at n'existe pas dans ta table users,
        | cette partie doit être remplacée par ton vrai champ
        | de connexion. Pour éviter une erreur SQL, on récupère
        | ici les utilisateurs par rapport à leur statut si disponible.
        */
        try {
            $utilisateursActifs = User::where(
                'statut',
                'actif'
            )->count();

            $utilisateursInactifs = max(
                0,
                $utilisateursTotal - $utilisateursActifs
            );
        } catch (\Throwable $e) {
            $utilisateursActifs = $utilisateursTotal;
            $utilisateursInactifs = 0;
        }

        /*
        | Répartition des utilisateurs par rôle.
        */
        $utilisateursParRole = User::with('role')
            ->get()
            ->groupBy(
                fn (User $item) =>
                    $item->role?->nom ?? 'Sans rôle'
            )
            ->map(fn ($items) => $items->count());

        /*
        | Recensements par campagne.
        */
        $recensementsParCampagneBrut = Recensement::query()
            ->selectRaw(
                'campagne_id, COUNT(*) as total'
            )
            ->groupBy('campagne_id')
            ->pluck('total', 'campagne_id');

        $recensementsParCampagne = $campagnes->map(
            function ($item) use ($recensementsParCampagneBrut) {
                return [
                    'campagne' => $item,
                    'total' => (int) (
                        $recensementsParCampagneBrut[
                            $item->idCampagne
                        ] ?? 0
                    ),
                ];
            }
        );

        /*
        | Derniers comptes.
        */
        $comptesRecents = User::with('role')
            ->latest()
            ->take(5)
            ->get();

        /*
        | Villages couverts pour la campagne sélectionnée.
        */
        $affectationsCampagne = Affectation::query()
            ->with('village.canton.commune')
            ->when(
                $campagneId,
                fn (Builder $q) =>
                    $q->where('campagne_id', $campagneId)
            )
            ->get();

        $villagesCouverts = $affectationsCampagne
            ->pluck('village_id')
            ->filter()
            ->unique()
            ->count();

        $prefecturesCouvertes = $affectationsCampagne
            ->map(
                fn ($affectation) =>
                    $affectation->village?->canton?->commune?->prefecture_id
            )
            ->filter()
            ->unique()
            ->count();

        return view('dashboard', array_merge(
            [
                'user' => $user,
                'role' => $role,

                'campagnes' => $campagnes,
                'campagne' => $campagne,
                'campagneActive' => $campagneActive,

                'cantons' => $cantons,
                'villages' => $villages,

                'campagneId' => $campagneId,
                'cantonId' => $cantonId,
                'villageId' => $villageId,

                'utilisateursTotal' => $utilisateursTotal,
                'utilisateursActifs' => $utilisateursActifs,
                'utilisateursInactifs' => $utilisateursInactifs,

                'prefecturesCouvertes' => $prefecturesCouvertes,
                'villagesCouverts' => $villagesCouverts,

                'utilisateursParRole' => $utilisateursParRole,
                'recensementsParCampagne' => $recensementsParCampagne,
                'comptesRecents' => $comptesRecents,

                'progressionVillages' => $this->progressionVillages(
                    $recensements,
                    $campagneId,
                    $cantonId,
                    $villageId
                ),
            ],
            $this->donneesCommunes(
                $statsRecensement,
                $statsAgricoles
            )
        ));
    }

    /**
     * Tableau de bord DPA.
     */
    private function dashboardDpa(
        Request $request,
        User $user,
        string $role,
        $campagnes,
        $campagne,
        $campagneActive,
        $cantons,
        $villages,
        ?int $campagneId,
        ?int $cantonId,
        ?int $villageId
    ): View {
        $recensements = $this->requeteRecensements(
            $campagneId,
            $cantonId,
            $villageId
        );

        /*
        | Rattachement préfectoral du DPA.
        */
        $prefectureId = null;

        try {
            $prefectureId = RattachementPrefecture::query()
                ->where('user_id', $user->id)
                ->where(function ($query) {
                    $query->whereNull('dateFin')
                        ->orWhere('dateFin', '>=', now()->toDateString());
                })
                ->latest('dateDebut')
                ->value('prefecture_id');
        } catch (\Throwable $e) {
            $prefectureId = null;
        }

        /*
        | On limite le DPA à sa préfecture lorsque le rattachement
        | est disponible.
        */
        if ($prefectureId) {
            $recensements->whereHas(
                'affectation.village.canton.commune',
                fn (Builder $query) =>
                    $query->where(
                        'prefecture_id',
                        $prefectureId
                    )
            );
        }

        $statsRecensement = $this->statistiquesRecensement(
            $recensements
        );

        $statsAgricoles = $this->statistiquesAgricoles(
            $recensements
        );

        /*
        | Affectations de la campagne.
        */
        $affectations = Affectation::query()
            ->with('village')
            ->when(
                $campagneId,
                fn (Builder $q) =>
                    $q->where('campagne_id', $campagneId)
            )
            ->when(
                $cantonId,
                fn (Builder $q) =>
                    $q->whereHas(
                        'village.canton',
                        fn (Builder $v) =>
                            $v->where('idCanton', $cantonId)
                    )
            )
            ->when(
                $villageId,
                fn (Builder $q) =>
                    $q->where('village_id', $villageId)
            )
            ->when(
                $prefectureId,
                fn (Builder $q) =>
                    $q->whereHas(
                        'village.canton.commune',
                        fn (Builder $v) =>
                            $v->where(
                                'prefecture_id',
                                $prefectureId
                            )
                    )
            )
            ->get();

        $equipesConstituees = $affectations
            ->pluck('equipe_id')
            ->filter()
            ->unique()
            ->count();

        $equipesAffectees = $affectations
            ->where('statut', 'active')
            ->pluck('equipe_id')
            ->filter()
            ->unique()
            ->count();

        $villagesDeployes = $affectations
            ->pluck('village_id')
            ->filter()
            ->unique()
            ->count();

        /*
        | Nombre total de villages de la préfecture.
        */
        $villagesTotalQuery = Village::query();

        if ($prefectureId) {
            $villagesTotalQuery->whereHas(
                'canton.commune',
                fn (Builder $query) =>
                    $query->where(
                        'prefecture_id',
                        $prefectureId
                    )
            );
        }

        if ($cantonId) {
            $villagesTotalQuery->whereHas(
                'canton',
                fn (Builder $query) =>
                    $query->where(
                        'idCanton',
                        $cantonId
                    )
            );
        }

        if ($villageId) {
            $villagesTotalQuery->where(
                'idVillage',
                $villageId
            );
        }

        $villagesTotal = $villagesTotalQuery->count();

        $villagesRestants = max(
            0,
            $villagesTotal - $villagesDeployes
        );

        /*
        | Agents ayant effectivement des recensements dans le périmètre.
        */
        $nombreAgents = (clone $recensements)
            ->whereNotNull('agent_id')
            ->distinct('agent_id')
            ->count('agent_id');

        /*
        | Progression par village.
        */
        $progressionVillages = $this->progressionVillages(
            $recensements,
            $campagneId,
            $cantonId,
            $villageId
        );

        /*
        | Suivi des équipes.
        */
        $recensementsCollection = (clone $recensements)
            ->with('affectation.equipe')
            ->get();

        $suiviEquipes = [];

        foreach (
            $affectations
                ->unique('equipe_id')
            as $affectation
        ) {
            $equipe = $affectation->equipe;

            if (!$equipe) {
                continue;
            }

            $items = $recensementsCollection->filter(
                fn (Recensement $recensement) =>
                    $recensement->affectation?->equipe_id
                    === $equipe->idEquipe
            );

            $total = $items->count();

            $traites = $items
                ->whereIn(
                    'statut',
                    ['termine', 'valide']
                )
                ->count();

            $progression = $total > 0
                ? round(($traites / $total) * 100, 1)
                : 0;

            $suiviEquipes[] = [
                'equipe' => $equipe,
                'total' => $total,
                'traites' => $traites,
                'progression' => $progression,
            ];
        }

        usort(
            $suiviEquipes,
            fn (array $a, array $b) =>
                $b['progression'] <=> $a['progression']
        );

        /*
        | File de contrôle :
        | les dossiers terminés attendent la validation.
        */
        $fileControle = (clone $recensements)
            ->where('statut', 'termine')
            ->with([
                'affectation.village',
                'agent',
            ])
            ->latest('dateDerniereModification')
            ->take(10)
            ->get();

        return view('dashboard', array_merge(
            [
                'user' => $user,
                'role' => $role,

                'campagnes' => $campagnes,
                'campagne' => $campagne,
                'campagneActive' => $campagneActive,

                'cantons' => $cantons,
                'villages' => $villages,

                'campagneId' => $campagneId,
                'cantonId' => $cantonId,
                'villageId' => $villageId,

                'equipesConstituees' => $equipesConstituees,
                'equipesAffectees' => $equipesAffectees,

                'villagesDeployes' => $villagesDeployes,
                'villagesRestants' => $villagesRestants,

                'nombreAgents' => $nombreAgents,

                'progressionVillages' => $progressionVillages,
                'suiviEquipes' => $suiviEquipes,
                'fileControle' => $fileControle,
            ],
            $this->donneesCommunes(
                $statsRecensement,
                $statsAgricoles
            )
        ));
    }

    /**
     * Tableau de bord Superviseur.
     */
    private function dashboardSuperviseur(
        Request $request,
        User $user,
        string $role,
        $campagnes,
        $campagne,
        $campagneActive,
        $cantons,
        $villages,
        ?int $campagneId,
        ?int $cantonId,
        ?int $villageId
    ): View {
        /*
        | Equipes supervisées.
        */
        $equipes = Equipe::query()
            ->where('superviseur_id', $user->id)
            ->get();

        $equipeIds = $equipes
            ->pluck('idEquipe')
            ->filter()
            ->values();

        $recensements = $this->requeteRecensements(
            $campagneId,
            $cantonId,
            $villageId
        );

        $recensements->whereHas(
            'affectation',
            fn (Builder $query) =>
                $query->whereIn(
                    'equipe_id',
                    $equipeIds
                )
        );

        $statsRecensement = $this->statistiquesRecensement(
            $recensements
        );

        $statsAgricoles = $this->statistiquesAgricoles(
            $recensements
        );

        $villagesSuivis = Affectation::query()
            ->when(
                $campagneId,
                fn (Builder $q) =>
                    $q->where(
                        'campagne_id',
                        $campagneId
                    )
            )
            ->whereIn(
                'equipe_id',
                $equipeIds
            )
            ->when(
                $cantonId,
                fn (Builder $q) =>
                    $q->whereHas(
                        'village.canton',
                        fn (Builder $v) =>
                            $v->where(
                                'idCanton',
                                $cantonId
                            )
                    )
            )
            ->when(
                $villageId,
                fn (Builder $q) =>
                    $q->where(
                        'village_id',
                        $villageId
                    )
            )
            ->distinct('village_id')
            ->count('village_id');

        $progressionAgents = [];

        $itemsAgents = (clone $recensements)
            ->with('agent')
            ->get()
            ->groupBy('agent_id');

        foreach ($itemsAgents as $agentId => $items) {
            $agent = $items->first()?->agent;

            $total = $items->count();

            $traites = $items
                ->whereIn(
                    'statut',
                    ['termine', 'valide']
                )
                ->count();

            $progressionAgents[] = [
                'agent' => $agent,
                'total' => $total,
                'traites' => $traites,
                'progression' => $total > 0
                    ? round(
                        ($traites / $total) * 100,
                        1
                    )
                    : 0,
            ];
        }

        usort(
            $progressionAgents,
            fn (array $a, array $b) =>
                $b['progression'] <=> $a['progression']
        );

        $fileControle = (clone $recensements)
            ->where('statut', 'termine')
            ->with([
                'affectation.village',
                'agent',
            ])
            ->latest('dateDerniereModification')
            ->take(10)
            ->get();

        $progressionVillages = $this->progressionVillages(
            $recensements,
            $campagneId,
            $cantonId,
            $villageId
        );

        return view('dashboard', array_merge(
            [
                'user' => $user,
                'role' => $role,

                'campagnes' => $campagnes,
                'campagne' => $campagne,
                'campagneActive' => $campagneActive,

                'cantons' => $cantons,
                'villages' => $villages,

                'campagneId' => $campagneId,
                'cantonId' => $cantonId,
                'villageId' => $villageId,

                'equipes' => $equipes,
                'agents' => $itemsAgents->count(),
                'villagesSuivis' => $villagesSuivis,

                'progressionAgents' => $progressionAgents,
                'fileControle' => $fileControle,
                'progressionVillages' => $progressionVillages,
            ],
            $this->donneesCommunes(
                $statsRecensement,
                $statsAgricoles
            )
        ));
    }

    /**
     * Tableau de bord Agent recenseur.
     */
    private function dashboardAgent(
        Request $request,
        User $user,
        string $role,
        $campagnes,
        $campagne,
        $campagneActive,
        $cantons,
        $villages,
        ?int $campagneId,
        ?int $cantonId,
        ?int $villageId
    ): View {
        $recensements = $this->requeteRecensements(
            $campagneId,
            $cantonId,
            $villageId
        );

        /*
        | L'agent ne voit que ses propres recensements.
        */
        $recensements->where(
            'agent_id',
            $user->id
        );

        $statsRecensement = $this->statistiquesRecensement(
            $recensements
        );

        $statsAgricoles = $this->statistiquesAgricoles(
            $recensements
        );

        /*
        | À faire :
        | brouillons + recensements en cours.
        */
        $aFaire = (clone $recensements)
            ->whereIn(
                'statut',
                ['brouillon', 'en_cours']
            )
            ->with([
                'maison',
                'affectation.village',
            ])
            ->latest('dateDerniereModification')
            ->take(10)
            ->get();

        /*
        | Villages réellement rencontrés dans les recensements
        | de l'agent.
        */
        $progressionVillages = $this->progressionVillages(
            $recensements,
            $campagneId,
            $cantonId,
            $villageId
        );

        return view('dashboard', array_merge(
            [
                'user' => $user,
                'role' => $role,

                'campagnes' => $campagnes,
                'campagne' => $campagne,
                'campagneActive' => $campagneActive,

                'cantons' => $cantons,
                'villages' => $villages,

                'campagneId' => $campagneId,
                'cantonId' => $cantonId,
                'villageId' => $villageId,

                'aFaire' => $aFaire,
                'progressionVillages' => $progressionVillages,
            ],
            $this->donneesCommunes(
                $statsRecensement,
                $statsAgricoles
            )
        ));
    }

    /**
     * Variables communes à tous les dashboards.
     */
    private function donneesCommunes(
        array $statsRecensement,
        array $statsAgricoles
    ): array {
        return [
            /*
            | Recensements
            */
            'nombreRecensements' =>
                $statsRecensement['total'],

            'maisonsRecensees' =>
                $statsRecensement['maisonsRecensees'],

            'dossiersBrouillon' =>
                $statsRecensement['brouillons'],

            'dossiersEnCours' =>
                $statsRecensement['enCours'],

            'dossiersTermines' =>
                $statsRecensement['termines'],

            'dossiersValides' =>
                $statsRecensement['valides'],

            'dossiersTraites' =>
                $statsRecensement['traites'],

            'progression' =>
                $statsRecensement['progression'],

            /*
            | Données agricoles
            */
            'menages' =>
                $statsAgricoles['menages'],

            'exploitants' =>
                $statsAgricoles['exploitants'],

            'exploitations' =>
                $statsAgricoles['exploitations'],

            'parcelles' =>
                $statsAgricoles['parcelles'],

            'superficieTotale' =>
                $statsAgricoles['superficieTotale'],
        ];
    }
}