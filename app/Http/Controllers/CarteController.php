<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CampagneRecensement;
use App\Models\Prefecture;
use App\Models\Recensement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CarteController extends Controller
{
    private const LIBELLES = [
        'brouillon'  => 'Brouillon',
        'en_attente' => 'En attente',
        'en_cours'   => 'En cours',
        'termine'    => 'Terminé',
        'valide'     => 'Validé',
    ];

    // Limites géographiques du Togo
    private const TOGO = ['latMin' => 6.0, 'latMax' => 11.2, 'lngMin' => -0.2, 'lngMax' => 1.9];

    public function index(): View
    {
        abort_unless(Auth::user()?->isAdmin(), 403);

        $cultures = $this->donneesCultures();

        $recensements = Recensement::query()
            ->with(['campagne', 'maison.village.canton.commune', 'agent'])
            ->whereHas('maison')
            ->latest('idRecensement')
            ->limit(5000)
            ->get()
            ->unique(fn ($r) => $r->maison->getKey() . '-' . ($r->campagne?->getKey() ?? '0'))
            ->values();

        $diag = ['total' => $recensements->count(), 'maison' => 0, 'village' => 0, 'aucune' => 0];

        // Tous les recensements sont conservés, avec ou sans coordonnées
        $points = $recensements->map(function ($r) use ($cultures, &$diag) {
            $coord = $this->coordonnees($r->maison);
            $diag[$coord['source'] ?? 'aucune']++;

            $commune = $r->maison->village?->canton?->commune;

            $cs = collect($cultures['parRecensement'][$r->getKey()] ?? [])
                ->map(fn ($c, $id) => [
                    'id'        => (string) $id,
                    'nom'       => $c['nom'],
                    'ha'        => round($c['ha'], 2),
                    'parcelles' => $c['parcelles'],
                ])->values();

            return [
                'maison'     => (string) $r->maison->getKey(),
                'lat'        => $coord['lat'] ?? null,
                'lng'        => $coord['lng'] ?? null,
                'source'     => $coord['source'] ?? 'aucune',
                'statut'     => (string) $r->statut,
                'statutLb'   => self::LIBELLES[$r->statut] ?? ucfirst(str_replace('_', ' ', (string) $r->statut)),
                'village'    => $r->maison->village?->nom,
                'commune'    => $commune?->nom,
                'prefecture' => (string) ($commune?->prefecture_id ?? ''),
                'chef'       => $r->maison->nomChef,
                'numero'     => $r->maison->numeroMaison,
                'agent'      => $r->agent?->name,
                'campagneId' => (string) ($r->campagne?->getKey() ?? ''),
                'campagne'   => $r->campagne?->libelle,
                'cultures'   => $cs,
                'cultureIds' => $cs->pluck('id')->all(),
                'url'        => route('admin.recensements.show', $r),
            ];
        })->values();

        return view('admin.carte.index', [
            'points'        => $points,
            'diag'          => $diag,
            'statuts'       => self::LIBELLES,
            'prefectures'   => Prefecture::orderBy('nom')->get(),
            'campagnes'     => CampagneRecensement::orderByDesc('idCampagne')->get(),
            'listeCultures' => $this->listeCultures($points),
            'culturesOk'    => $cultures['ok'],
            'culturesDiag'  => $cultures['diag'],
        ]);
    }

    /* ------------------------------------------------------------------
     |  Coordonnées : GPS de la maison, sinon centre du village
     ------------------------------------------------------------------ */
    private function coordonnees($maison): ?array
    {
        $candidats = [
            ['maison',  $maison->latitude,           $maison->longitude],
            ['village', $maison->village?->latitude, $maison->village?->longitude],
        ];

        foreach ($candidats as [$source, $lat, $lng]) {
            if ($lat === null || $lng === null || $lat === '' || $lng === '') {
                continue;
            }

            $lat = (float) str_replace(',', '.', (string) $lat);
            $lng = (float) str_replace(',', '.', (string) $lng);

            if ($this->dansTogo($lat, $lng)) {
                return ['lat' => $lat, 'lng' => $lng, 'source' => $source];
            }

            // Latitude et longitude inversées
            if ($this->dansTogo($lng, $lat)) {
                return ['lat' => $lng, 'lng' => $lat, 'source' => $source];
            }
        }

        return null;
    }

    private function dansTogo(float $lat, float $lng): bool
    {
        $t = self::TOGO;

        return $lat >= $t['latMin'] && $lat <= $t['latMax']
            && $lng >= $t['lngMin'] && $lng <= $t['lngMax'];
    }

    /* ------------------------------------------------------------------
     |  Liste des cultures proposées dans le filtre (toujours remplie)
     ------------------------------------------------------------------ */
    private function listeCultures($points): array
    {
        try {
            $cul = $this->modeleInfo('App\\Models\\Culture');

            if ($cul) {
                $col = $this->colonne($cul[0], ['nomCulture', 'libelle', 'nom']) ?? $cul[1];

                $liste = DB::table($cul[0])->orderBy($col)->pluck($col, $cul[1])
                    ->mapWithKeys(fn ($nom, $id) => [(string) $id => $nom])
                    ->all();

                if ($liste) {
                    return $liste;
                }
            }
        } catch (\Throwable $e) {
            // on retombe sur les cultures trouvées dans les points
        }

        return $points->flatMap(fn ($p) => $p['cultures'])
            ->unique('id')->sortBy('nom')
            ->mapWithKeys(fn ($c) => [$c['id'] => $c['nom']])->all();
    }

    /* ------------------------------------------------------------------
     |  Cultures pratiquées par recensement
     |  Chemin : culture de parcelle → parcelle → (exploitation) → exploitant
     |           → ménage → recensement
     |  Les noms de tables et de colonnes sont détectés automatiquement.
     ------------------------------------------------------------------ */
    private function donneesCultures(): array
    {
        $vide = ['ok' => false, 'diag' => '', 'parRecensement' => []];

        try {
            $ns = 'App\\Models\\';

            $cp  = $this->modeleInfo($ns . 'CultureParcelle');
            $par = $this->modeleInfo($ns . 'Parcelle');
            $cul = $this->modeleInfo($ns . 'Culture');
            $exn = $this->modeleInfo($ns . 'Exploitant');
            $men = $this->modeleInfo($ns . 'Menage');
            $rec = $this->modeleInfo($ns . 'Recensement');
            $exp = $this->modeleInfo($ns . 'Exploitation');

            foreach (['CultureParcelle' => $cp, 'Parcelle' => $par, 'Culture' => $cul,
                      'Exploitant' => $exn, 'Menage' => $men, 'Recensement' => $rec] as $nom => $info) {
                if (!$info) {
                    return ['diag' => "Modèle introuvable : App\\Models\\{$nom}."] + $vide;
                }
            }

            $cpParcelle = $this->colonne($cp[0], ['parcelle_id', 'idParcelle']);
            $cpCulture  = $this->colonne($cp[0], ['culture_id', 'idCulture']);
            $exnMenage  = $this->colonne($exn[0], ['menage_id', 'idMenage']);
            $menRec     = $this->colonne($men[0], ['recensement_id', 'idRecensement']);

            if (!$cpParcelle) return ['diag' => "Colonne parcelle introuvable dans {$cp[0]}."] + $vide;
            if (!$cpCulture)  return ['diag' => "Colonne culture introuvable dans {$cp[0]}."] + $vide;
            if (!$exnMenage)  return ['diag' => "Colonne ménage introuvable dans {$exn[0]}."] + $vide;
            if (!$menRec)     return ['diag' => "Colonne recensement introuvable dans {$men[0]}."] + $vide;

            $q = DB::table($cp[0] . ' as cp')
                ->join($par[0] . ' as p', 'p.' . $par[1], '=', 'cp.' . $cpParcelle)
                ->join($cul[0] . ' as c', 'c.' . $cul[1], '=', 'cp.' . $cpCulture);

            // Lien parcelle → exploitant
            $parExploitation = $exp ? $this->colonne($par[0], ['exploitation_id', 'idExploitation']) : null;
            $parExploitant   = $this->colonne($par[0], ['exploitant_id', 'idExploitant']);
            $lie = false;

            if ($parExploitation && $exp) {
                $expExploitant   = $this->colonne($exp[0], ['exploitant_id', 'idExploitant']);
                $exnExploitation = $this->colonne($exn[0], ['exploitation_id', 'idExploitation']);

                if ($expExploitant) {
                    $q->join($exp[0] . ' as e', 'e.' . $exp[1], '=', 'p.' . $parExploitation)
                      ->join($exn[0] . ' as x', 'x.' . $exn[1], '=', 'e.' . $expExploitant);
                    $lie = true;
                } elseif ($exnExploitation) {
                    $q->join($exp[0] . ' as e', 'e.' . $exp[1], '=', 'p.' . $parExploitation)
                      ->join($exn[0] . ' as x', 'x.' . $exnExploitation, '=', 'e.' . $exp[1]);
                    $lie = true;
                }
            }

            if (!$lie && $parExploitant) {
                $q->join($exn[0] . ' as x', 'x.' . $exn[1], '=', 'p.' . $parExploitant);
                $lie = true;
            }

            if (!$lie) {
                return ['diag' => "Lien parcelle → exploitant introuvable ({$par[0]})."] + $vide;
            }

            $q->join($men[0] . ' as m', 'm.' . $men[1], '=', 'x.' . $exnMenage)
              ->join($rec[0] . ' as r', 'r.' . $rec[1], '=', 'm.' . $menRec);

            $colNom = $this->colonne($cul[0], ['nomCulture', 'libelle', 'nom']) ?? $cul[1];

            // Superficie cultivée, sinon superficie de la parcelle
            $haCp = $this->colonne($cp[0], ['superficieCultivee', 'superficie_cultivee', 'superficie']);
            $haP  = $this->colonne($par[0], ['superficie']);

            if ($haCp && $haP) {
                $expressionHa = "COALESCE(NULLIF(cp.`{$haCp}`, 0), p.`{$haP}`, 0)";
            } elseif ($haCp) {
                $expressionHa = "COALESCE(cp.`{$haCp}`, 0)";
            } elseif ($haP) {
                $expressionHa = "COALESCE(p.`{$haP}`, 0)";
            } else {
                $expressionHa = '0';
            }

            if (DB::connection()->getDriverName() === 'sqlite') {
                $expressionHa = str_replace('`', '"', $expressionHa);
            }

            $lignes = $q->selectRaw(
                'r.' . $rec[1] . ' as recensement_id, c.' . $cul[1] . ' as culture_id, '
                . 'c.' . $colNom . ' as nom, ' . $expressionHa . ' as ha'
            )->get();

            $parRecensement = [];

            foreach ($lignes as $l) {
                $rid = $l->recensement_id;
                $cid = $l->culture_id;

                if (!isset($parRecensement[$rid][$cid])) {
                    $parRecensement[$rid][$cid] = ['nom' => $l->nom, 'ha' => 0.0, 'parcelles' => 0];
                }

                $parRecensement[$rid][$cid]['ha']        += (float) $l->ha;
                $parRecensement[$rid][$cid]['parcelles'] += 1;
            }

            return ['ok' => true, 'diag' => '', 'parRecensement' => $parRecensement];

        } catch (\Throwable $e) {
            return ['diag' => 'Erreur : ' . Str::limit($e->getMessage(), 220)] + $vide;
        }
    }

    /** [table, clé primaire] d'un modèle, ou null s'il n'existe pas. */
    private function modeleInfo(string $classe): ?array
    {
        if (!class_exists($classe)) {
            return null;
        }

        $m = new $classe;

        return [$m->getTable(), $m->getKeyName()];
    }

    /** Première colonne existante parmi les candidates. */
    private function colonne(string $table, array $candidats): ?string
    {
        foreach ($candidats as $c) {
            if (Schema::hasColumn($table, $c)) {
                return $c;
            }
        }

        return null;
    }
}