<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Créer les étapes des planifications.
     */
    public function up(): void
    {
        Schema::create('planification_etapes', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | IDENTIFICATION
            |--------------------------------------------------------------------------
            */

            $table->id('idEtape');


            /*
            |--------------------------------------------------------------------------
            | PLANIFICATION
            |--------------------------------------------------------------------------
            */

            $table->foreignId('planification_id')
                ->constrained(
                    'campagne_planifications',
                    'idPlanification'
                )
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | TYPE D'ÉTAPE
            |--------------------------------------------------------------------------
            |
            | Exemple :
            | sensibilisation
            | formation
            | collecte
            | controle
            | transmission
            | evaluation
            |
            | On utilise une chaîne plutôt qu'un enum afin de permettre
            | l'ajout futur d'autres types d'étapes.
            |
            */

            $table->string('type')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | LIBELLÉ
            |--------------------------------------------------------------------------
            |
            | Nom affiché de l'étape.
            |
            */

            $table->string('libelle');


            /*
            |--------------------------------------------------------------------------
            | ORDRE
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('ordre');


            /*
            |--------------------------------------------------------------------------
            | PÉRIODE DE L'ÉTAPE
            |--------------------------------------------------------------------------
            */

            $table->dateTime('dateDebut')
                ->nullable();

            $table->dateTime('dateFin')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | STATUT
            |--------------------------------------------------------------------------
            */

            $table->enum('statut', [
                'planifiee',
                'en_cours',
                'terminee',
                'suspendue',
            ])->default('planifiee');


            /*
            |--------------------------------------------------------------------------
            | OBSERVATIONS
            |--------------------------------------------------------------------------
            */

            $table->text('observations')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | TRAÇABILITÉ
            |--------------------------------------------------------------------------
            */

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | ORDRE UNIQUE DANS UNE PLANIFICATION
            |--------------------------------------------------------------------------
            |
            | Une planification ne peut pas avoir deux étapes n°1,
            | deux étapes n°2, etc.
            |
            */

            $table->unique([
                'planification_id',
                'ordre',
            ]);
        });
    }


    /**
     * Supprimer la table.
     */
    public function down(): void
    {
        Schema::dropIfExists('planification_etapes');
    }
};