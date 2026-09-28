<?php

namespace Database\Seeders;

use App\Models\Culture;
use Illuminate\Database\Seeder;

class CultureSeeder extends Seeder
{
    /**
     * Référentiel des cultures agricoles pratiquées au Togo.
     *
     * Les catégories utilisées correspondent exactement
     * aux valeurs de la table cultures :
     *
     * - vivriere
     * - rente
     * - maraichere
     * - fruitiere
     * - fourragere
     * - autre
     */
    public function run(): void
    {
        $cultures = [

            /*
            |--------------------------------------------------------------------------
            | CULTURES VIVRIÈRES
            |--------------------------------------------------------------------------
            */

            [
                'nomCulture' => 'Maïs',
                'categorie' => 'vivriere',
                'description' => 'Céréale vivrière majeure cultivée au Togo.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Sorgho',
                'categorie' => 'vivriere',
                'description' => 'Céréale vivrière largement cultivée dans les régions du nord et du centre du Togo.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Mil',
                'categorie' => 'vivriere',
                'description' => 'Céréale vivrière principalement cultivée dans les zones septentrionales.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Riz',
                'categorie' => 'vivriere',
                'description' => 'Céréale cultivée notamment dans les bas-fonds, plaines et zones aménagées.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Fonio',
                'categorie' => 'vivriere',
                'description' => 'Petite céréale traditionnelle cultivée notamment dans le nord du pays.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Niébé',
                'categorie' => 'vivriere',
                'description' => 'Légumineuse alimentaire également connue sous le nom de haricot cornille.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Haricot',
                'categorie' => 'vivriere',
                'description' => 'Légumineuse alimentaire cultivée dans différentes régions du Togo.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Voandzou',
                'categorie' => 'vivriere',
                'description' => 'Légumineuse traditionnelle également appelée pois bambara.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Arachide',
                'categorie' => 'vivriere',
                'description' => 'Légumineuse oléagineuse cultivée dans plusieurs régions du Togo.',
                'active' => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | RACINES ET TUBERCULES
            |--------------------------------------------------------------------------
            */

            [
                'nomCulture' => 'Igname',
                'categorie' => 'vivriere',
                'description' => 'Tubercule vivrier majeur au Togo, particulièrement important dans la préfecture de Mô.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Manioc',
                'categorie' => 'vivriere',
                'description' => 'Racine vivrière majeure cultivée dans plusieurs régions du Togo.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Patate douce',
                'categorie' => 'vivriere',
                'description' => 'Culture vivrière à tubercule.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Taro',
                'categorie' => 'vivriere',
                'description' => 'Culture à tubercule cultivée dans certaines zones du Togo.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Macabo',
                'categorie' => 'vivriere',
                'description' => 'Culture à tubercule pratiquée dans certaines zones humides.',
                'active' => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | CULTURES DE RENTE / INDUSTRIELLES
            |--------------------------------------------------------------------------
            */

            [
                'nomCulture' => 'Coton',
                'categorie' => 'rente',
                'description' => 'Principale culture de rente traditionnelle du Togo.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Soja',
                'categorie' => 'rente',
                'description' => 'Culture oléagineuse et protéagineuse importante au Togo.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Sésame',
                'categorie' => 'rente',
                'description' => 'Culture oléagineuse cultivée notamment dans le nord et le centre du pays.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Anacardier',
                'categorie' => 'rente',
                'description' => 'Culture pérenne produisant la noix de cajou.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Caféier',
                'categorie' => 'rente',
                'description' => 'Culture pérenne de rente.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Cacaoyer',
                'categorie' => 'rente',
                'description' => 'Culture pérenne de rente pratiquée notamment dans les zones favorables.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Palmier à huile',
                'categorie' => 'rente',
                'description' => 'Culture pérenne produisant des régimes destinés notamment à l’huile de palme.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Cocotier',
                'categorie' => 'rente',
                'description' => 'Culture pérenne particulièrement présente dans les zones côtières.',
                'active' => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | CULTURES MARAÎCHÈRES
            |--------------------------------------------------------------------------
            */

            [
                'nomCulture' => 'Tomate',
                'categorie' => 'maraichere',
                'description' => 'Culture maraîchère largement cultivée au Togo.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Piment',
                'categorie' => 'maraichere',
                'description' => 'Culture maraîchère et condimentaire.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Gombo',
                'categorie' => 'maraichere',
                'description' => 'Culture maraîchère et légumière.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Aubergine',
                'categorie' => 'maraichere',
                'description' => 'Culture maraîchère.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Aubergine africaine',
                'categorie' => 'maraichere',
                'description' => 'Culture légumière traditionnelle.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Oignon',
                'categorie' => 'maraichere',
                'description' => 'Culture maraîchère cultivée notamment dans le nord du Togo.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Chou',
                'categorie' => 'maraichere',
                'description' => 'Culture maraîchère.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Carotte',
                'categorie' => 'maraichere',
                'description' => 'Culture maraîchère.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Concombre',
                'categorie' => 'maraichere',
                'description' => 'Culture maraîchère.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Courgette',
                'categorie' => 'maraichere',
                'description' => 'Culture maraîchère.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Laitue',
                'categorie' => 'maraichere',
                'description' => 'Culture maraîchère à feuilles.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Amarante',
                'categorie' => 'maraichere',
                'description' => 'Légume-feuille cultivé et consommé au Togo.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Corète potagère',
                'categorie' => 'maraichere',
                'description' => 'Légume-feuille traditionnel.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Morelle noire',
                'categorie' => 'maraichere',
                'description' => 'Légume-feuille traditionnel.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Oseille de Guinée',
                'categorie' => 'maraichere',
                'description' => 'Culture légumière et condimentaire.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Pastèque',
                'categorie' => 'maraichere',
                'description' => 'Culture fruitière annuelle souvent intégrée aux productions maraîchères.',
                'active' => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | CULTURES FRUITIÈRES
            |--------------------------------------------------------------------------
            */

            [
                'nomCulture' => 'Ananas',
                'categorie' => 'fruitiere',
                'description' => 'Culture fruitière importante dans certaines zones du Togo.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Manguier',
                'categorie' => 'fruitiere',
                'description' => 'Arbre fruitier largement présent au Togo.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Bananier',
                'categorie' => 'fruitiere',
                'description' => 'Culture fruitière.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Plantain',
                'categorie' => 'fruitiere',
                'description' => 'Bananier plantain cultivé comme culture alimentaire.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Papayer',
                'categorie' => 'fruitiere',
                'description' => 'Culture fruitière.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Oranger',
                'categorie' => 'fruitiere',
                'description' => 'Culture fruitière agrumicole.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Citronnier',
                'categorie' => 'fruitiere',
                'description' => 'Culture fruitière agrumicole.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Mandarinier',
                'categorie' => 'fruitiere',
                'description' => 'Culture fruitière agrumicole.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Avocatier',
                'categorie' => 'fruitiere',
                'description' => 'Culture fruitière.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Goyavier',
                'categorie' => 'fruitiere',
                'description' => 'Culture fruitière.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Papaye',
                'categorie' => 'fruitiere',
                'description' => 'Fruit du papayer.',
                'active' => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | CULTURES FOURRAGÈRES
            |--------------------------------------------------------------------------
            */

            [
                'nomCulture' => 'Maïs fourrager',
                'categorie' => 'fourragere',
                'description' => 'Maïs destiné principalement à l’alimentation animale.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Herbe fourragère',
                'categorie' => 'fourragere',
                'description' => 'Production d’herbacées destinées à l’alimentation animale.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Brachiaria',
                'categorie' => 'fourragere',
                'description' => 'Graminée fourragère.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Panicum',
                'categorie' => 'fourragere',
                'description' => 'Graminée fourragère.',
                'active' => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | AUTRES CULTURES
            |--------------------------------------------------------------------------
            */

            [
                'nomCulture' => 'Canne à sucre',
                'categorie' => 'autre',
                'description' => 'Culture sucrière.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Tabac',
                'categorie' => 'autre',
                'description' => 'Culture industrielle.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Karité',
                'categorie' => 'autre',
                'description' => 'Arbre agroforestier et fruitier à forte importance économique.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Néré',
                'categorie' => 'autre',
                'description' => 'Arbre agroforestier traditionnellement valorisé.',
                'active' => true,
            ],

            [
                'nomCulture' => 'Neem',
                'categorie' => 'autre',
                'description' => 'Arbre utilisé notamment dans les systèmes agroforestiers.',
                'active' => true,
            ],
        ];

        foreach ($cultures as $culture) {
            Culture::updateOrCreate(
                [
                    'nomCulture' => $culture['nomCulture'],
                ],
                $culture
            );
        }
    }
}
