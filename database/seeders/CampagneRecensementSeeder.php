<?php

namespace Database\Seeders;

use App\Models\CampagneRecensement;
use App\Models\User;
use Illuminate\Database\Seeder;

class CampagneRecensementSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | UTILISATEUR QUI ENREGISTRE LA CAMPAGNE DANS SIRA
        |--------------------------------------------------------------------------
        |
        | Dans notre logique métier, la campagne a déjà été organisée
        | et validée en dehors de SIRA.
        |
        | Une personne désignée par le ministère l'enregistre donc
        | dans l'application.
        |
        */

        $createur = User::first();

        /*
        |--------------------------------------------------------------------------
        | CAMPAGNE 1 — NATIONALE
        |--------------------------------------------------------------------------
        */

        CampagneRecensement::updateOrCreate(
            [
                'codeCampagne' => 'CAM-2026-001',
            ],
            [
                'libelle' => 'Recensement Agricole National 2026',

                'description' =>
                    'Campagne nationale de recensement des exploitations agricoles.',

                'objectifs' =>
                    'Identifier les exploitants agricoles et collecter les données relatives aux exploitations agricoles sur l’ensemble du territoire national.',

                'resultatsAttendus' =>
                    'Disposer de données fiables et actualisées sur les exploitants, les exploitations agricoles et les activités agricoles.',

                'methodologie' =>
                    'Recensement de terrain réalisé par des agents formés selon une méthodologie nationale standardisée.',

                'instructions' =>
                    'Les équipes doivent respecter les procédures nationales de collecte et de contrôle des données.',

                'portee' => 'nationale',

                'dateDebut' => '2026-09-08 08:00:00',

                'dateFin' => '2026-12-31 18:00:00',

                /*
                | La campagne est officielle dès son enregistrement
                | dans SIRA, car sa validation est effectuée en dehors
                | du système.
                */
                'estOfficielle' => true,

                /*
                | Elle n'est pas encore en cours d'exécution.
                */
                'statut' => 'planifiee',

                /*
                | Personne ayant enregistré la campagne dans SIRA.
                */
                'created_by' => $createur?->id,
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | CAMPAGNE 2 — RÉGIONALE
        |--------------------------------------------------------------------------
        */

        CampagneRecensement::updateOrCreate(
            [
                'codeCampagne' => 'CAM-2026-002',
            ],
            [
                'libelle' =>
                    'Recensement Agricole Région Centrale 2026',

                'description' =>
                    'Campagne de recensement agricole ciblée sur la région Centrale.',

                'objectifs' =>
                    'Actualiser les données agricoles de la région et identifier les exploitations nouvellement créées.',

                'resultatsAttendus' =>
                    'Disposer de données agricoles actualisées permettant d’améliorer la connaissance et le suivi des exploitations de la région.',

                'methodologie' =>
                    'Collecte de données auprès des exploitants agricoles dans les préfectures concernées.',

                'instructions' =>
                    'Les agents recenseurs doivent suivre les instructions nationales et assurer la validation des données collectées.',

                'portee' => 'regionale',

                'dateDebut' => '2026-09-20 08:00:00',

                'dateFin' => '2026-10-30 18:00:00',

                'estOfficielle' => true,

                'statut' => 'planifiee',

                'created_by' => $createur?->id,
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | CAMPAGNE 3 — PRÉFECTORALE
        |--------------------------------------------------------------------------
        */

        CampagneRecensement::updateOrCreate(
            [
                'codeCampagne' => 'CAM-2026-003',
            ],
            [
                'libelle' =>
                    'Recensement Agricole Préfecture de Mô 2026',

                'description' =>
                    'Campagne de recensement agricole dans la préfecture de Mô.',

                'objectifs' =>
                    'Recenser les exploitants, les exploitations agricoles et les ménages agricoles de la préfecture de Mô.',

                'resultatsAttendus' =>
                    'Disposer de données agricoles fiables et actualisées pour la préfecture de Mô.',

                'methodologie' =>
                    'Collecte de terrain réalisée par les agents recenseurs affectés aux différentes communes et villages.',

                'instructions' =>
                    'Les agents doivent effectuer la collecte conformément aux instructions du ministère et synchroniser régulièrement les données.',

                'portee' => 'prefectorale',

                'dateDebut' => '2026-09-20 08:00:00',

                'dateFin' => '2026-11-30 18:00:00',

                'estOfficielle' => true,

                'statut' => 'planifiee',

                'created_by' => $createur?->id,
            ]
        );
    }
}

