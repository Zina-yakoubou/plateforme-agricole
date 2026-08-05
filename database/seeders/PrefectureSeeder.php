<?php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\Prefecture;
use Illuminate\Database\Seeder;

class PrefectureSeeder extends Seeder
{
    public function run(): void
    {
        $prefectures = [

            // Région Maritime
            [
                'nom' => 'Golfe',
                'code' => 'GOL',
                'region' => 'MAR',
            ],
            [
                'nom' => 'Lacs',
                'code' => 'LAC',
                'region' => 'MAR',
            ],
            [
                'nom' => 'Vo',
                'code' => 'VO',
                'region' => 'MAR',
            ],

            // Région des Plateaux
            [
                'nom' => 'Ogou',
                'code' => 'OGO',
                'region' => 'PLA',
            ],
            [
                'nom' => 'Kloto',
                'code' => 'KLO',
                'region' => 'PLA',
            ],
            [
                'nom' => 'Atakpamé',
                'code' => 'ATA',
                'region' => 'PLA',
            ],

            // Région Centrale
            [
                'nom' => 'Tchaoudjo',
                'code' => 'TCH',
                'region' => 'CEN',
            ],
            [
                'nom' => 'Mô',
                'code' => 'MO',
                'region' => 'CEN',
            ],
            [
                'nom' => 'Sotouboua',
                'code' => 'SOT',
                'region' => 'CEN',
            ],
            [
                'nom' => 'Tchamba',
                'code' => 'TCHB',
                'region' => 'CEN',
            ],
            [
                'nom' => 'Blitta',
                'code' => 'BLI',
                'region' => 'CEN',
            ],

            // Région de la Kara
            [
                'nom' => 'Kozah',
                'code' => 'KOZ',
                'region' => 'KAR',
            ],
            [
                'nom' => 'Bassar',
                'code' => 'BAS',
                'region' => 'KAR',
            ],

            // Région des Savanes
            [
                'nom' => 'Tône',
                'code' => 'TON',
                'region' => 'SAV',
            ],
            [
                'nom' => 'Oti',
                'code' => 'OTI',
                'region' => 'SAV',
            ],
        ];


        foreach ($prefectures as $prefecture) {

            $region = Region::where('code', $prefecture['region'])->first();

            Prefecture::updateOrCreate(
                [
                    'code' => $prefecture['code']
                ],
                [
                    'nom' => $prefecture['nom'],
                    'region_id' => $region->idRegion,
                ]
            );
        }
    }
}