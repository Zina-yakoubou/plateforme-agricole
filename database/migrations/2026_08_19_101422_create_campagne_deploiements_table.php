<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campagne_deploiements', function (Blueprint $table) {

            $table->id('idDeploiement');


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
            | PRÉFECTURE DESTINATAIRE
            |--------------------------------------------------------------------------
            */

            $table->foreignId('prefecture_id')
                ->constrained(
                    'prefectures',
                    'idPrefecture'
                )
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | ÉTAT DU DÉPLOIEMENT
            |--------------------------------------------------------------------------
            |
            | notifiee : la campagne a été envoyée à la préfecture.
            | recue    : le DPA a accusé réception.
            |
            */

            $table->enum('statut', [
                'notifiee',
                'recue',
            ])->default('notifiee');


            /*
            |--------------------------------------------------------------------------
            | RÉCEPTION
            |--------------------------------------------------------------------------
            */

            $table->timestamp('dateReception')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | UTILISATEUR AYANT ACCUSÉ RÉCEPTION
            |--------------------------------------------------------------------------
            */

            $table->foreignId('recu_par')
                ->nullable()
                ->constrained(
                    'users',
                    'id'
                )
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | DATES TECHNIQUES
            |--------------------------------------------------------------------------
            */

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | UN SEUL DÉPLOIEMENT PAR CAMPAGNE ET PAR PRÉFECTURE
            |--------------------------------------------------------------------------
            */

            $table->unique([
                'campagne_id',
                'prefecture_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campagne_deploiements');
    }
};