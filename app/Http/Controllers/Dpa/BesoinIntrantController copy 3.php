<?php

namespace App\Http\Controllers\Dpa;

use App\Http\Controllers\Controller;
use App\Models\BaremeAgronomique;
use App\Models\BesoinIntrant;
use App\Models\Culture;
use App\Models\CultureParcelle;
use App\Models\Intrant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BesoinIntrantController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = BesoinIntrant::with([
            'cultureParcelle.culture',
            'cultureParcelle.parcelle.exploitation.exploitant',
            'intrant',
            'agentEvaluateur',
        ]);

        if ($request->filled('culture_id')) {
            $query->whereHas('cultureParcelle', function ($q) use ($request) {
                $q->where('culture_id', $request->culture_id);
            });
        }

        if ($request->filled('intrant_id')) {
            $query->where('intrant_id', $request->intrant_id);
        }

        $besoins = $query->latest('dateEvaluation')->paginate(15)->withQueryString();

        $intrants = Intrant::all();
        $cultures = Culture::orderBy('nomCulture', 'asc')->get();

        return view('dpa.besoins.index', compact('besoins', 'intrants', 'cultures'));
    }

    /**
     * Calcule le besoin UNIQUEMENT pour la culture sélectionnée.
     */
    public function lancerCalculGlobal(Request $request)
    {
        $user = Auth::user();
        $cultureIdCible = $request->input('culture_id');

        // 1. Exiger obligatoirement le choix d'une culture
        if (!$cultureIdCible) {
            return redirect()->back()->with('error', 'Veuillez sélectionner une culture spécifique dans la liste avant de lancer le calcul.');
        }

        // 2. Récupérer uniquement les barèmes de la culture choisie
        $baremesActifs = BaremeAgronomique::where('culture_id', $cultureIdCible)
            ->where('est_actif', true)
            ->get();

        if ($baremesActifs->isEmpty()) {
            return redirect()->back()->with('error', 'Aucun barème agronomique actif trouvé pour cette culture.');
        }

        // 3. Récupérer uniquement les parcelles de cette culture
        $culturesParcelles = CultureParcelle::with(['parcelle', 'culture'])
            ->where('culture_id', $cultureIdCible)
            ->get();

        if ($culturesParcelles->isEmpty()) {
            return redirect()->back()->with('error', 'Aucune parcelle n\'a été recensée pour cette culture.');
        }

        $compteur = 0;

        // 4. Calcul ciblé strict
        DB::transaction(function () use ($culturesParcelles, $baremesActifs, $user, &$compteur) {
            foreach ($culturesParcelles as $cp) {
                foreach ($baremesActifs as $bareme) {
                    $superficieHa = $cp->superficie_occupee ?? $cp->parcelle->superficie ?? 0;
                    $quantiteCalculated = $superficieHa * $bareme->dose_par_hectare;

                    if ($bareme->plafond_max_producteur && $quantiteCalculated > $bareme->plafond_max_producteur) {
                        $quantiteCalculated = $bareme->plafond_max_producteur;
                    }

                    BesoinIntrant::updateOrCreate(
                        [
                            'culture_parcelle_id' => $cp->idCultureParcelle,
                            'intrant_id'          => $bareme->intrant_id,
                        ],
                        [
                            'quantitePrevue'     => $quantiteCalculated,
                            'unite'              => str_replace('/ha', '', $bareme->unite),
                            'dateEvaluation'     => now(),
                            'agentEvaluateur_id' => $user->id,
                            'observations'       => "Calcul ciblé culture (#{$cultureIdCible})",
                        ]
                    );

                    $compteur++;
                }
            }
        });

        $nomCulture = $culturesParcelles->first()->culture->nomCulture ?? 'la culture choisie';

        return redirect()->route('dpa.besoins.index', ['culture_id' => $cultureIdCible])
            ->with('success', "Calcul réussi pour {$nomCulture} : {$compteur} besoin(s) mis à jour.");
    }
}