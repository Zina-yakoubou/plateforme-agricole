<?php

namespace Database\Seeders;

use App\Models\Canton;
use App\Models\Commune;
use Illuminate\Database\Seeder;

class CantonSeeder extends Seeder
{
    public function run(): void
    {
        $mo1 = Commune::where('code', 'MO1')->first();
        $mo2 = Commune::where('code', 'MO2')->first();

        if (!$mo1 || !$mo2) {
            return;
        }

        $cantons = [

            // Commune Mô 1
            [
                'nom' => 'Boulohou',
                'code' => 'BOU',
                'commune' => 'MO1',
            ],
            [
                'nom' => 'Djarkpanga',
                'code' => 'DJA',
                'commune' => 'MO1',
            ],
            [
                'nom' => 'Kadjigbara',
                'code' => 'KAD',
                'commune' => 'MO1',
            ],

            // Commune Mô 2
            [
                'nom' => 'Tindjassi',
                'code' => 'TIN',
                'commune' => 'MO2',
            ],
            [
                'nom' => 'Saïboudè',
                'code' => 'SAI',
                'commune' => 'MO2',
            ],

        ];

        foreach ($cantons as $canton) {

            $commune = $canton['commune'] == 'MO1'
                ? $mo1
                : $mo2;

            Canton::updateOrCreate(
                [
                    'code' => $canton['code'],
                ],
                [
                    'nom' => $canton['nom'],
                    'commune_id' => $commune->idCommune,
                ]
            );
        }
    }
}