<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Créer le périmètre géographique de chaque campagne.
     */
    public function up(): void
    {
        Schema::create('campagne_zones', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | IDENTIFICATION
            |--------------------------------------------------------------------------
            */

            $table->id('idCampagneZone');


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
            | RÉGION
            |--------------------------------------------------------------------------
            |
            | La région constitue le niveau territorial de référence.
            |
            | NULL uniquement si le périmètre commence directement
            | à un autre niveau prévu par le métier.
            |
            */

            $table->foreignId('region_id')
                ->nullable()
                ->constrained(
                    'regions',
                    'idRegion'
                )
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | PRÉFECTURE
            |--------------------------------------------------------------------------
            |
            | NULL = toute la région est concernée.
            |
            | Valeur = une préfecture précise est concernée.
            |
            */

            $table->foreignId('prefecture_id')
                ->nullable()
                ->constrained(
                    'prefectures',
                    'idPrefecture'
                )
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | COMMUNE
            |--------------------------------------------------------------------------
            |
            | NULL = toute la préfecture est concernée.
            |
            | Valeur = une commune précise est concernée.
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
            |
            | NULL = toute la commune est concernée.
            |
            | Valeur = un canton précis est concerné.
            |
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
            |
            | NULL = tout le canton est concerné.
            |
            | Valeur = un village précis est concerné.
            |
            */

            $table->foreignId('village_id')
                ->nullable()
                ->constrained(
                    'villages',
                    'idVillage'
                )
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | PÉRIODE SPÉCIFIQUE À LA ZONE
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
                'active',
                'cloturee',
                'suspendue',
            ])->default('planifiee');


            /*
            |--------------------------------------------------------------------------
            | DATES TECHNIQUES
            |--------------------------------------------------------------------------
            */

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | INDEX
            |--------------------------------------------------------------------------
            */

            $table->index([
                'campagne_id',
                'region_id',
            ]);

            $table->index([
                'campagne_id',
                'prefecture_id',
            ]);

            $table->index([
                'campagne_id',
                'commune_id',
            ]);

            $table->index([
                'campagne_id',
                'canton_id',
            ]);

            $table->index([
                'campagne_id',
                'village_id',
            ]);


            /*
            |--------------------------------------------------------------------------
            | UNICITÉ
            |--------------------------------------------------------------------------
            |
            | Une même combinaison territoriale ne peut être enregistrée
            | deux fois dans une même campagne.
            |
            */

            $table->unique([
                'campagne_id',
                'region_id',
                'prefecture_id',
                'commune_id',
                'canton_id',
                'village_id',
            ]);
        });
    }


    /**
     * Supprimer le périmètre géographique.
     */
    public function down(): void
    {
        Schema::dropIfExists('campagne_zones');
    }
};