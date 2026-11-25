<?php

namespace App\Services;

use App\Models\BaremeAgronomique;
use App\Models\Exploitant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EvaluationBesoinService
{
    /** [prefecture_id => barèmes actifs groupés par culture] */
    private array $cacheBaremes = [];

    /** [culture_id => nombre de cultures ignorées faute de barème] */
    private array $culturesSansBareme = [];

    private int $exploitantsSansPrefecture = 0;

    /**
     * Calcule les besoins d'un exploitant avec les barèmes de la préfecture.
     * $campagneId est la campagne source (les exploitants ont déjà été filtrés dessus) ;
     * besoin_intrants n'a pas de colonne campagne.
     */
    public function calculerPourExploitant(Exploitant $exploitant, int $campagneId, ?int $prefectureId = null): int
    {
        $prefectureId ??= $exploitant->prefectureId();

        if (! $prefectureId) {
            $this->exploitantsSansPrefecture++;
            return 0;
        }

        $baremes = $this->baremesPourPrefecture($prefectureId);

        $cultures = DB::table('cultures_parcelles as cp')
            ->join('parcelles as p', 'p.idParcelle', '=', 'cp.parcelle_id')
            ->join('exploitations as e', 'e.idExploitation', '=', 'p.exploitation_id')
            ->where('e.exploitant_id', $exploitant->getKey())
            ->select('cp.idCultureParcelle', 'cp.culture_id', 'cp.superficieCultivee', 'p.superficie')
            ->get();

        $lignes = [];
        $cumul = [];
        $plafonds = [];

        foreach ($cultures as $cp) {
            $baremesCulture = $baremes->get($cp->culture_id);

            if (! $baremesCulture) {
                $this->culturesSansBareme[$cp->culture_id] =
                    ($this->culturesSansBareme[$cp->culture_id] ?? 0) + 1;
                continue;
            }

            // On suppose des hectares ; la surface de la culture prime sur celle de la parcelle
            $surface = (float) ($cp->superficieCultivee ?: $cp->superficie);

            foreach ($baremesCulture as $b) {
                $quantite = $surface * (float) $b->dose_par_hectare;

                $lignes[] = [
                    'culture_parcelle_id' => $cp->idCultureParcelle,
                    'intrant_id'          => $b->intrant_id,
                    'quantite'            => $quantite,
                    'unite'               => preg_replace('#/ha$#i', '', (string) $b->unite),
                ];

                $cumul[$b->intrant_id] = ($cumul[$b->intrant_id] ?? 0) + $quantite;

                if ($b->plafond_max_producteur !== null) {
                    $p = (float) $b->plafond_max_producteur;
                    $plafonds[$b->intrant_id] = isset($plafonds[$b->intrant_id])
                        ? min($plafonds[$b->intrant_id], $p)
                        : $p;
                }
            }
        }

        $nb = 0;

        DB::transaction(function () use ($lignes, $cumul, $plafonds, &$nb) {
            foreach ($lignes as $l) {
                $quantite = $l['quantite'];
                $total = $cumul[$l['intrant_id']];
                $plafond = $plafonds[$l['intrant_id']] ?? null;

                // Plafond appliqué au total du producteur, réparti proportionnellement
                if ($plafond !== null && $total > $plafond && $total > 0) {
                    $quantite = $plafond * ($l['quantite'] / $total);
                }

                $cle = [
                    'culture_parcelle_id' => $l['culture_parcelle_id'],
                    'intrant_id'          => $l['intrant_id'],
                ];

                $valeurs = [
                    'quantitePrevue'     => round($quantite, 2),
                    'unite'              => $l['unite'],
                    'dateEvaluation'     => now(),
                    'agentEvaluateur_id' => auth()->id(),
                    'updated_at'         => now(),
                ];

                if (DB::table('besoin_intrants')->where($cle)->exists()) {
                    DB::table('besoin_intrants')->where($cle)->update($valeurs);
                } else {
                    DB::table('besoin_intrants')->insert($cle + $valeurs + ['created_at' => now()]);
                }

                $nb++;
            }
        });

        return $nb;
    }

    public function culturesSansBareme(): array
    {
        return $this->culturesSansBareme;
    }

    public function exploitantsSansPrefecture(): int
    {
        return $this->exploitantsSansPrefecture;
    }

    private function baremesPourPrefecture(int $prefectureId): Collection
    {
        return $this->cacheBaremes[$prefectureId] ??= BaremeAgronomique::query()
            ->where('prefecture_id', $prefectureId)
            ->where('est_actif', true)
            ->get()
            ->groupBy('culture_id');
    }
}