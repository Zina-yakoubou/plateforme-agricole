<?php

namespace Database\Seeders;

use App\Models\Affectation;
use App\Models\CampagneRecensement;
use App\Models\Canton;
use App\Models\Prefecture;
use App\Models\User;
use App\Models\Village;
use Illuminate\Database\Seeder;

class AffectationSeeder extends Seeder
{
    /**
     * Création des affectations de démonstration.
     *
     * Logique :
     *
     * ADMIN
     *   ↓
     * DIRECTEUR PRÉFECTORAL
     *   ↓
     * PRÉFECTURE DE MÔ
     *   ↓
     * CANTON
     *   ↓
     * VILLAGES
     *   ↓
     * AGENT RECENSEUR
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. CAMPAGNE ACTIVE
        |--------------------------------------------------------------------------
        */

        $campagne = CampagneRecensement::where(
            'statut',
            'active'
        )->first();

        if (!$campagne) {
            $this->command->error(
                'Aucune campagne active trouvée.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | 2. DIRECTEUR PRÉFECTORAL
        |--------------------------------------------------------------------------
        */

        $directeur = User::where(
            'login',
            'directeur01'
        )->first();

        if (!$directeur) {
            $this->command->error(
                'Le directeur directeur01 est introuvable.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | 3. AGENT RECENSEUR
        |--------------------------------------------------------------------------
        */

        $agent = User::where(
            'login',
            'agent001'
        )->first();

        if (!$agent) {
            $this->command->error(
                'L’agent agent001 est introuvable.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | 4. PRÉFECTURE DE MÔ
        |--------------------------------------------------------------------------
        */

        $prefecture = Prefecture::where(
            'nom',
            'Mô'
        )->first();

        if (!$prefecture) {
            $this->command->error(
                'La Préfecture de Mô est introuvable.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | 5. AFFECTATION DU DIRECTEUR À LA PRÉFECTURE DE MÔ
        |--------------------------------------------------------------------------
        */

        Affectation::updateOrCreate(
            [
                'user_id' => $directeur->id,
                'campagne_id' => $campagne->idCampagne,
                'prefecture_id' => $prefecture->idPrefecture,
                'canton_id' => null,
                'village_id' => null,
            ],
            [
                'reference' => 'AFF-DIR-MO-001',
                'dateDebut' => $campagne->dateDebut,
                'dateFin' => $campagne->dateFin,
                'statut' => 'ACTIVE',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | 6. CANTON CHOISI POUR L'AGENT
        |--------------------------------------------------------------------------
        |
        | Pour notre démonstration, le directeur affecte l'agent
        | au canton de Djarkpanga.
        |
        */

        $canton = Canton::where(
            'code',
            'DJA'
        )
        ->whereHas(
            'commune',
            function ($query) use ($prefecture) {

                $query->where(
                    'prefecture_id',
                    $prefecture->idPrefecture
                );

            }
        )
        ->first();


        if (!$canton) {
            $this->command->error(
                'Le canton de Djarkpanga est introuvable dans la Préfecture de Mô.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | 7. VILLAGES DU CANTON
        |--------------------------------------------------------------------------
        |
        | On récupère plusieurs villages appartenant au canton.
        |
        */

        $villages = Village::where(
            'canton_id',
            $canton->idCanton
        )
        ->orderBy('idVillage')
        ->take(3)
        ->get();


        if ($villages->isEmpty()) {
            $this->command->error(
                'Aucun village trouvé dans le canton de Djarkpanga.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | 8. AFFECTATION DES VILLAGES À L'AGENT
        |--------------------------------------------------------------------------
        */

        foreach ($villages as $index => $village) {

            $numero = str_pad(
                $index + 1,
                3,
                '0',
                STR_PAD_LEFT
            );


            Affectation::updateOrCreate(
                [
                    'user_id' => $agent->id,
                    'campagne_id' => $campagne->idCampagne,
                    'village_id' => $village->idVillage,
                ],
                [
                    'reference' => "AFF-AGT-MO-{$numero}",

                    'dateDebut' => $campagne->dateDebut,

                    'dateFin' => $campagne->dateFin,

                    'statut' => 'ACTIVE',

                    'prefecture_id' => $prefecture->idPrefecture,

                    'canton_id' => $canton->idCanton,
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 9. MESSAGE DE CONFIRMATION
        |--------------------------------------------------------------------------
        */

        $this->command->info(
            'Affectations créées avec succès.'
        );

        $this->command->info(
            "Directeur : {$directeur->name} → Préfecture de Mô"
        );

        $this->command->info(
            "Agent : {$agent->name} → Canton {$canton->nom}"
        );

        $this->command->info(
            "{$villages->count()} village(s) affecté(s) à l'agent."
        );
    }
}

