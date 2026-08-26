<?php

namespace Database\Seeders;

use App\Models\Structure;
use Illuminate\Database\Seeder;

class StructureSeeder extends Seeder
{
    /**
     * Créer les structures institutionnelles
     * utilisées par SIRA-Mô / SIRA national.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. NIVEAU NATIONAL
        |--------------------------------------------------------------------------
        */

        $ministere = Structure::updateOrCreate(
            [
                'codeStructure' => 'STR-NAT-MAEH',
            ],
            [
                'nom' => 'Ministère de l’Agriculture, de l’Élevage et du Développement Rural',
                'niveau' => 'national',
                'type' => 'ministere',
                'structure_parent_id' => null,
                'statut' => true,
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | 2. DIRECTION NATIONALE
        |--------------------------------------------------------------------------
        */

        $directionNationale = Structure::updateOrCreate(
            [
                'codeStructure' => 'STR-NAT-DPA',
            ],
            [
                'nom' => 'Direction nationale de la planification agricole',
                'niveau' => 'national',
                'type' => 'direction_nationale',
                'structure_parent_id' => $ministere->idStructure,
                'statut' => true,
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | 3. DIRECTIONS RÉGIONALES
        |--------------------------------------------------------------------------
        |
        | Ces structures pourront ensuite être reliées
        | aux régions correspondantes si nécessaire.
        |
        */

        $regions = [
            [
                'code' => 'STR-REG-MAR',
                'nom' => 'Direction régionale agricole Maritime',
            ],
            [
                'code' => 'STR-REG-PLT',
                'nom' => 'Direction régionale agricole des Plateaux',
            ],
            [
                'code' => 'STR-REG-CEN',
                'nom' => 'Direction régionale agricole Centrale',
            ],
            [
                'code' => 'STR-REG-KAR',
                'nom' => 'Direction régionale agricole de la Kara',
            ],
            [
                'code' => 'STR-REG-SAV',
                'nom' => 'Direction régionale agricole des Savanes',
            ],
        ];


        foreach ($regions as $region) {

            Structure::updateOrCreate(
                [
                    'codeStructure' => $region['code'],
                ],
                [
                    'nom' => $region['nom'],
                    'niveau' => 'regional',
                    'type' => 'direction_regionale',
                    'structure_parent_id' => $directionNationale->idStructure,
                    'statut' => true,
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 4. STRUCTURE PRÉFECTORALE DE MÔ
        |--------------------------------------------------------------------------
        |
        | Pour ton cas d'étude actuel.
        |
        */

        $structureMaritime = Structure::where(
            'codeStructure',
            'STR-REG-MAR'
        )->first();


        Structure::updateOrCreate(
            [
                'codeStructure' => 'STR-PREF-MO',
            ],
            [
                'nom' => 'Direction préfectorale agricole de Mô',
                'niveau' => 'prefectoral',
                'type' => 'direction_prefectorale',
                'structure_parent_id' => $structureMaritime?->idStructure,
                'statut' => true,
            ]
        );
    }
}