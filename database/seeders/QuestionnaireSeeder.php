<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Questionnaire;
use App\Models\CampagneRecensement;

class QuestionnaireSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | QUESTIONNAIRES OFFICIELS DU MINISTÈRE
        |--------------------------------------------------------------------------
        */

        $questionnaires = [

            [
                'codeQuestionnaire' => 'QST-EXP-2026',
                'titre' => 'Questionnaire de recensement des exploitations agricoles',
                'version' => '2026.1',
                'description' => 'Identification des producteurs, exploitations, cultures, superficies et besoins en intrants.',
                'type' => 'exploitation',
                'fichier' => 'questionnaires/recensement_exploitation_v2026.xlsx',
                'format' => 'XLSForm',
                'actif' => true,
            ],

            [
                'codeQuestionnaire' => 'QST-MEN-2026',
                'titre' => 'Questionnaire ménage agricole',
                'version' => '2026.1',
                'description' => 'Informations socio-économiques du ménage agricole.',
                'type' => 'menage',
                'fichier' => 'questionnaires/menage_agricole_v2026.xlsx',
                'format' => 'XLSForm',
                'actif' => true,
            ],

            [
                'codeQuestionnaire' => 'QST-VIL-2026',
                'titre' => 'Questionnaire village',
                'version' => '2026.1',
                'description' => 'Informations générales sur les villages recensés.',
                'type' => 'village',
                'fichier' => 'questionnaires/village_v2026.xlsx',
                'format' => 'XLSForm',
                'actif' => true,
            ],

            [
                'codeQuestionnaire' => 'QST-COM-2026',
                'titre' => 'Questionnaire communauté agricole',
                'version' => '2026.1',
                'description' => 'Informations sur les infrastructures et organisations agricoles.',
                'type' => 'communaute',
                'fichier' => 'questionnaires/communaute_v2026.xlsx',
                'format' => 'XLSForm',
                'actif' => true,
            ],

            [
                'codeQuestionnaire' => 'QST-INT-2026',
                'titre' => 'Questionnaire besoins en intrants agricoles',
                'version' => '2026.1',
                'description' => 'Collecte des besoins en semences, engrais et autres intrants.',
                'type' => 'thematique',
                'fichier' => 'questionnaires/intrants_v2026.xlsx',
                'format' => 'XLSForm',
                'actif' => true,
            ],

            [
                'codeQuestionnaire' => 'QST-ARB-2026',
                'titre' => 'Questionnaire arboriculture et plantations',
                'version' => '2026.1',
                'description' => 'Recensement des cultures pérennes.',
                'type' => 'thematique',
                'fichier' => 'questionnaires/arboriculture_v2026.xlsx',
                'format' => 'XLSForm',
                'actif' => true,
            ],

            [
                'codeQuestionnaire' => 'QST-ELE-2026',
                'titre' => 'Questionnaire élevage',
                'version' => '2026.1',
                'description' => 'Recensement des activités d’élevage.',
                'type' => 'thematique',
                'fichier' => 'questionnaires/elevage_v2026.xlsx',
                'format' => 'XLSForm',
                'actif' => true,
            ],

        ];

        /*
        |--------------------------------------------------------------------------
        | INSERTION DES QUESTIONNAIRES
        |--------------------------------------------------------------------------
        */

        foreach ($questionnaires as $data) {

            Questionnaire::updateOrCreate(
                [
                    'codeQuestionnaire' => $data['codeQuestionnaire'],
                ],
                [
                    'titre'       => $data['titre'],
                    'version'     => $data['version'],
                    'description' => $data['description'],
                    'type'        => $data['type'],
                    'fichier'     => $data['fichier'],
                    'format'      => $data['format'],
                    'actif'       => $data['actif'],
                    'created_by'  => 1, // Administrateur du ministère
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ASSOCIATION AUX CAMPAGNES (TABLE PIVOT)
        |--------------------------------------------------------------------------
        */

        $campagnes = CampagneRecensement::whereIn('codeCampagne', [
            'CAM-2026-001',
            'CAM-2026-002',
            'CAM-2026-003',
        ])->get()->keyBy('codeCampagne');

        if ($campagnes->isEmpty()) {
            return;
        }

        $liaisons = [

            /*
            |--------------------------------------------------------------
            | Campagne nationale
            |--------------------------------------------------------------
            */

            'CAM-2026-001' => [
                'QST-EXP-2026',
                'QST-MEN-2026',
                'QST-VIL-2026',
                'QST-COM-2026',
                'QST-INT-2026',
            ],

            /*
            |--------------------------------------------------------------
            | Campagne régionale
            |--------------------------------------------------------------
            */

            'CAM-2026-002' => [
                'QST-EXP-2026',
                'QST-MEN-2026',
                'QST-INT-2026',
            ],

            /*
            |--------------------------------------------------------------
            | Campagne préfectorale (Mô)
            |--------------------------------------------------------------
            */

            'CAM-2026-003' => [
                'QST-EXP-2026',
                'QST-INT-2026',
            ],

        ];

        foreach ($liaisons as $codeCampagne => $codesQuestionnaires) {

            $campagne = $campagnes[$codeCampagne];

            $idsQuestionnaires = Questionnaire::whereIn(
                'codeQuestionnaire',
                $codesQuestionnaires
            )->pluck('idQuestionnaire');

            /*
            |--------------------------------------------------------------
            | Remplit la table campagne_questionnaires
            |--------------------------------------------------------------
            */

            $campagne->questionnaires()->syncWithoutDetaching(
                $idsQuestionnaires
            );
        }
    }
}