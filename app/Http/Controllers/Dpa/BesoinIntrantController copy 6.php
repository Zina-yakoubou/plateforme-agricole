<?php

namespace App\Http\Controllers\Dpa;

use App\Http\Controllers\Controller;
use App\Models\BaremeAgronomique;
use App\Models\BesoinIntrant;
use App\Models\CampagneRecensement;
use App\Models\Culture;
use App\Models\Intrant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BesoinIntrantController extends Controller
{
    /** Seuls les recensements terminés ou validés entrent dans le calcul. */
    private const STATUTS_RETENUS = ['termine', 'valide'];

    public function index(Request $request)
    {
        $prefectureId = $this->prefectureCible($request);
        $campagne     = $this->campagneChoisie($request);
        $table        = (new BesoinIntrant)->getTable();

        // Même filtre pour la liste et pour les totaux :
        // campagne choisie (par défaut la dernière clôturée), préfecture du DPA, culture, intrant
        $filtrer = fn ($q) => $q
            ->when($campagne, fn ($q) => $q->whereIn(
                $table . '.culture_parcelle_id',
                $this->parcellesCultivees($prefectureId, $campagne->getKey())->select('cp.idCultureParcelle')
            ))
            ->when($request->filled('culture_id'), fn ($q) => $q->whereHas(
                'cultureParcelle',
                fn ($c) => $c->where('culture_id', $request->culture_id)
            ))
            ->when($request->filled('intrant_id'), fn ($q) => $q->where($table . '.intrant_id', $request->intrant_id));

        $besoins = $filtrer(BesoinIntrant::with([
            'cultureParcelle.culture',
            'cultureParcelle.parcelle.exploitation.exploitant',
            'intrant',
            'agentEvaluateur',
        ]))->latest('dateEvaluation')->paginate(15)->withQueryString();

        // Consolidation : total par intrant
        $totaux = $filtrer(BesoinIntrant::query())
            ->join('intrants as i', 'i.idIntrant', '=', $table . '.intrant_id')
            ->groupBy('i.idIntrant', 'i.nom', $table . '.unite')
            ->select(
                'i.nom',
                $table . '.unite',
                DB::raw('SUM(' . $table . '.quantitePrevue) as total'),
                DB::raw('COUNT(DISTINCT ' . $table . '.culture_parcelle_id) as parcelles')
            )
            ->orderBy('i.nom')
            ->get();

        // Village de chaque culture de parcelle affichée
        $villages = $this->parcellesCultivees()
            ->whereIn('cp.idCultureParcelle', $besoins->pluck('culture_parcelle_id'))
            ->pluck('v.nom', 'cp.idCultureParcelle');

        $intrants = Intrant::orderBy('nom')->get();
        $cultures = Culture::orderBy('nomCulture')->get();

        return view('dpa.besoins.index', compact('besoins', 'intrants', 'cultures', 'campagne', 'totaux', 'villages'));
    }

    /**
     * Calcule les besoins de TOUTES les cultures de la préfecture
     * (ou d'une seule culture si culture_id est fourni).
     *
     *   besoin d'un producteur = Σ (superficie retenue × dose du barème de sa préfecture),
     *   limité au plafond par producteur, puis réparti entre ses parcelles.
     */
    public function lancerCalculGlobal(Request $request)
    {
        $request->validate([
            'culture_id'  => ['nullable', 'exists:cultures,idCulture'],
            'campagne_id' => ['nullable', 'exists:campagne_recensements,idCampagne'],
        ]);

        $user         = Auth::user();
        $cultureId    = $request->filled('culture_id') ? (int) $request->input('culture_id') : null;
        $prefectureId = $this->prefectureCible($request);
        $campagne     = $this->campagneChoisie($request);

        if (! $campagne) {
            return back()->with('error', 'Aucune campagne clôturée : il n\'y a pas encore de données à exploiter.');
        }

        // 1. Cultures de parcelles de la campagne (préfecture, statuts, parcelles exploitées)
        $lignes = $this->parcellesCultivees($prefectureId, $campagne->getKey())
            ->when($cultureId, fn ($q) => $q->where('cp.culture_id', $cultureId))
            ->get([
                'cp.idCultureParcelle as cp_id',
                'cp.culture_id as culture_id',
                'x.idExploitant as exploitant_id',
                'co.prefecture_id as prefecture_id',
                'p.superficie as ha_parcelle',
                'cp.superficieCultivee as ha_cultivee',
            ]);

        if ($lignes->isEmpty()) {
            return back()->with('error', "Aucune parcelle exploitée dans un recensement terminé ou validé pour la campagne « {$campagne->libelle} ».");
        }

        $lignes->each(fn ($l) => $l->ha = $this->superficieRetenue($l));

        // 2. Barèmes actifs, regroupés par « préfecture-culture »
        $baremes = BaremeAgronomique::where('est_actif', true)
            ->when($prefectureId, fn ($q) => $q->where('prefecture_id', $prefectureId))
            ->when($cultureId, fn ($q) => $q->where('culture_id', $cultureId))
            ->get()
            ->groupBy(fn ($b) => $b->prefecture_id . '-' . $b->culture_id);

        if ($baremes->isEmpty()) {
            return back()->with('error', 'Aucun barème agronomique actif : définissez d\'abord les barèmes.');
        }

        $culturesSansBareme = [];
        $culturesCalculees  = [];
        $compteur           = 0;
        $plafonnes          = 0;

        // 3. Calcul : par préfecture et culture, puis par barème, puis par producteur
        DB::transaction(function () use ($lignes, $baremes, $user, $campagne, &$culturesSansBareme, &$culturesCalculees, &$compteur, &$plafonnes) {

            foreach ($lignes->groupBy(fn ($l) => $l->prefecture_id . '-' . $l->culture_id) as $cle => $lignesGroupe) {

                $baremesGroupe = $baremes->get($cle);

                if (! $baremesGroupe) {
                    $culturesSansBareme[$lignesGroupe->first()->culture_id] = true;
                    continue;
                }

                $culturesCalculees[$lignesGroupe->first()->culture_id] = true;

                foreach ($baremesGroupe as $bareme) {
                    $dose    = (float) $bareme->dose_par_hectare;
                    $plafond = (float) $bareme->plafond_max_producteur;
                    $unite   = str_replace('/ha', '', (string) $bareme->unite);

                    foreach ($lignesGroupe->groupBy('exploitant_id') as $parcellesProducteur) {

                        $brut        = $parcellesProducteur->sum(fn ($l) => (float) $l->ha * $dose);
                        $estPlafonne = $plafond > 0 && $brut > $plafond;
                        $total       = $estPlafonne ? $plafond : $brut;

                        if ($estPlafonne) {
                            $plafonnes++;
                        }

                        foreach ($parcellesProducteur as $l) {
                            $part = $brut > 0 ? ((float) $l->ha * $dose) / $brut : 0;

                            BesoinIntrant::updateOrCreate(
                                [
                                    'culture_parcelle_id' => $l->cp_id,
                                    'intrant_id'          => $bareme->intrant_id,
                                ],
                                [
                                    'quantitePrevue'     => round($total * $part, 2),
                                    'unite'              => $unite,
                                    'dateEvaluation'     => now(),
                                    'agentEvaluateur_id' => $user->id,
                                    'observations'       => $estPlafonne
                                        ? "Campagne {$campagne->libelle} : plafond producteur appliqué ({$plafond} {$unite})."
                                        : "Campagne {$campagne->libelle} : superficie × dose du barème.",
                                ]
                            );

                            $compteur++;
                        }
                    }
                }
            }
        });

        // 4. Message de fin
        $message = sprintf(
            'Campagne « %s » : %d culture(s) calculée(s), %d besoin(s) mis à jour.',
            $campagne->libelle,
            count($culturesCalculees),
            $compteur
        );

        if ($plafonnes > 0) {
            $message .= " {$plafonnes} producteur(s) limité(s) par le plafond.";
        }

        if ($culturesSansBareme) {
            $noms = Culture::whereIn('idCulture', array_keys($culturesSansBareme))
                ->orderBy('nomCulture')
                ->pluck('nomCulture')
                ->implode(', ');

            $message .= " Cultures ignorées faute de barème actif : {$noms}.";
        }

        return redirect()
            ->route('dpa.besoins.index', array_filter(['culture_id' => $cultureId]))
            ->with('success', $message);
    }

    /** Campagne demandée, sinon la dernière campagne clôturée. */
    private function campagneChoisie(Request $request): ?CampagneRecensement
    {
        if ($request->filled('campagne_id')) {
            return CampagneRecensement::find($request->input('campagne_id'));
        }

        return CampagneRecensement::where('statut', 'cloturee')
            ->orderByDesc('dateFin')
            ->first();
    }

    /** DPA : sa préfecture. Administrateur : préfecture facultative (null = toutes). */
    private function prefectureCible(Request $request): ?int
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $request->filled('prefecture_id') ? (int) $request->input('prefecture_id') : null;
        }

        $prefectureId = $user->prefectureId();

        abort_if(! $prefectureId, 403, 'Aucune préfecture rattachée à votre compte.');

        return (int) $prefectureId;
    }

    /**
     * Superficie utilisée pour le calcul.
     * Référence : superficie de la parcelle (calculée par GPS côté serveur).
     * Si la culture n'occupe qu'une partie de la parcelle (cultures associées),
     * on prend la superficie cultivée, sans jamais dépasser celle de la parcelle.
     */
    private function superficieRetenue(object $ligne): float
    {
        $parcelle = (float) $ligne->ha_parcelle;
        $cultivee = (float) $ligne->ha_cultivee;

        return $cultivee > 0 ? min($cultivee, $parcelle) : $parcelle;
    }

    /**
     * Requête de base : culture de parcelle → parcelle → exploitation → exploitant
     * → ménage → recensement → maison → village → canton → commune.
     *
     * Clé primaire supposée : menages.idMenage (parcelles.idParcelle est confirmée).
     */
    private function parcellesCultivees(?int $prefectureId = null, ?int $campagneId = null)
    {
        return DB::table('cultures_parcelles as cp')
            ->join('parcelles as p', 'p.idParcelle', '=', 'cp.parcelle_id')
            ->join('exploitations as e', 'e.idExploitation', '=', 'p.exploitation_id')
            ->join('exploitants as x', 'x.idExploitant', '=', 'e.exploitant_id')
            ->join('menages as m', 'm.idMenage', '=', 'x.menage_id')
            ->join('recensements as r', 'r.idRecensement', '=', 'm.recensement_id')
            ->join('maisons as ma', 'ma.idMaison', '=', 'r.maison_id')
            ->join('villages as v', 'v.idVillage', '=', 'ma.village_id')
            ->join('cantons as ca', 'ca.idCanton', '=', 'v.canton_id')
            ->join('communes as co', 'co.idCommune', '=', 'ca.commune_id')
            ->where('p.statutParcelle', 'exploitee') // ni jachère, ni parcelle non exploitée
            ->whereIn('r.statut', self::STATUTS_RETENUS)
            ->when($prefectureId, fn ($q) => $q->where('co.prefecture_id', $prefectureId))
            ->when($campagneId, fn ($q) => $q->where('r.campagne_id', $campagneId));
    }
}