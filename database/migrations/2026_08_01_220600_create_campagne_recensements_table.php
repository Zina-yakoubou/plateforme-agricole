<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Créer la table des campagnes de recensement.
     */
    public function up(): void
    {
        Schema::create('campagne_recensements', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | IDENTIFICATION
            |--------------------------------------------------------------------------
            */

            $table->id('idCampagne');

            $table->string('codeCampagne')
                ->unique();

            $table->string('libelle');

            $table->text('description')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | CADRE DU RECENSEMENT
            |--------------------------------------------------------------------------
            */

            $table->text('objectifs');
            $table->longText('resultatsAttendus');
            $table->text('zoneConserner')->nullable();


            $table->longText('methodologie')
                ->nullable();

            $table->longText('instructions')
                ->nullable();
            
           

            /*
            |--------------------------------------------------------------------------
            | PORTÉE TERRITORIALE
            |--------------------------------------------------------------------------
            |
            | Définit le niveau territorial général de la campagne.
            |
            | nationale    : tout le territoire national
            | regionale    : une ou plusieurs régions
            | prefectorale : une ou plusieurs préfectures
            |
            | Les territoires précis sont enregistrés dans
            | campagne_zones.
            |
            */

            $table->enum('portee', [
                'nationale',
                'regionale',
                'prefectorale',
            ]);


            /*
            |--------------------------------------------------------------------------
            | PÉRIODE GÉNÉRALE
            |--------------------------------------------------------------------------
            */

            $table->dateTime('dateDebut');

            $table->dateTime('dateFin')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | CYCLE DE VIE
            |--------------------------------------------------------------------------
            */

            $table->enum('statut', [
                'planifiee',
                'active',
                'cloturee',
                'archivee',
            ])->default('planifiee');


            /*
            |--------------------------------------------------------------------------
            | CARACTÈRE OFFICIEL
            |--------------------------------------------------------------------------
            */

            $table->boolean('estOfficielle')
                ->default(false)->nullable();


    

            /*
            |--------------------------------------------------------------------------
            | UTILISATEUR AYANT CRÉÉ LA CAMPAGNE
            |--------------------------------------------------------------------------
            |
            | Utilisateur ayant enregistré la campagne dans SIRA.
            |
            */

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users', 'id')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | DATES TECHNIQUES
            |--------------------------------------------------------------------------
            */

            $table->timestamps();
        });
    }


    /**
     * Supprimer la table des campagnes.
     */
    public function down(): void
    {
        Schema::dropIfExists('campagne_recensements');
    }
};