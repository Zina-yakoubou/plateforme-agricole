<?php

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
     * Affiche le tableau de bord de consolidation.
     */
    public function index()
    {
        $campagneActive = CampagneRecensement::where('statut', 'active')->first();

        // Récupération simple sans eager loading pour garantir la stabilité de l'affichage
        $exploitants = Exploitant::paginate(10);

        return view('dpa.besoins.index', compact('exploitants', 'campagneActive'));
    }

    /**
     * Lance le calcul global des besoins pour la campagne active.
     */
    // public function lancerCalculGlobal(Request $request)
    // {
    //     $campagneActive = CampagneRecensement::where('statut', 'active')->first();

    //     if (!$campagneActive) {
    //         return redirect()->back()->with('error', 'Aucune campagne active trouvée pour lancer le calcul.');
    //     }

    //     $exploitants = Exploitant::all();

    //     if ($exploitants->isEmpty()) {
    //         return redirect()->back()->with('warning', 'Aucun exploitant trouvé pour effectuer le calcul.');
    //     }

    //     foreach ($exploitants as $exploitant) {
    //         $this->evaluationService->calculerPourExploitant($exploitant, $campagneActive->idCampagne);
    //     }

    //     return redirect()->back()->with('success', 'Le calcul automatique des besoins en intrants a été généré avec succès !');
    // }



   /**
     * Lance le calcul global des besoins pour la campagne active.
     */
        public function lancerCalculGlobal(EvaluationBesoinService $service)
    {
        $campagne = CampagneRecensement::where('statut', 'active')->firstOrFail();

        $total = 0;

        Exploitant::with('parcelles.cultureParcelles')
            ->chunkById(200, function ($exploitants) use ($service, $campagne, &$total) {
                foreach ($exploitants as $exploitant) {
                    $total += $service->calculerPourExploitant($exploitant, $campagne->id);
                }
            });

        return back()->with('success', "$total besoins en intrants calculés.");
    }
}