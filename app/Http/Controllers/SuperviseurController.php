<?php

namespace App\Http\Controllers;

use App\Models\Affectation;
use App\Models\CampagneRecensement;
use App\Models\Equipe;
use App\Models\VisiteSupervision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SuperviseurController extends Controller
{
    /**
     * ================================================================
     * TABLEAU DE BORD SUPERVISEUR
     * ================================================================
     */
    public function dashboard(): View
    {
        $user = Auth::user();

        $equipes = Equipe::where('superviseur_id', $user->id)->get();

        $affectations = Affectation::where('statut', 'active')
            ->whereHas('equipe', function ($query) use ($user) {
                $query->where('superviseur_id', $user->id);
            })
            ->get();

        return view('superviseur.dashboard', [
            'equipes'          => $equipes,
            'nombreEquipes'    => $equipes->count(),
            'nombreAgents'     => $equipes->sum(fn ($equipe) => $equipe->membres()->count()),
            'nombreVillages'   => $affectations->pluck('village_id')->unique()->count(),
            'affectations'     => $affectations,
        ]);
    }

    /**
     * ================================================================
     * MES ÉQUIPES
     * ================================================================
     */
    public function equipes(Request $request): View
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

    /**
     * ================================================================
     * DÉTAIL D'UNE ÉQUIPE
     * ================================================================
     */
    public function equipe(Equipe $equipe): View
    {
        $user = Auth::user();

        abort_unless(
            $equipe->superviseur_id === $user->id,
            403,
            "Cette équipe ne vous appartient pas."
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

    /**
     * ================================================================
     * MES ZONES (Villages supervisés)
     * ================================================================
     */
    public function zones(Request $request): View
    {
        $user = Auth::user();

        $search = trim($request->input('search', ''));

        $affectations = Affectation::query()
            ->with([
                'campagne',
                'equipe',
                'village.canton.commune.prefecture',
            ])
            ->where('statut', 'active')
            ->whereHas('equipe', function ($query) use ($user) {
                $query->where('superviseur_id', $user->id);
            })

            ->when($search !== '', function ($query) use ($search) {
                $query->whereHas('village', function ($village) use ($search) {
                    $village->where('nom', 'like', "%{$search}%");
                });
            })

            ->orderBy('village_id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'superviseur.zones.index',
            compact('affectations', 'search')
        );
    }

    /**
     * ================================================================
     * DÉTAIL D'UNE ZONE
     * ================================================================
     */
    public function zone(Affectation $affectation): View
    {
        $user = Auth::user();

        $autorise = Equipe::whereKey($affectation->equipe_id)
            ->where('superviseur_id', $user->id)
            ->exists();

        abort_unless($autorise, 403);

        $affectation->load([
            'campagne',
            'equipe.superviseur',
            'equipe.membres',
            'village.canton.commune.prefecture',
        ]);

        return view(
            'superviseur.zones.show',
            compact('affectation')
        );
    }

    /**
     * ================================================================
     * SUIVI DU RECENSEMENT
     * ================================================================
     */
  
public function suivi(): View
{
    $user = Auth::user();

    // 1. Sécurité Rôle Superviseur
    $estSuperviseur = $user && (
        (method_exists($user, 'isSuperviseur') && $user->isSuperviseur())
        || in_array(strtolower((string) ($user->role ?? '')), ['superviseur'], true)
    );

    abort_unless($estSuperviseur, 403, 'Cette page est réservée aux superviseurs.');

    // 2. Récupération de la Campagne Active
    $campagneActive = CampagneRecensement::query()
        ->where('statut', 'active')
        ->first();

    // 3. Équipes sous la responsabilité du superviseur
    $equipes = Equipe::query()
        ->where('superviseur_id', $user->id)
        ->with(['membres'])
        ->get();

    // Cas où aucune campagne n'est active ou le superviseur n'a pas d'équipe
    if (! $campagneActive || $equipes->isEmpty()) {
        return view('superviseur.suivi.index', [
            'campagneActive' => $campagneActive,
            'stats'          => [
                'agents'           => $equipes->sum(fn ($e) => $e->membres->count()),
                'villages'         => 0,
                'villages_actifs'  => 0,
                'maisons'          => 0,
                'exploitants'      => 0,
                'maisons_visitees' => 0,
            ],
            'lignesSuivi'    => collect(),
        ]);
    }

    // 4. Affectations actives de la campagne en cours pour ces équipes
    $affectations = Affectation::query()
        ->where('campagne_id', $campagneActive->getKey())
        ->where('statut', 'active')
        ->whereIn('equipe_id', $equipes->pluck('idEquipe'))
        ->with(['equipe', 'village.canton.commune.prefecture'])
        ->get()
        ->groupBy('equipe_id');

    $villageIds = $affectations->flatten(1)
        ->pluck('village_id')
        ->filter()
        ->map(fn ($id) => (int) $id)
        ->unique()
        ->values();

    $agentIds = $equipes
        ->flatMap(fn ($e) => $e->membres->pluck('id'))
        ->filter()
        ->unique()
        ->values();

    // 5. Recensements réels réalisés durant la campagne active
    $recensements = DB::table('recensements')
        ->join('maisons', 'recensements.maison_id', '=', 'maisons.idMaison')
        ->where('recensements.campagne_id', $campagneActive->getKey())
        ->whereIn('recensements.agent_id', $agentIds)
        ->when($villageIds->isNotEmpty(), fn ($q) => $q->whereIn('maisons.village_id', $villageIds))
        ->select([
            'recensements.idRecensement',
            'recensements.agent_id',
            'recensements.maison_id',
            'maisons.village_id',
            'recensements.created_at',
        ])
        ->get();

    $recensementIds = $recensements->pluck('idRecensement')->unique()->values();

    // Exploitants rattachés aux ménages des recensements de la campagne
    $exploitantsParRecensement = $recensementIds->isEmpty()
        ? collect()
        : DB::table('exploitants')
            ->join('menages', 'exploitants.menage_id', '=', 'menages.idMenage')
            ->whereIn('menages.recensement_id', $recensementIds)
            ->select('menages.recensement_id', DB::raw('COUNT(*) as total'))
            ->groupBy('menages.recensement_id')
            ->pluck('total', 'recensement_id');

    // 6. Visites de supervision effectuées par le superviseur
    $visites = $villageIds->isEmpty()
        ? collect()
        : VisiteSupervision::query()
            ->where('superviseur_id', $user->id)
            ->where('campagne_id', $campagneActive->getKey())
            ->whereIn('village_id', $villageIds)
            ->whereNotNull('maison_id')
            ->orderByDesc('date_visite')
            ->get();

    $visitesParVillage = $visites->groupBy('village_id');
    $recensementsParAgent = $recensements->groupBy('agent_id');

    $villagesAvecSaisie = $recensements->pluck('village_id')->filter()->map(fn ($id) => (int) $id);
    $villagesAvecVisite = $visites->pluck('village_id')->filter()->map(fn ($id) => (int) $id);
    $villagesActifsCount = $villagesAvecSaisie->merge($villagesAvecVisite)->unique()->count();

    // 7. Construction des lignes du tableau de suivi par membre d'équipe
    $lignesSuivi = collect();

    foreach ($equipes as $equipe) {
        $villagesEquipe = ($affectations->get($equipe->idEquipe) ?? collect())
            ->pluck('village')
            ->filter()
            ->unique('idVillage')
            ->values();

        $villagePrincipal = $villagesEquipe->first();
        $villageIdPrincipal = $villagePrincipal?->idVillage;

        foreach ($equipe->membres as $agent) {
            $recAgent = $recensementsParAgent->get($agent->id, collect());

            $maisonsRecensees = $recAgent->pluck('maison_id')->filter()->unique()->count();
            $exploitantsRecenses = (int) $recAgent->sum(
                fn ($r) => (int) ($exploitantsParRecensement[$r->idRecensement] ?? 0)
            );

            $derniereActivite = $recAgent->max('created_at');
            $visitesZone = $villageIdPrincipal
                ? ($visitesParVillage->get($villageIdPrincipal) ?? collect())
                : collect();

            $lignesSuivi->push((object) [
                'agent_nom'            => $agent->name,
                'equipe_libelle'       => $equipe->libelle ?? $equipe->nom ?? 'Équipe',
                'villages'             => $villagesEquipe,
                'village_principal'    => $villagePrincipal,
                'maisons_recensees'    => $maisonsRecensees,
                'exploitants_recenses' => $exploitantsRecenses,
                'derniere_activite'    => $derniereActivite,
                'maisons_visitees'     => $visitesZone->pluck('maison_id')->filter()->unique()->count(),
                'derniere_visite'      => $visitesZone->first(),
            ]);
        }
    }

    // 8. Statistiques globales réelles
    $stats = [
        'agents'           => $lignesSuivi->count(),
        'villages'         => $villageIds->count(),
        'villages_actifs'  => $villagesActifsCount,
        'maisons'          => (int) $lignesSuivi->sum('maisons_recensees'),
        'exploitants'      => (int) $lignesSuivi->sum('exploitants_recenses'),
        'maisons_visitees' => $visites->pluck('maison_id')->filter()->unique()->count(),
    ];

    return view('superviseur.suivi.index', compact(
        'campagneActive',
        'stats',
        'lignesSuivi'
    ));
}

    /**
     * ================================================================
     * MAISONS D'UN VILLAGE (pour marquer les visites)
     * ================================================================
     */
    public function maisons(string $villageId): View
    {
        $user = Auth::user();

        abort_unless(
            $this->villageIdsDuSuperviseur($user->id)->contains((int) $villageId),
            403,
            "Ce village ne fait pas partie de vos zones."
        );

        $village = DB::table('villages')
            ->where('idVillage', $villageId)
            ->first();

        abort_if(! $village, 404);

        $campagneActive = CampagneRecensement::query()
            ->where('statut', 'active')
            ->first();

        $maisons = DB::table('maisons')
            ->where('village_id', $villageId)
            ->orderBy('numeroMaison')
            ->get();

        // Chefs de ménage réels, via recensements -> menages
        $menages = DB::table('menages')
            ->join('recensements', 'menages.recensement_id', '=', 'recensements.idRecensement')
            ->whereIn('recensements.maison_id', $maisons->pluck('idMaison'))
            ->select('recensements.maison_id', 'menages.nomChef', 'menages.prenomChef')
            ->distinct()
            ->get()
            ->groupBy('maison_id');

        $visites = VisiteSupervision::query()
            ->where('superviseur_id', $user->id)
            ->where('village_id', $villageId)
            ->whereNotNull('maison_id')
            ->when($campagneActive, function ($query) use ($campagneActive) {
                $query->where('campagne_id', $campagneActive->getKey());
            })
            ->orderByDesc('date_visite')
            ->get()
            ->groupBy('maison_id')
            ->map(fn ($groupe) => $groupe->first());

        return view(
            'superviseur.suivi.maisons',
            compact('village', 'maisons', 'visites', 'campagneActive', 'menages')
        );
    }

    /**
     * ================================================================
     * ENREGISTRER UNE VISITE (marque sur une maison)
     * ================================================================
     */
    public function enregistrerVisite(Request $request)
    {
        $user = Auth::user();

        $campagne = CampagneRecensement::query()
            ->where('statut', 'active')
            ->first();

        if (! $campagne) {
            return back()->withErrors([
                'campagne' => "La campagne est clôturée : impossible d'enregistrer une visite.",
            ]);
        }

        $data = $request->validate([
            'village_id'  => 'required',
            'maison_id'   => 'required',
            'statut'      => 'required|in:conforme,a_corriger,probleme',
            'observation' => 'nullable|string|max:1000',
        ]);

        abort_unless(
            $this->villageIdsDuSuperviseur($user->id)->contains((int) $data['village_id']),
            403,
            "Ce village ne fait pas partie de vos zones."
        );

        $maisonExiste = DB::table('maisons')
            ->where('idMaison', $data['maison_id'])
            ->where('village_id', $data['village_id'])
            ->exists();

        abort_unless($maisonExiste, 404);

        VisiteSupervision::create($data + [
            'superviseur_id' => $user->id,
            'campagne_id'    => $campagne->getKey(),
            'date_visite'    => now(),
        ]);

        return back()->with('success', 'Visite enregistrée.');
    }

    /**
     * ================================================================
     * CONTRÔLE QUALITÉ
     * ================================================================
     */
    public function controleQualite(): View
    {
        $user = Auth::user();

        abort_unless(
            $user && (
                method_exists($user, 'isSuperviseur')
                    ? $user->isSuperviseur()
                    : $user->role?->nom === 'Superviseur'
            ),
            403,
            'Cette page est réservée aux superviseurs.'
        );

        $campagneActive = CampagneRecensement::query()
            ->where('statut', 'active')
            ->first();

        $affectations = Affectation::query()
            ->where('statut', 'active')
            ->whereHas('equipe', function ($query) use ($user) {
                $query->where('superviseur_id', $user->id);
            })
            ->with([
                'equipe.membres',
                'village',
                'campagne',
            ])
            ->get();

        $stats = [
            'questionnaires' => 0,
            'incomplets'     => 0,
            'incoherences'   => 0,
            'corrections'    => 0,
            'verifications'  => 0,
        ];

        return view(
            'superviseur.controle-qualite.index',
            compact(
                'campagneActive',
                'affectations',
                'stats'
            )
        );
    }

    /**
     * ================================================================
     * OUTIL : villages des équipes d'un superviseur
     * ================================================================
     */
    private function villageIdsDuSuperviseur($superviseurId)
    {
        return Affectation::query()
            ->where('statut', 'active')
            ->whereHas('equipe', function ($query) use ($superviseurId) {
                $query->where('superviseur_id', $superviseurId);
            })
            ->pluck('village_id')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();
    }
}