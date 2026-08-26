<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Créer la table des questionnaires.
     */
    public function up(): void
    {
        Schema::create('questionnaires', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | IDENTIFICATION
            |--------------------------------------------------------------------------
            */

            $table->id('idQuestionnaire');

            $table->string('codeQuestionnaire')
                ->unique();

            $table->string('titre');

            /*
            |--------------------------------------------------------------------------
            | VERSION
            |--------------------------------------------------------------------------
            |
            | Permet d'identifier la version du questionnaire utilisé.
            |
            */

            $table->string('version')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | DESCRIPTION
            |--------------------------------------------------------------------------
            */

            $table->text('description')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | TYPE DE QUESTIONNAIRE
            |--------------------------------------------------------------------------
            */

            $table->enum('type', [
                'exploitation',
                'menage',
                'village',
                'communaute',
                'thematique',
                'autre',
            ])->default('exploitation');

            /*
            |--------------------------------------------------------------------------
            | FICHIER
            |--------------------------------------------------------------------------
            |
            | Chemin du fichier questionnaire stocké dans le système.
            |
            */

            $table->string('fichier');

            /*
            |--------------------------------------------------------------------------
            | FORMAT
            |--------------------------------------------------------------------------
            |
            | Exemples :
            | xlsx, xls, csv, json, xml, xlsform...
            |
            */

            $table->string('format', 20)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | ÉTAT
            |--------------------------------------------------------------------------
            |
            | Un questionnaire peut être désactivé sans être supprimé.
            |
            */

            $table->boolean('actif')
                ->default(true);

            /*
            |--------------------------------------------------------------------------
            | CRÉATEUR
            |--------------------------------------------------------------------------
            */

            $table->foreignId('created_by')
                ->nullable()
                ->constrained(
                    'users',
                    'id'
                )
                ->nullOnDelete();

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

            $table->index('type');
            $table->index('actif');
        });
    }


    /**
     * Supprimer la table des questionnaires.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionnaires');
    }
};