namespace App\Http\Controllers\Dpa;

use App\Http\Controllers\Controller;
use App\Models\Exploitant;
use App\Models\CampagneRecensement;
use App\Services\EvaluationBesoinService;
use Illuminate\Http\Request;

class BesoinIntrantController extends Controller
{
    protected EvaluationBesoinService $evaluationService;

    public function __construct(EvaluationBesoinService $evaluationService)
    {
        $this->evaluationService = $evaluationService;
    }

    /**
     * 1. Affiche le tableau de bord de consolidation des besoins par producteur.
     * URL: GET /dpa/besoins
     */
    public function index()
    {
        // Récupérer la campagne active via le modèle CampagneRecensement
        $campagneActive = CampagneRecensement::where('statut', 'active')->first();

        // Récupérer les exploitants avec pagination et relations
        $exploitants = Exploitant::with([
            'parcelles.cultureParcelles.culture',
            'parcelles.cultureParcelles.besoins.intrant'
        ])
        ->paginate(10);

        return view('dpa.besoins.index', compact('exploitants', 'campagneActive'));
    }

    /**
     * 2. Lance le calcul automatique global des besoins pour la campagne active.
     * URL: POST /dpa/besoins/calculer (Aucun ID dans l'URL)
     */
    public function lancerCalculGlobal(Request $request)
    {
        // Récupération automatique de la campagne active du moment
        $campagneActive = CampagneRecensement::where('statut', 'active')->first();

        if (!$campagneActive) {
            return redirect()->back()->with('error', 'Aucune campagne active trouvée pour lancer le calcul.');
        }

        // Récupération de tous les exploitants avec leurs parcelles et cultures associées
        $exploitants = Exploitant::with('parcelles.cultureParcelles')->get();

        if ($exploitants->isEmpty()) {
            return redirect()->back()->with('warning', 'Aucun exploitant ou parcelle trouvé pour effectuer le calcul.');
        }

        // Exécution du service de calcul pour chaque exploitant
        foreach ($exploitants as $exploitant) {
            $this->evaluationService->calculerPourExploitant($exploitant, $campagneActive->idCampagne);
        }

        return redirect()->back()->with('success', 'Le calcul automatique des besoins en intrants a été généré avec succès pour toute la campagne !');
    }
}