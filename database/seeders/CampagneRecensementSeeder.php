<?php

namespace Database\Seeders;

use App\Models\CampagneRecensement;
use App\Models\User;
use Illuminate\Database\Seeder;

class CampagneRecensementSeeder extends Seeder
{
    public function run(): void
    {
        $createur = User::first();

        // 1. CINQ CAMPAGNES CLÔTURÉES (Pour tester l'historique et la lecture seule)
        $anneesCloturees = [2021, 2022, 2023, 2024, 2025];
        foreach ($anneesCloturees as $annee) {
            CampagneRecensement::updateOrCreate(
                ['codeCampagne' => "CAM-{$annee}-001"],
                [
                    'libelle' => "Recensement Agricole National {$annee} ",
                    'description' => "Ancienne campagne nationale de recensement agricole de l'année {$annee}.",
                    'objectifs' => "Collecter les données statistiques de l'année {$annee}.",
                    'resultatsAttendus' => "Données consolidées et validées.",
                    'methodologie' => "Recensement standardisé sur le terrain.",
                    'instructions' => "Campagne close en lecture seule.",
                    'portee' => 'nationale',
                    'dateDebut' => "{$annee}-01-08 08:00:00",
                    'dateFin' => "{$annee}-05-31 18:00:00",
                    'estOfficielle' => true,
                    'statut' => 'cloturee',
                    'created_by' => $createur?->id,
                ]
            );
        }

        // 2. DEUX CAMPAGNES ACTIVES (Pour permettre la collecte en cours sur plusieurs fronts)
        CampagneRecensement::updateOrCreate(
            ['codeCampagne' => 'CAM-2026-001'],
            [
                'libelle' => 'Recensement Agricole National 2026',
                'description' => 'Campagne nationale active de recensement des exploitations agricoles.',
                'objectifs' => 'Identifier les exploitants agricoles et collecter les données sur le terrain.',
                'resultatsAttendus' => 'Données fiables et actualisées pour la campagne en cours.',
                'methodologie' => 'Recensement de terrain par les agents formés.',
                'instructions' => 'Respecter les procédures de collecte et de clôture des maisons.',
                'portee' => 'nationale',
                'dateDebut' => '2026-10-08 08:00:00',
                'dateFin' => '2027-05-31 18:00:00',
                'estOfficielle' => true,
                'statut' => 'active',
                'created_by' => $createur?->id,
            ]
        );

        CampagneRecensement::updateOrCreate(
            ['codeCampagne' => 'CAM-2026-002'],
            [
                'libelle' => 'Recensement Agricole Région Centrale 2026',
                'description' => 'Campagne active ciblée sur la région Centrale.',
                'objectifs' => 'Actualiser les données agricoles de la région.',
                'resultatsAttendus' => 'Disposer de données régionales actualisées.',
                'methodologie' => 'Collecte active de terrain.',
                'instructions' => 'Suivre les consignes régionales.',
                'portee' => 'regionale',
                'dateDebut' => '2026-09-01 08:00:00',
                'dateFin' => '2026-12-31 18:00:00',
                'estOfficielle' => true,
                'statut' => 'active',
                'created_by' => $createur?->id,
            ]
        );

        // 3. DEUX CAMPAGNES PLANIFIÉES (Pour tester le blocage en amont)
        CampagneRecensement::updateOrCreate(
            ['codeCampagne' => 'CAM-2026-003'],
            [
                'libelle' => 'Recensement Agricole Préfecture de Mô 2026 ',
                'description' => 'Campagne future ciblée sur la préfecture de Mô.',
                'objectifs' => 'Recenser les ménages et exploitants de Mô.',
                'resultatsAttendus' => 'Disposer de données locales précises.',
                'methodologie' => 'Collecte future.',
                'instructions' => 'En attente de démarrage.',
                'portee' => 'prefectorale',
                'dateDebut' => '2027-02-01 08:00:00',
                'dateFin' => '2027-07-31 18:00:00',
                'estOfficielle' => true,
                'statut' => 'planifiee',
                'created_by' => $createur?->id,
            ]
        );
    }
}