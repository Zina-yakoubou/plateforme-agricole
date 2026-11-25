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

    /**
     * Affichage de la liste des besoins pour la préfecture du DPA avec filtres.
     */
    public function index(Request $request)
    {
        $prefectureId = $this->prefectureCible();
        $campagne     = $this->campagneChoisie($request);
        $table        = (new BesoinIntrant)->getTable();

        // Filtre principal pour la liste et les totaux
        $filtrer = fn ($q) => $q
            ->when($campagne, fn ($q) => $q->whereIn(
                $table . '.culture_parcelle_id',
                $this->parcellesCultivees($prefectureId, $campagne->getKey())->select('cp.idCultureParcelle')
            ))
            // Filtre par campagne spécifique depuis le sélecteur
            ->when($request->filled('campagne_id'), function ($q) use ($request) {
                $q->whereIn(
                    'culture_parcelle_id',
                    $this->parcellesCultivees($this->prefectureCible(), $request->campagne_id)->select('cp.idCultureParcelle')
                );
            })
            // Filtre par culture
            ->when($request->filled('culture_id'), fn ($q) => $q->whereHas(
                'cultureParcelle',
                fn ($c) => $c->where('culture_id', $request->culture_id)
            ))
            // Filtre par intrant
            ->when($request->filled('intrant_id'), fn ($q) => $q->where($table . '.intrant_id', $request->intrant_id))
            // Filtre par village
            ->when($request->filled('village_id'), function ($q) use ($request) {
                $q->whereHas('cultureParcelle.parcelle.exploitation.exploitant.menage.recensement.maison', function ($m) use ($request) {
                    $m->where('village_id', $request->village_id);
                });
            })
            // Filtre par producteur (Nom ou Prénom)
            ->when($request->filled('producteur'), function ($q) use ($request) {
                $terme = $request->producteur;
                $q->whereHas('cultureParcelle.parcelle.exploitation.exploitant', function ($ex) use ($terme) {
                    $ex->where('nom', 'like', "%{$terme}%")
                       ->orWhere('prenom', 'like', "%{$terme}%");
                });
            });

        $besoins = $filtrer(BesoinIntrant::with([
            'cultureParcelle.culture',
            'cultureParcelle.parcelle.exploitation.exploitant',
            'intrant',
            'agentEvaluateur',
        ]))->latest('dateEvaluation')->paginate(15)->withQueryString();

        // Consolidation : total par intrant pour les cartes de synthèse
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

        // Récupération des villages associés aux lignes affichées
        $villagesIds = $besoins->pluck('culture_parcelle_id');
        $villages = $villagesIds->isNotEmpty() 
            ? $this->parcellesCultivees($prefectureId)->whereIn('cp.idCultureParcelle', $villagesIds)->pluck('v.nom', 'cp.idCultureParcelle') 
            : collect();

        // Données pour les listes déroulantes des filtres
        $intrants  = Intrant::orderBy('nom')->get();
        $cultures  = Culture::orderBy('nomCulture')->get();
        $campagnes = CampagneRecensement::orderByDesc('dateFin')->get();

        $listeVillages = DB::table('villages')
            ->join('cantons', 'cantons.idCanton', '=', 'villages.canton_id')
            ->join('communes', 'communes.idCommune', '=', 'cantons.commune_id')
            ->where('communes.prefecture_id', $prefectureId)
            ->orderBy('villages.nom')
            ->get(['villages.idVillage', 'villages.nom']);

        return view('dpa.besoins.index', compact(
            'besoins', 'intrants', 'cultures', 'campagne', 'totaux', 'villages', 'campagnes', 'listeVillages'
        ));
    }

    /**
     * Exécute le calcul global des besoins pour la préfecture du DPA.
     */
    public function lancerCalculGlobal(Request $request)
    {
        $request->validate([
            'culture_id'  => ['nullable', 'exists:cultures,idCulture'],
            'campagne_id' => ['nullable', 'exists:campagne_recensements,idCampagne'],
        ]);

        $user         = Auth::user();
        $cultureId    = $request->filled('culture_id') ? (int) $request->input('culture_id') : null;
        $prefectureId = $this->prefectureCible();
        $campagne     = $this->campagneChoisie($request);

        if (! $campagne) {
            return back()->with('error', 'Aucune campagne disponible pour le calcul.');
        }

        // 1. Récupération des parcelles cultivées de la préfecture pour la campagne
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

        // 2. Récupération des barèmes actifs de la préfecture
        $baremes = BaremeAgronomique::where('est_actif', true)
            ->where('prefecture_id', $prefectureId)
            ->when($cultureId, fn ($q) => $q->where('culture_id', $cultureId))
            ->get()
            ->groupBy(fn ($b) => $b->prefecture_id . '-' . $b->culture_id);

        if ($baremes->isEmpty()) {
            return back()->with('error', 'Aucun barème agronomique actif trouvé pour votre préfecture.');
        }

        $culturesSansBareme = [];
        $culturesCalculees  = [];
        $compteur           = 0;
        $plafonnes          = 0;

        // 3. Traitement du calcul par transaction sécurisée
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

        // 4. Message de retour synthétique
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

    /** Détermination de la campagne active ou sélectionnée. */
    private function campagneChoisie(Request $request): ?CampagneRecensement
    {
        if ($request->filled('campagne_id')) {
            return CampagneRecensement::find($request->input('campagne_id'));
        }

        return CampagneRecensement::where('statut', 'cloturee')
            ->orderByDesc('dateFin')
            ->first() ?? CampagneRecensement::orderByDesc('idCampagne')->first();
    }

    /** Sécurisation stricte de la préfecture du DPA connecté.[cite: 2] */
    private function prefectureCible(): int
    {
        $user = Auth::user();
        $prefectureId = $user->prefectureId();

        abort_if(! $prefectureId, 403, 'Aucune préfecture rattachée à votre compte DPA.');

        return (int) $prefectureId;
    }

    /** Calcul de la superficie utile (surface cultivée avec repli sur la surface parcelle).[cite: 2] */
    private function superficieRetenue(object $ligne): float
    {
        $parcelle = (float) $ligne->ha_parcelle;
        $cultivee = (float) $ligne->ha_cultivee;

        return $cultivee > 0 ? min($cultivee, $parcelle) : $parcelle;
    }

    /** Requête de jointure complète pour remonter jusqu'au village et à la préfecture du DPA.[cite: 2] */
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
            ->where('p.statutParcelle', 'exploitee')
            ->whereIn('r.statut', self::STATUTS_RETENUS)
            ->when($prefectureId, fn ($q) => $q->where('co.prefecture_id', $prefectureId))
            ->when($campagneId, fn ($q) => $q->where('r.campagne_id', $campagneId));
    }
}