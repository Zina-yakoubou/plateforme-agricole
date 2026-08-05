<?php

namespace Database\Seeders;

use App\Models\Commune;
use App\Models\Prefecture;
use Illuminate\Database\Seeder;

class CommuneSeeder extends Seeder
{
    public function run(): void
    {
        // Récupération de la préfecture de Mô
        $prefectureMo = Prefecture::where('code', 'MO')->first();

        if (!$prefectureMo) {
            return;
        }

        $communes = [
            [
                'nom' => 'Commune Mô 1',
                'code' => 'MO1',
            ],
            [
                'nom' => 'Commune Mô 2',
                'code' => 'MO2',
            ],
        ];

        foreach ($communes as $commune) {

            Commune::updateOrCreate(
                [
                    'code' => $commune['code'],
                ],
                [
                    'nom' => $commune['nom'],
                    'prefecture_id' => $prefectureMo->idPrefecture,
                ]
            );
        }
    }
}