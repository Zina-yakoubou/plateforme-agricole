<?php

namespace App\Http\Controllers;

use App\Models\Affectation;
use App\Models\Recensement;
use App\Models\Verification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VerificationController extends Controller
{
    /**
     * Seul le superviseur de l'équipe peut vérifier cette zone.
     */
    private function autoriser(Affectation $affectation): void
    {
        $user = Auth::user();

        abort_unless(
            $user && is_callable([$user, 'isSuperviseur']) && $user->isSuperviseur(),
            403,
            'Cette page est réservée aux superviseurs.'
        );

        abort_unless(
            $affectation->equipe()
                ->where('superviseur_id', $user->id)
                ->exists(),
            403,
            'Vous n’êtes pas autorisé à vérifier cette zone.'
        );
    }

    /**
     * Liste des maisons recensées du village, à vérifier.
     * ?hasard=3 propose 3 maisons tirées au hasard parmi celles
     * qui n'ont pas encore été vérifiées.
     */
    public function index(Request $request, Affectation $affectation): View
    {
        $this->autoriser($affectation);

        $affectation->load([
            'campagne',
            'equipe',
            'village.canton.commune.prefecture',
        ]);

        $verifications = Verification::query()
            ->where('affectation_id', $affectation->getKey())
            ->get()
            ->keyBy('recensement_id');

        $tous = Recensement::query()
            ->with(['maison', 'agent'])
            ->where('affectation_id', $affectation->getKey())
            ->whereIn('statut', ['termine', 'valide'])
            ->get()
            ->sortBy(fn ($r) => $r->maison?->numeroMaison)
            ->values();

        $nombreHasard = max(0, (int) $request->input('hasard', 0));
        $modeHasard = $nombreHasard > 0;

        $recensements = $modeHasard
            ? $tous
                ->reject(fn ($r) => $verifications->has($r->getKey()))
                ->shuffle()
                ->take($nombreHasard)
                ->values()
            : $tous;

        $total = $tous->count();

        $verifiees = $tous
            ->filter(fn ($r) => $verifications->has($r->getKey()))
            ->count();

        $conformes = $verifications
            ->where('resultat', 'conforme')
            ->count();

        return view('superviseur.zones.verification', compact(
            'affectation',
            'recensements',
            'verifications',
            'total',
            'verifiees',
            'conformes',
            'modeHasard',
            'nombreHasard'
        ));
    }

    /**
     * Enregistre (ou modifie) le résultat d'une visite.
     */
    public function store(Request $request, Affectation $affectation): RedirectResponse
    {
        $this->autoriser($affectation);

        $data = $request->validate(
            [
                'recensement_id' => ['required', 'integer'],
                'resultat' => ['required', 'in:conforme,non_conforme,introuvable'],
                'remarque' => [
                    'nullable',
                    'string',
                    'max:1000',
                    'required_if:resultat,non_conforme,introuvable',
                ],
            ],
            [
                'resultat.required' => 'Choisissez un résultat.',
                'remarque.required_if' => 'Une remarque est obligatoire quand la maison est non conforme ou introuvable.',
            ]
        );

        // La fiche doit appartenir à cette zone.
        $recensement = Recensement::query()
            ->where('idRecensement', $data['recensement_id'])
            ->where('affectation_id', $affectation->getKey())
            ->firstOrFail();

        Verification::updateOrCreate(
            ['recensement_id' => $recensement->getKey()],
            [
                'affectation_id' => $affectation->getKey(),
                'maison_id' => $recensement->maison_id,
                'superviseur_id' => Auth::id(),
                'agent_id' => $recensement->agent_id,
                'resultat' => $data['resultat'],
                'remarque' => $data['remarque'] ?? null,
                'dateVisite' => now(),
            ]
        );

        return redirect()
            ->route('superviseur.verifications.index', $affectation)
            ->with('success', 'Vérification enregistrée.');
    }
}