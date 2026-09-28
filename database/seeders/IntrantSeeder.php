<?php

namespace Database\Seeders;

use App\Models\Intrant;
use Illuminate\Database\Seeder;

class IntrantSeeder extends Seeder
{
    /**
     * Référentiel des intrants agricoles utilisés au Togo.
     *
     * Catégories compatibles avec la migration :
     *
     * - semence
     * - engrais
     * - herbicide
     * - insecticide
     * - fongicide
     * - fumure_organique
     * - autre
     */
    public function run(): void
    {
        $intrants = [

            /*
            |--------------------------------------------------------------------------
            | SEMENCES
            |--------------------------------------------------------------------------
            */

            [
                'nom' => 'Semence de maïs',
                'type' => 'semence',
                'unite' => 'kg',
                'actif' => true,
            ],

            [
                'nom' => 'Semence de riz',
                'type' => 'semence',
                'unite' => 'kg',
                'actif' => true,
            ],

            [
                'nom' => 'Semence de sorgho',
                'type' => 'semence',
                'unite' => 'kg',
                'actif' => true,
            ],

            [
                'nom' => 'Semence de mil',
                'type' => 'semence',
                'unite' => 'kg',
                'actif' => true,
            ],

            [
                'nom' => 'Semence de fonio',
                'type' => 'semence',
                'unite' => 'kg',
                'actif' => true,
            ],

            [
                'nom' => 'Semence de soja',
                'type' => 'semence',
                'unite' => 'kg',
                'actif' => true,
            ],

            [
                'nom' => 'Semence de niébé',
                'type' => 'semence',
                'unite' => 'kg',
                'actif' => true,
            ],

            [
                'nom' => 'Semence de haricot',
                'type' => 'semence',
                'unite' => 'kg',
                'actif' => true,
            ],

            [
                'nom' => 'Semence d’arachide',
                'type' => 'semence',
                'unite' => 'kg',
                'actif' => true,
            ],

            [
                'nom' => 'Semence de voandzou',
                'type' => 'semence',
                'unite' => 'kg',
                'actif' => true,
            ],

            [
                'nom' => 'Semence de sésame',
                'type' => 'semence',
                'unite' => 'kg',
                'actif' => true,
            ],

            [
                'nom' => 'Semence de coton',
                'type' => 'semence',
                'unite' => 'kg',
                'actif' => true,
            ],

            [
                'nom' => 'Semence de tomate',
                'type' => 'semence',
                'unite' => 'g',
                'actif' => true,
            ],

            [
                'nom' => 'Semence de piment',
                'type' => 'semence',
                'unite' => 'g',
                'actif' => true,
            ],

            [
                'nom' => 'Semence de gombo',
                'type' => 'semence',
                'unite' => 'kg',
                'actif' => true,
            ],

            [
                'nom' => 'Semence d’oignon',
                'type' => 'semence',
                'unite' => 'kg',
                'actif' => true,
            ],

            [
                'nom' => 'Semence de chou',
                'type' => 'semence',
                'unite' => 'g',
                'actif' => true,
            ],

            [
                'nom' => 'Semence de carotte',
                'type' => 'semence',
                'unite' => 'g',
                'actif' => true,
            ],

            [
                'nom' => 'Semence de laitue',
                'type' => 'semence',
                'unite' => 'g',
                'actif' => true,
            ],

            [
                'nom' => 'Semence de pomme de terre',
                'type' => 'semence',
                'unite' => 'kg',
                'actif' => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | ENGRAIS MINÉRAUX
            |--------------------------------------------------------------------------
            */

            [
                'nom' => 'NPK 15-15-15',
                'type' => 'engrais',
                'unite' => 'kg',
                'actif' => true,
            ],

            [
                'nom' => 'Urée 46% N',
                'type' => 'engrais',
                'unite' => 'kg',
                'actif' => true,
            ],

            [
                'nom' => 'DAP',
                'type' => 'engrais',
                'unite' => 'kg',
                'actif' => true,
            ],

            [
                'nom' => 'TSP',
                'type' => 'engrais',
                'unite' => 'kg',
                'actif' => true,
            ],

            [
                'nom' => 'KCl',
                'type' => 'engrais',
                'unite' => 'kg',
                'actif' => true,
            ],

            [
                'nom' => 'Sulfate de potassium',
                'type' => 'engrais',
                'unite' => 'kg',
                'actif' => true,
            ],

            [
                'nom' => 'Engrais NPK spécifique',
                'type' => 'engrais',
                'unite' => 'kg',
                'actif' => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | HERBICIDES
            |--------------------------------------------------------------------------
            */

            [
                'nom' => 'Herbicide total',
                'type' => 'herbicide',
                'unite' => 'L',
                'actif' => true,
            ],

            [
                'nom' => 'Herbicide sélectif maïs',
                'type' => 'herbicide',
                'unite' => 'L',
                'actif' => true,
            ],

            [
                'nom' => 'Herbicide sélectif riz',
                'type' => 'herbicide',
                'unite' => 'L',
                'actif' => true,
            ],

            [
                'nom' => 'Herbicide sélectif soja',
                'type' => 'herbicide',
                'unite' => 'L',
                'actif' => true,
            ],

            [
                'nom' => 'Herbicide prélevée',
                'type' => 'herbicide',
                'unite' => 'L',
                'actif' => true,
            ],

            [
                'nom' => 'Herbicide postlevée',
                'type' => 'herbicide',
                'unite' => 'L',
                'actif' => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | INSECTICIDES
            |--------------------------------------------------------------------------
            */

            [
                'nom' => 'Insecticide général',
                'type' => 'insecticide',
                'unite' => 'L',
                'actif' => true,
            ],

            [
                'nom' => 'Insecticide maïs',
                'type' => 'insecticide',
                'unite' => 'L',
                'actif' => true,
            ],

            [
                'nom' => 'Insecticide soja',
                'type' => 'insecticide',
                'unite' => 'L',
                'actif' => true,
            ],

            [
                'nom' => 'Insecticide niébé',
                'type' => 'insecticide',
                'unite' => 'L',
                'actif' => true,
            ],

            [
                'nom' => 'Insecticide coton',
                'type' => 'insecticide',
                'unite' => 'L',
                'actif' => true,
            ],

            [
                'nom' => 'Insecticide maraîcher',
                'type' => 'insecticide',
                'unite' => 'L',
                'actif' => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | FONGICIDES
            |--------------------------------------------------------------------------
            */

            [
                'nom' => 'Fongicide général',
                'type' => 'fongicide',
                'unite' => 'L',
                'actif' => true,
            ],

            [
                'nom' => 'Fongicide riz',
                'type' => 'fongicide',
                'unite' => 'L',
                'actif' => true,
            ],

            [
                'nom' => 'Fongicide maïs',
                'type' => 'fongicide',
                'unite' => 'L',
                'actif' => true,
            ],

            [
                'nom' => 'Fongicide maraîcher',
                'type' => 'fongicide',
                'unite' => 'L',
                'actif' => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | FUMURE ORGANIQUE
            |--------------------------------------------------------------------------
            */

            [
                'nom' => 'Fumier',
                'type' => 'fumure_organique',
                'unite' => 'kg',
                'actif' => true,
            ],

            [
                'nom' => 'Compost',
                'type' => 'fumure_organique',
                'unite' => 'kg',
                'actif' => true,
            ],

            [
                'nom' => 'Fientes de volaille',
                'type' => 'fumure_organique',
                'unite' => 'kg',
                'actif' => true,
            ],

            [
                'nom' => 'Résidus organiques compostés',
                'type' => 'fumure_organique',
                'unite' => 'kg',
                'actif' => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | AUTRES
            |--------------------------------------------------------------------------
            */

            [
                'nom' => 'Inoculum de soja',
                'type' => 'autre',
                'unite' => 'dose',
                'actif' => true,
            ],

            [
                'nom' => 'Biopesticide',
                'type' => 'autre',
                'unite' => 'L',
                'actif' => true,
            ],

            [
                'nom' => 'Biofertilisant',
                'type' => 'autre',
                'unite' => 'kg',
                'actif' => true,
            ],

            [
                'nom' => 'Traitement des semences',
                'type' => 'autre',
                'unite' => 'kg',
                'actif' => true,
            ],
        ];

        foreach ($intrants as $intrant) {
            Intrant::updateOrCreate(
                [
                    'nom' => $intrant['nom'],
                ],
                $intrant
            );
        }
    }
}
