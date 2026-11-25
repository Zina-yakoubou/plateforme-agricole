<?php

namespace App\Http\Controllers\Dpa;

use App\Http\Controllers\Controller;
use App\Models\BesoinIntrant;
use App\Models\CampagneRecensement;
use App\Models\Exploitant;
use App\Models\Intrant;
use App\Models\Prefecture;
use App\Services\EvaluationBesoinService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BesoinIntrantController extends Controller
{
    public function __construct(protected EvaluationBesoinService $evaluationService)
    {
    }

       public function index(Request $request)
    {
        $user = Auth::user();
        $prefectureId = $user->prefecture_id ?? null;

        // 1. Requête de base sur les besoins (avec les relations directes et sûres)
        $query = BesoinIntrant::with([
            'cultureParcelle.culture',
            'cultureParcelle.parcelle.exploitation.exploitant',
            'intrant',
            'agentEvaluateur',
        ]);

        // Filtrage par intrant
        if ($request->filled('intrant_id')) {
            $query->where('intrant_id', $request->intrant_id);
        }

        // 2. Pagination de la liste détaillée
        $besoins = $query->latest('dateEvaluation')->paginate(15)->withQueryString();

        // 3. Agrégation globale des besoins par type d'intrant (Consolidation SQL)
        $consolidationParIntrant = BesoinIntrant::select(
            'intrant_id',
            'unite',
            DB::raw('SUM(quantitePrevue) as quantite_totale'),
            DB::raw('COUNT(DISTINCT culture_parcelle_id) as total_parcelles')
        )
        ->groupBy('intrant_id', 'unite')
        ->with('intrant')
        ->get();

        // Data pour les filtres du formulaire
        $intrants = Intrant::all();

        return view('dpa.besoins.index', compact(
            'besoins',
            'consolidationParIntrant',
            'intrants'
        ));
    }

    public function lancerCalculGlobal(Request $request)
    {
        $campagne = $this->derniereCampagneCloturee();

        if (! $campagne) {
            return back()->with('error', "Aucune campagne clôturée : le calcul n'est pas possible.");
        }

        $campagneId = (int) $campagne->idCampagne;
        $prefectureId = $this->prefectureCiblee($request);
        $total = 0;

        Exploitant::query()
            ->dansCampagne($campagneId)
            ->when($prefectureId, fn ($q) => $q->dansPrefecture($prefectureId))
            ->chunkById(200, function ($exploitants) use ($campagneId, $prefectureId, &$total) {
                foreach ($exploitants as $exploitant) {
                    $total += $this->evaluationService
                        ->calculerPourExploitant($exploitant, $campagneId, $prefectureId);
                }
            }, 'idExploitant');

        $message = "$total besoins calculés à partir de la campagne « {$campagne->libelle} ».";

        $ignorees = $this->evaluationService->culturesSansBareme();
        if ($ignorees) {
            $message .= ' Attention : ' . array_sum($ignorees) . ' culture(s) ignorée(s) faute de barème.';
        }

        $sansPrefecture = $this->evaluationService->exploitantsSansPrefecture();
        if ($sansPrefecture) {
            $message .= " $sansPrefecture exploitant(s) sans préfecture ignoré(s).";
        }

        return back()->with($ignorees || $sansPrefecture ? 'warning' : 'success', $message);
    }

    private function derniereCampagneCloturee(): ?CampagneRecensement
    {
        return CampagneRecensement::where('statut', 'cloturee')
            ->orderByDesc('dateFin')
            ->first();
    }

    /**
     * DPA : préfecture imposée par son rattachement (jamais lue depuis le formulaire).
     * Admin : peut choisir une préfecture ; null = toutes.
     */
    private function prefectureCiblee(Request $request): ?int
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $id = $request->input('prefecture_id');
            return $id ? (int) $id : null;
        }

        $prefectureId = $user->prefectureId();

        abort_if(! $prefectureId, 403, 'Aucune préfecture rattachée à votre compte.');

        return $prefectureId;
    }
}