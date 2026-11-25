<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CollecteIntrantsSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('referentiel_culture_intrants')->count() === 0) {
            $this->command->error('Référentiel vide : lancez CultureIntrantSeeder avant.');
            return;
        }

        // Dose par hectare plausible selon le type d'intrant : [min, max]
        $plages = [
            'engrais'          => [50, 150],    // kg/ha
            'fumure_organique' => [1000, 3000], // kg/ha
            'herbicide'        => [1, 3],       // L/ha
            'insecticide'      => [0.5, 1.5],   // L/ha
            'fongicide'        => [1, 2],       // L/ha
            'semence'          => [20, 40],     // kg/ha (x10 si l'unité est le gramme)
            'autre'            => [1, 5],
        ];

        $nb = 0;

        $cps = DB::table('cultures_parcelles')
            ->get(['idCultureParcelle', 'culture_id', 'superficieCultivee']);

        DB::transaction(function () use ($cps, $plages, &$nb) {
            foreach ($cps as $cp) {
                if (DB::table('culture_intrants')->where('culture_parcelle_id', $cp->idCultureParcelle)->exists()) {
                    continue;
                }

                // Uniquement des intrants autorisés par le référentiel de la culture
                $intrants = DB::table('referentiel_culture_intrants as r')
                    ->join('intrants as i', 'i.idIntrant', '=', 'r.intrant_id')
                    ->where('r.culture_id', $cp->culture_id)
                    ->inRandomOrder()
                    ->limit(3)
                    ->get(['i.idIntrant', 'i.type', 'i.unite']);

                foreach ($intrants as $intrant) {
                    [$min, $max] = $plages[$intrant->type] ?? $plages['autre'];

                    $dose = $min + (mt_rand() / mt_getrandmax()) * ($max - $min);

                    if ($intrant->type === 'semence' && $intrant->unite === 'g') {
                        $dose *= 10; // petites graines (tomate, chou...) en grammes
                    }

                    DB::table('culture_intrants')->insert([
                        'culture_parcelle_id' => $cp->idCultureParcelle,
                        'intrant_id'          => $intrant->idIntrant,
                        'quantite'            => round($dose * $cp->superficieCultivee, 2),
                        'nombreApplications'  => rand(1, 3),
                        'dateApplication'     => now()->subDays(rand(10, 60))->toDateString(),
                        'created_at'          => now(),
                        'updated_at'          => now(),
                    ]);
                    $nb++;
                }
            }
        });

        $this->command->info("Intrants collectés : $nb");
    }
}