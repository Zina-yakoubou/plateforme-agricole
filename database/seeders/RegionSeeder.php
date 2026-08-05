<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = [
            [
                'nom' => 'Région Maritime',
                'code' => 'MAR',
            ],
            [
                'nom' => 'Région des Plateaux',
                'code' => 'PLA',
            ],
            [
                'nom' => 'Région Centrale',
                'code' => 'CEN',
            ],
            [
                'nom' => 'Région de la Kara',
                'code' => 'KAR',
            ],
            [
                'nom' => 'Région des Savanes',
                'code' => 'SAV',
            ],
        ];

        foreach ($regions as $region) {
            Region::updateOrCreate(
                ['code' => $region['code']],
                $region
            );
        }
    }
}