<?php

namespace App\Http\Controllers;

use App\Models\Affectation;
use App\Models\CampagneRecensement;
use App\Models\Equipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    


    public function suivi(): View
    {
        $user = Auth::user();

        $estSuperviseur = $user
            && (
                (method_exists($user, 'isSuperviseur') && $user->isSuperviseur())
                || in_array($user->role ?? null, ['superviseur', 'Superviseur'], true)
            );

        abort_unless(
            $estSuperviseur,
            403,
            'Cette page est réservée aux superviseurs.'
        );

        /*
        |--------------------------------------------------------------------------
        | Campagne active
        |--------------------------------------------------------------------------
        */
        $campagneActive = \App\Models\CampagneRecensement::query()
            ->where('statut', 'active')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Affectations des équipes supervisées
        |--------------------------------------------------------------------------
        */
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

        /*
        |--------------------------------------------------------------------------
        | Équipes du superviseur
        |--------------------------------------------------------------------------
        */
        $equipes = Equipe::query()
            ->where('superviseur_id', $user->id)
            ->with('membres')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Agents supervisés
        |--------------------------------------------------------------------------
        */
        $agents = collect();

        foreach ($equipes as $equipe) {

            foreach ($equipe->membres as $agent) {

                /*
                * On récupère l'affectation de cette équipe.
                */
                $affectation = $affectations
                    ->where('equipe_id', $equipe->idEquipe)
                    ->first();

                /*
                * On ajoute les informations directement sur
                * l'objet User pour que la vue puisse utiliser :
                *
                * $agent->name
                * $agent->telephone
                * $agent->equipe
                * $agent->village
                * etc.
                */
                $agent->setAttribute(
                    'equipe',
                    $equipe
                );

                $agent->setAttribute(
                    'village',
                    $affectation?->village
                );

                $agent->setAttribute(
                    'maisons_recensees',
                    0
                );

                $agent->setAttribute(
                    'exploitants_recenses',
                    0
                );

                $agent->setAttribute(
                    'progression',
                    0
                );

                $agents->push($agent);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Statistiques générales
        |--------------------------------------------------------------------------
        */
        $nombreEquipes = $equipes->count();

        $nombreAgents = $agents->count();

        $nombreVillages = $affectations
            ->pluck('village_id')
            ->unique()
            ->count();

        /*
        * Les vrais nombres seront branchés lorsque les modules
        * Maisons / Ménages / Exploitants seront intégrés.
        */
        $nombreMaisons = 0;

        $nombreExploitants = 0;

        $progression = 0;

        /*
        |--------------------------------------------------------------------------
        | Statistiques envoyées à la vue
        |--------------------------------------------------------------------------
        */
        $stats = [
            'equipes'     => $nombreEquipes,
            'agents'      => $nombreAgents,
            'villages'    => $nombreVillages,
            'maisons'     => $nombreMaisons,
            'exploitants' => $nombreExploitants,
            'progression' => $progression,
        ];

        /*
        |--------------------------------------------------------------------------
        | Retour de la vue
        |--------------------------------------------------------------------------
        */
        return view(
            'superviseur.suivi.index',
            compact(
                'campagneActive',
                'stats',
                'agents',
                'affectations',
                'equipes'
            )
        );
    }




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

        /*
        |--------------------------------------------------------------------------
        | Affectations des équipes supervisées
        |--------------------------------------------------------------------------
        */
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

        /*
        |--------------------------------------------------------------------------
        | Pour le moment, les contrôles sont initialisés à zéro.
        |
        | Ils seront ensuite calculés à partir des questionnaires,
        | maisons, ménages et exploitants réellement enregistrés.
        |--------------------------------------------------------------------------
        */

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
}