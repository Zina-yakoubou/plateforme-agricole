<?php

namespace Database\Seeders;

use App\Models\CampagnePlanification;
use App\Models\PlanificationActivite;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PlanificationActiviteSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | PLANIFICATIONS
        |--------------------------------------------------------------------------
        */

        $planifications = CampagnePlanification::query()
            ->with('campagne')
            ->get();

        if ($planifications->isEmpty()) {
            $this->command->warn(
                'Aucune planification générale trouvée.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | ACTIVITÉS TYPES D'UN RECENSEMENT AGRICOLE
        |--------------------------------------------------------------------------
        |
        | Les durées représentent un calendrier général.
        | Au niveau préfectoral, elles pourront être adaptées.
        |
        */

        $activites = [

            [
                'libelle' =>
                    'Préparation et cadrage de l’opération',

                'description' =>
                    'Définition du dispositif opérationnel, validation de la méthodologie, '
                    . 'préparation des outils de collecte, des procédures et du calendrier général.',

                'duree' => 7,
            ],

            [
                'libelle' =>
                    'Mise à jour de la cartographie et des zones de dénombrement',

                'description' =>
                    'Vérification et mise à jour du découpage territorial utilisé pour la collecte : '
                    . 'préfectures, cantons, villages et zones de dénombrement.',

                'duree' => 10,
            ],

            [
                'libelle' =>
                    'Sensibilisation des autorités et des populations',

                'description' =>
                    'Information des autorités préfectorales et locales, chefs de villages, '
                    . 'organisations agricoles, ménages et exploitants sur les objectifs du recensement.',

                'duree' => 7,
            ],

            [
                'libelle' =>
                    'Mobilisation et constitution des équipes',

                'description' =>
                    'Constitution des équipes de terrain, désignation des superviseurs et agents '
                    . 'recenseurs, vérification des effectifs et préparation du dispositif territorial.',

                'duree' => 5,
            ],

            [
                'libelle' =>
                    'Affectation des équipes aux territoires',

                'description' =>
                    'Répartition des équipes selon les territoires à couvrir et préparation '
                    . 'des affectations en fonction de la charge de travail et des distances.',

                'duree' => 3,
            ],

            [
                'libelle' =>
                    'Formation des superviseurs et agents recenseurs',

                'description' =>
                    'Formation sur la méthodologie du recensement agricole, le questionnaire, '
                    . 'les concepts agricoles, les règles de collecte et l’utilisation du système numérique.',

                'duree' => 7,
            ],

            [
                'libelle' =>
                    'Pré-test du questionnaire et du dispositif de collecte',

                'description' =>
                    'Test du questionnaire, des formulaires numériques, des procédures de synchronisation '
                    . 'et du matériel de collecte sur un échantillon de zones.',

                'duree' => 3,
            ],

            [
                'libelle' =>
                    'Dénombrement des maisons et des ménages',

                'description' =>
                    'Identification des maisons, ménages et unités agricoles afin d’assurer une couverture '
                    . 'complète du territoire avant ou pendant la collecte détaillée.',

                'duree' => 15,
            ],

            [
                'libelle' =>
                    'Recensement des exploitants et des exploitations agricoles',

                'description' =>
                    'Collecte des informations sur les exploitants, leurs exploitations, les superficies '
                    . 'exploitées, les parcelles et les principales activités agricoles.',

                'duree' => 30,
            ],

            [
                'libelle' =>
                    'Collecte des données sur les cultures et productions agricoles',

                'description' =>
                    'Collecte des informations relatives aux cultures pratiquées, superficies, productions, '
                    . 'intrants, équipements et autres caractéristiques des exploitations.',

                'duree' => 30,
            ],

            [
                'libelle' =>
                    'Collecte des données sur l’élevage et les équipements agricoles',

                'description' =>
                    'Collecte des informations relatives aux animaux d’élevage, équipements, matériels '
                    . 'agricoles et autres ressources utilisées par les exploitants.',

                'duree' => 15,
            ],

            [
                'libelle' =>
                    'Supervision et contrôle de la collecte sur le terrain',

                'description' =>
                    'Suivi quotidien des équipes, contrôle de la couverture territoriale, accompagnement '
                    . 'des agents et vérification du respect des procédures de collecte.',

                'duree' => 30,
            ],

            [
                'libelle' =>
                    'Contrôle de qualité et vérification des données',

                'description' =>
                    'Contrôle des données collectées, recherche des incohérences, doublons, omissions '
                    . 'et valeurs anormales avant validation.',

                'duree' => 15,
            ],

            [
                'libelle' =>
                    'Retour terrain et correction des dossiers',

                'description' =>
                    'Retour vers les équipes de terrain pour corriger les questionnaires incomplets '
                    . 'ou présentant des incohérences identifiées lors du contrôle qualité.',

                'duree' => 10,
            ],

            [
                'libelle' =>
                    'Synchronisation et centralisation des données',

                'description' =>
                    'Synchronisation des données collectées hors ligne et centralisation des informations '
                    . 'dans la base de données du système SIRA-Mô.',

                'duree' => 7,
            ],

            [
                'libelle' =>
                    'Validation des données du recensement',

                'description' =>
                    'Validation progressive des données après contrôle de couverture, de cohérence '
                    . 'et de qualité par les responsables de l’opération.',

                'duree' => 7,
            ],

            [
                'libelle' =>
                    'Traitement et analyse des résultats',

                'description' =>
                    'Traitement statistique des données validées et production des indicateurs '
                    . 'sur les exploitants, exploitations, cultures, productions et équipements agricoles.',

                'duree' => 15,
            ],

            [
                'libelle' =>
                    'Production des tableaux et rapports du recensement',

                'description' =>
                    'Élaboration des tableaux statistiques, rapports de synthèse et supports de restitution '
                    . 'des principaux résultats du recensement agricole.',

                'duree' => 7,
            ],

            [
                'libelle' =>
                    'Clôture et archivage de l’opération',

                'description' =>
                    'Bilan de l’opération, archivage des données et documents, évaluation du dispositif '
                    . 'et clôture administrative de la campagne.',

                'duree' => 3,
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | CRÉATION DES ACTIVITÉS POUR CHAQUE CAMPAGNE
        |--------------------------------------------------------------------------
        */

        foreach ($planifications as $planification) {

            $campagne = $planification->campagne;

            if (! $campagne) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | DATE DE DÉBUT
            |--------------------------------------------------------------------------
            */

            $dateCourante = Carbon::parse(
                $campagne->dateDebut
            )->startOfDay();

            /*
            |--------------------------------------------------------------------------
            | DATE DE FIN DE LA CAMPAGNE
            |--------------------------------------------------------------------------
            */

            $dateFinCampagne = $campagne->dateFin
                ? Carbon::parse($campagne->dateFin)->endOfDay()
                : null;

            /*
            |--------------------------------------------------------------------------
            | SUPPRESSION DES ANCIENNES ACTIVITÉS
            |--------------------------------------------------------------------------
            |
            | On repart proprement pour éviter qu'une ancienne planification
            | conserve des activités qui ne correspondent plus au calendrier.
            |
            */

            PlanificationActivite::where(
                'planification_id',
                $planification->getKey()
            )->delete();

            /*
            |--------------------------------------------------------------------------
            | CRÉATION DES ACTIVITÉS
            |--------------------------------------------------------------------------
            */

            foreach ($activites as $index => $activite) {

                /*
                |--------------------------------------------------------------------------
                | Si la campagne est terminée avant cette activité
                |--------------------------------------------------------------------------
                */

                if (
                    $dateFinCampagne
                    && $dateCourante->greaterThan($dateFinCampagne)
                ) {
                    break;
                }

                /*
                |--------------------------------------------------------------------------
                | DATE DE DÉBUT
                |--------------------------------------------------------------------------
                */

                $dateDebut = $dateCourante->copy();

                /*
                |--------------------------------------------------------------------------
                | DATE DE FIN
                |--------------------------------------------------------------------------
                */

                $dateFin = $dateDebut
                    ->copy()
                    ->addDays($activite['duree'] - 1)
                    ->endOfDay();

                /*
                |--------------------------------------------------------------------------
                | NE JAMAIS DÉPASSER LA FIN DE LA CAMPAGNE
                |--------------------------------------------------------------------------
                */

                if (
                    $dateFinCampagne
                    && $dateFin->greaterThan($dateFinCampagne)
                ) {
                    $dateFin = $dateFinCampagne->copy();
                }

                /*
                |--------------------------------------------------------------------------
                | CRÉATION
                |--------------------------------------------------------------------------
                */

                PlanificationActivite::create([
                    'planification_id' =>
                        $planification->getKey(),

                    'libelle' =>
                        $activite['libelle'],

                    'description' =>
                        $activite['description'],

                    'ordre' =>
                        $index + 1,

                    'dateDebut' =>
                        $dateDebut,

                    'dateFin' =>
                        $dateFin,

                    'statut' =>
                        'planifiee',
                ]);

                /*
                |--------------------------------------------------------------------------
                | ACTIVITÉ SUIVANTE
                |--------------------------------------------------------------------------
                */

                $dateCourante = $dateFin
                    ->copy()
                    ->addDay()
                    ->startOfDay();
            }
        }

        $this->command->info(
            'Les activités générales des planifications ont été créées avec succès.'
        );
    }
}