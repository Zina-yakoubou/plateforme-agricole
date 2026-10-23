<?php

namespace App\Http\Controllers\Dpa;

use App\Http\Controllers\Controller;
use App\Models\BaremeAgronomique;
use App\Models\BesoinIntrant;
use App\Models\CultureParcelle;
use App\Models\Intrant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BesoinController extends Controller
{
    /**
     * Affiche le tableau de bord de consolidation des besoins en intrants
     * filtré automatiquement par la préfecture du DPA connecté.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $prefectureId = $user->prefecture_id;

        // 1. Requête de base sur les besoins filtrés par la préfecture du DPA via la hiérarchie territoriale
        $query = BesoinIntrant::with([
            'cultureParcelle.culture',
            'cultureParcelle.parcelle.exploitation.exploitant.village.canton',
            'intrant',
            'agentEvaluateur',
        ])->whereHas('cultureParcelle.parcelle.exploitation.exploitant.village.canton.commune', function ($q) use ($prefectureId) {
            $q->where('prefecture_id', $prefectureId);
        });

        // Filtrage par intrant
        if ($request->filled('intrant_id')) {
            $query->where('intrant_id', $request->intrant_id);
        }

        // Filtrage par canton
        if ($request->filled('canton_id')) {
            $query->whereHas('cultureParcelle.parcelle.exploitation.exploitant.village', function ($q) use ($request) {
                $q->where('canton_id', $request->canton_id);
            });
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
        ->whereHas('cultureParcelle.parcelle.exploitation.exploitant.village.canton.commune', function ($q) use ($prefectureId) {
            $q->where('prefecture_id', $prefectureId);
        })
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

    /**
     * Déclenche le calcul/recalcul automatique des besoins en intrants
     * pour toutes les parcelles recensées dans la préfecture
     * en se basant sur les barèmes agronomiques actifs fixés par le DPA.
     */
    public function recalculerBesoins()
    {
        $user = Auth::user();
        $prefectureId = $user->prefecture_id;

        // Récupérer les barèmes actifs du DPA pour sa préfecture
        $baremesActifs = BaremeAgronomique::where('prefecture_id', $prefectureId)
            ->where('est_actif', true)
            ->get();

        if ($baremesActifs->isEmpty()) {
            return redirect()->back()->with('error', 'Aucun barème agronomique actif trouvé. Veuillez configurer vos barèmes avant de lancer le calcul.');
        }

        // Récupérer toutes les cultures sur parcelles dans la préfecture
        $culturesParcelles = CultureParcelle::with('parcelle')
            ->whereHas('parcelle.exploitation.exploitant.village.canton.commune', function ($q) use ($prefectureId) {
                $q->where('prefecture_id', $prefectureId);
            })->get();

        $compteur = 0;

        DB::transaction(function () use ($culturesParcelles, $baremesActifs, $user, &$compteur) {
            foreach ($culturesParcelles as $cp) {
                // Trouver les barèmes correspondant à la culture de cette parcelle
                $baremesCorrespondants = $baremesActifs->where('culture_id', $cp->culture_id);

                foreach ($baremesCorrespondants as $bareme) {
                    $superficieHa = $cp->superficie_occupee; // en Hectares
                    $quantiteCalculated = $superficieHa * $bareme->dose_par_hectare;

                    // Application du plafond individuel si défini
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
                            'observations'       => "Consolidation automatique - Barème DPA (#{$bareme->idBareme})",
                        ]
                    );

                    $compteur++;
                }
            }
        });

        return redirect()->route('dpa.besoins.index')
            ->with('success', "Consolidation terminée avec succès ! {$compteur} besoin(s) calculé(s) ou mis à jour.");
    }

    /**
     * Supprime un enregistrement de besoin spécifique en cas de correction.
     */
    public function destroy(BesoinIntrant $besoin)
    {
        $prefectureId = Auth::user()->prefecture_id;

        // Sécurité : Vérifier que le besoin appartient à la préfecture du DPA
        $besoinPrefectureId = $besoin->cultureParcelle
            ->parcelle
            ->exploitation
            ->exploitant
            ->village
            ->canton
            ->commune
            ->prefecture_id ?? null;

        if ($besoinPrefectureId !== $prefectureId) {
            abort(403, 'Action non autorisée sur cette préfecture.');
        }

        $besoin->delete();

        return redirect()->route('dpa.besoins.index')
            ->with('success', 'Le besoin en intrant a été supprimé.');
    }
}