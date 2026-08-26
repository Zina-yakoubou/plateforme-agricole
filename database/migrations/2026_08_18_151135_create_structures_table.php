<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Créer les structures institutionnelles.
     */
    public function up(): void
    {
        Schema::create('structures', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | IDENTIFICATION
            |--------------------------------------------------------------------------
            */

            $table->id('idStructure');

            $table->string('codeStructure')
                ->unique();

            $table->string('nom');


            /*
            |--------------------------------------------------------------------------
            | CLASSIFICATION
            |--------------------------------------------------------------------------
            |
            | Exemple de niveau :
            |
            | national
            | regional
            | prefectoral
            | communal
            |
            */

            $table->string('niveau', 30);


            /*
            |--------------------------------------------------------------------------
            | TYPE DE STRUCTURE
            |--------------------------------------------------------------------------
            |
            | Permet de distinguer la nature de la structure.
            |
            | Exemple :
            |
            | ministere
            | direction_nationale
            | direction_regionale
            | direction_prefectorale
            | autre
            |
            */

            $table->string('type', 50);


            /*
            |--------------------------------------------------------------------------
            | HIÉRARCHIE
            |--------------------------------------------------------------------------
            |
            | Une structure peut dépendre d'une autre structure.
            |
            | Exemple :
            |
            | Direction préfectorale
            |       ↓
            | Structure régionale
            |
            */

            $table->foreignId('structure_parent_id')
                ->nullable()
                ->constrained(
                    'structures',
                    'idStructure'
                )
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | ÉTAT
            |--------------------------------------------------------------------------
            */

            $table->boolean('statut')
                ->default(true);


            /*
            |--------------------------------------------------------------------------
            | DATES
            |--------------------------------------------------------------------------
            */

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | INDEX
            |--------------------------------------------------------------------------
            */

            $table->index('niveau');

            $table->index('type');

            $table->index('structure_parent_id');
        });
    }


    /**
     * Supprimer la table.
     */
    public function down(): void
    {
        Schema::dropIfExists('structures');
    }
};