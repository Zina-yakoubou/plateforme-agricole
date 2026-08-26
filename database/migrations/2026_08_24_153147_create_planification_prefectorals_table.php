<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planifications_prefectorales', function (Blueprint $table) {

            $table->id('idPlanificationPrefectorale');

            /*
            |--------------------------------------------------------------------------
            | DÉPLOIEMENT CONCERNÉ
            |--------------------------------------------------------------------------
            */

            $table->foreignId('deploiement_id')
                ->unique()
                ->constrained(
                    'campagne_deploiements',
                    'idDeploiement'
                )
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | RESPONSABLE
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
            | PLAN DE TRAVAIL LOCAL
            |--------------------------------------------------------------------------
            */

            $table->text('planTravail')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | OBSERVATIONS
            |--------------------------------------------------------------------------
            */

            $table->text('observations')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | STATUT
            |--------------------------------------------------------------------------
            */

            $table->string('statut')
                ->default('brouillon');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planifications_prefectorales');
    }
};