<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campagne_planifications', function (Blueprint $table) {

            $table->id('idPlanification');

            /*
            |--------------------------------------------------------------------------
            | CAMPAGNE
            |--------------------------------------------------------------------------
            */

            $table->foreignId('campagne_id')
                ->unique()
                ->constrained(
                    'campagne_recensements',
                    'idCampagne'
                )
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | RESPONSABLE DE LA PLANIFICATION
            |--------------------------------------------------------------------------
            */

            $table->foreignId('planifie_par')
                ->constrained(
                    'users',
                    'id'
                )
                ->restrictOnDelete();


            /*
            |--------------------------------------------------------------------------
            | STATUT
            |--------------------------------------------------------------------------
            |
            | brouillon :
            |   la planification est en cours de préparation
            |
            | planifiee :
            |   le calendrier est défini
            |
            | en_cours :
            |   au moins une activité est en cours
            |
            | terminee :
            |   toutes les activités sont terminées
            |
            */

            $table->string('statut')
                ->default('brouillon');


            /*
            |--------------------------------------------------------------------------
            | OBSERVATIONS
            |--------------------------------------------------------------------------
            */

            $table->text('observations')
                ->nullable();


            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campagne_planifications');
    }
};