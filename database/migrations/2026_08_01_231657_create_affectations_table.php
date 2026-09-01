<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Création de la table des affectations.
     */
    public function up(): void
    {
        Schema::create('affectations', function (Blueprint $table) {

            $table->id('idAffectation');

            /*
            |--------------------------------------------------------------------------
            | IDENTIFICATION
            |--------------------------------------------------------------------------
            */

            $table->string('reference')->unique();

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
            | ÉQUIPE
            |--------------------------------------------------------------------------
            |
            | L'affectation concerne toute l'équipe :
            | superviseur + agents recenseurs.
            |
            */

            $table->foreignId('equipe_id')
                ->constrained(
                    'equipes',
                    'idEquipe'
                )
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | TERRITOIRE
            |--------------------------------------------------------------------------
            |
            | On saisit uniquement le village.
            |
            | Village
            |    ↓
            | Canton
            |    ↓
            | Commune
            |    ↓
            | Préfecture
            |
            */

            $table->foreignId('village_id')
                ->constrained(
                    'villages',
                    'idVillage'
                )
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | PÉRIODE
            |--------------------------------------------------------------------------
            */

            $table->date('dateDebut')->nullable();

            $table->date('dateFin')->nullable();

            /*
            |--------------------------------------------------------------------------
            | STATUT
            |--------------------------------------------------------------------------
            |
            | active   : affectation en cours
            | terminee : affectation terminée
            | annulee  : affectation annulée
            |
            */

            $table->string('statut')
                ->default('active');

            /*
            |--------------------------------------------------------------------------
            | OBSERVATIONS
            |--------------------------------------------------------------------------
            */

            $table->text('observations')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | CONTRAINTE
            |--------------------------------------------------------------------------
            |
            | Une même équipe ne peut pas avoir deux affectations
            | sur le même village pour la même campagne.
            |
            */

            $table->unique(
                ['campagne_id', 'equipe_id', 'village_id'],
                'affectation_campagne_equipe_village_unique'
            );
        });
    }

    /**
     * Suppression de la table.
     */
    public function down(): void
    {
        Schema::dropIfExists('affectations');
    }
};