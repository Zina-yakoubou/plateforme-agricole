'<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planification_activites', function (Blueprint $table) {

            $table->id('idActivite');


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
            | ACTIVITÉ
            |--------------------------------------------------------------------------
            */

            $table->string('libelle');

            $table->text('description')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | ORDRE
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('ordre')
                ->default(1);


            /*
            |--------------------------------------------------------------------------
            | CALENDRIER
            |--------------------------------------------------------------------------
            */

            $table->dateTime('dateDebut');

            $table->dateTime('dateFin');


            /*
            |--------------------------------------------------------------------------
            | STATUT
            |--------------------------------------------------------------------------
            */

            $table->string('statut')
                ->default('planifiee');


            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | INDEX
            |--------------------------------------------------------------------------
            */

            $table->index([
                'planification_id',
                'ordre'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planification_activites');
    }
};