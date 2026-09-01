<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planification_territoires', function (Blueprint $table) {

            $table->id('idPlanificationTerritoire');

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
            | COMMUNE
            |--------------------------------------------------------------------------
            |
            | NULL = la ligne concerne uniquement un canton ou un village.
            |
            */

            $table->foreignId('commune_id')
                ->nullable()
                ->constrained(
                    'communes',
                    'idCommune'
                )
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | CANTON
            |--------------------------------------------------------------------------
            */

            $table->foreignId('canton_id')
                ->nullable()
                ->constrained(
                    'cantons',
                    'idCanton'
                )
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | VILLAGE
            |--------------------------------------------------------------------------
            */

            $table->foreignId('village_id')
                ->nullable()
                ->constrained(
                    'villages',
                    'idVillage'
                )
                ->cascadeOnDelete();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | INDEX
            |--------------------------------------------------------------------------
            */

            $table->index([
                'planification_prefectorale_id',
                'canton_id',
            ]);

            $table->index([
                'planification_prefectorale_id',
                'village_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planification_territoires');
    }
};