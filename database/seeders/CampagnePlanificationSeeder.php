<?php

namespace Database\Seeders;

use App\Models\CampagnePlanification;
use App\Models\CampagneRecensement;
use App\Models\User;
use Illuminate\Database\Seeder;

class CampagnePlanificationSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | RESPONSABLES DE PLANIFICATION
        |--------------------------------------------------------------------------
        |
        | L'administrateur ou un DPA peut être responsable
        | de la planification générale.
        |
        */

        $responsables = User::query()
            ->whereIn('role_id', ['R01', 'R02'])
            ->where('statut', true)
            ->get();

        if ($responsables->isEmpty()) {
            $this->command->warn(
                'Aucun administrateur ou DPA actif trouvé.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | CAMPAGNES
        |--------------------------------------------------------------------------
        */

        $campagnes = CampagneRecensement::query()
            ->orderBy('dateDebut')
            ->get();

        if ($campagnes->isEmpty()) {
            $this->command->warn(
                'Aucune campagne de recensement trouvée.'
            );

            return;
        }

        foreach ($campagnes as $campagne) {

            /*
            |--------------------------------------------------------------------------
            | RESPONSABLE
            |--------------------------------------------------------------------------
            |
            | On privilégie un DPA.
            | S'il n'y en a pas, on prend l'administrateur disponible.
            |
            */

            $responsable = $responsables
                ->firstWhere('role_id', 'R02')
                ?? $responsables->first();

            CampagnePlanification::updateOrCreate(
                [
                    'campagne_id' => $campagne->getKey(),
                ],
                [
                    'planifie_par' => $responsable->getKey(),

                    'statut' => 'planifiee',

                    'observations' =>
                        'Planification générale de la campagne « '
                        . $campagne->libelle
                        . ' ». '
                        . 'Elle définit le cadre commun de préparation, '
                        . 'de déploiement, de collecte, de supervision, '
                        . 'de contrôle qualité et de clôture du recensement. '
                        . 'Cette planification pourra être adaptée au niveau '
                        . 'préfectoral en fonction des réalités locales, '
                        . 'des contraintes d’accès, de la disponibilité '
                        . 'des équipes et du calendrier agricole.',
                ]
            );
        }

        $this->command->info(
            'Les planifications générales ont été créées avec succès.'
        );
    }
}