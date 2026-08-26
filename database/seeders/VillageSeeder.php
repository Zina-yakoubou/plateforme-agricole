<?php

namespace Database\Seeders;

use App\Models\Canton;
use App\Models\Village;
use Illuminate\Database\Seeder;

class VillageSeeder extends Seeder
{
    /**
     * Création des villages de la Préfecture de Mô.
     */
    public function run(): void
    {
        $villages = [

            /*
            |--------------------------------------------------------------------------
            | CANTON DE BOULOHOU
            |--------------------------------------------------------------------------
            */

            [
                'nom' => 'Malouwada',
                'code' => 'MAL',
                'canton' => 'BOU',
            ],
            [
                'nom' => 'Boulohou Centre',
                'code' => 'BOU-C',
                'canton' => 'BOU',
            ],
            [
                'nom' => 'Kissiti',
                'code' => 'KIS',
                'canton' => 'BOU',
            ],


            /*
            |--------------------------------------------------------------------------
            | CANTON DE DJARKPANGA
            |--------------------------------------------------------------------------
            */

            [
                'nom' => 'Djarkpanga Centre',
                'code' => 'DJA-C',
                'canton' => 'DJA',
            ],
            [
                'nom' => 'Kagnigbara',
                'code' => 'KAG',
                'canton' => 'DJA',
            ],
            [
                'nom' => 'Kouma',
                'code' => 'KOU',
                'canton' => 'DJA',
            ],


            /*
            |--------------------------------------------------------------------------
            | CANTON DE KADJIGBARA
            |--------------------------------------------------------------------------
            */

            [
                'nom' => 'Kadjigbara Centre',
                'code' => 'KAD-C',
                'canton' => 'KAD',
            ],
            [
                'nom' => 'Ataloté',
                'code' => 'ATA',
                'canton' => 'KAD',
            ],
            [
                'nom' => 'Tchifama',
                'code' => 'TCH',
                'canton' => 'KAD',
            ],


            /*
            |--------------------------------------------------------------------------
            | CANTON DE TINDJASSI
            |--------------------------------------------------------------------------
            */

            [
                'nom' => 'Tindjassi Centre',
                'code' => 'TIN-C',
                'canton' => 'TIN',
            ],
            [
                'nom' => 'Koudjo',
                'code' => 'KOU-T',
                'canton' => 'TIN',
            ],
            [
                'nom' => 'Kpélé',
                'code' => 'KPE-T',
                'canton' => 'TIN',
            ],


            /*
            |--------------------------------------------------------------------------
            | CANTON DE SAÏBOUDÈ
            |--------------------------------------------------------------------------
            */

            [
                'nom' => 'Saïboudè Centre',
                'code' => 'SAI-C',
                'canton' => 'SAI',
            ],
            [
                'nom' => 'Nadjoundi',
                'code' => 'NAD',
                'canton' => 'SAI',
            ],
            [
                'nom' => 'Kpindi',
                'code' => 'KPI',
                'canton' => 'SAI',
            ],
        ];


        foreach ($villages as $village) {

            $canton = Canton::where(
                'code',
                $village['canton']
            )->first();

            /*
            |--------------------------------------------------------------------------
            | Si le canton n'existe pas, on ignore le village.
            |--------------------------------------------------------------------------
            */

            if (!$canton) {
                continue;
            }


            Village::updateOrCreate(
                [
                    'code' => $village['code'],
                ],
                [
                    'nom' => $village['nom'],
                    'canton_id' => $canton->idCanton,
                ]
            );
        }
    }
}

