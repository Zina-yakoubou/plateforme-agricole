<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Créer la table d'association entre campagnes et questionnaires.
     */
    public function up(): void
    {
        Schema::create('campagne_questionnaires', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | IDENTIFICATION
            |--------------------------------------------------------------------------
            */

            $table->id('idCampagneQuestionnaire');


            /*
            |--------------------------------------------------------------------------
            | CAMPAGNE
            |--------------------------------------------------------------------------
            */

            $table->foreignId('campagne_id')
                ->constrained(
                    'campagne_recensements',
                    'idCampagne'
                )
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | QUESTIONNAIRE
            |--------------------------------------------------------------------------
            */

            $table->foreignId('questionnaire_id')
                ->constrained(
                    'questionnaires',
                    'idQuestionnaire'
                )
                ->restrictOnDelete();


            /*
            |--------------------------------------------------------------------------
            | DATES TECHNIQUES
            |--------------------------------------------------------------------------
            */

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | UNICITÉ
            |--------------------------------------------------------------------------
            |
            | Un questionnaire ne peut être associé qu'une seule fois
            | à une même campagne.
            |
            */

            $table->unique([
                'campagne_id',
                'questionnaire_id',
            ]);
        });
    }


    /**
     * Supprimer la table d'association.
     */
    public function down(): void
    {
        Schema::dropIfExists('campagne_questionnaires');
    }
};