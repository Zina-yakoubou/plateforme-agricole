<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planification_activite_adaptations', function (Blueprint $table) {

            $table->id('idAdaptation');

            /*
            |--------------------------------------------------------------------------
            | PLANIFICATION PRÉFECTORALE
            |--------------------------------------------------------------------------
            */

            $table->foreignId('planification_prefectorale_id')
                ->constrained(
                    'planifications_prefectorales',
                    'idPlanificationPrefectorale'
                )
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | ACTIVITÉ DE LA PLANIFICATION GLOBALE
            |--------------------------------------------------------------------------
            */

            $table->foreignId('activite_id')
                ->constrained(
                    'planification_activites',
                    'idActivite'
                )
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | CALENDRIER LOCAL
            |--------------------------------------------------------------------------
            */

            $table->dateTime('dateDebutLocale');

            $table->dateTime('dateFinLocale');

            /*
            |--------------------------------------------------------------------------
            | SUIVI
            |--------------------------------------------------------------------------
            */

            $table->string('statut')
                ->default('planifiee');

            $table->text('observations')
                ->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | UNE ADAPTATION PAR ACTIVITÉ ET PAR PLANIFICATION
            |--------------------------------------------------------------------------
            */

            $table->unique([
                'planification_prefectorale_id',
                'activite_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planification_activite_adaptations');
    }
};