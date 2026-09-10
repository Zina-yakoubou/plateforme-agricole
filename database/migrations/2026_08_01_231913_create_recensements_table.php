<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recensements', function (Blueprint $table) {

            $table->id('idRecensement');

            /*
            |--------------------------------------------------------------------------
            | Identifiant technique
            |--------------------------------------------------------------------------
            | Utile notamment pour le fonctionnement hors ligne.
            */
            $table->uuid('uid')->unique();

            /*
            |--------------------------------------------------------------------------
            | Campagne
            |--------------------------------------------------------------------------
            */
            $table->foreignId('campagne_id')
                ->constrained(
                    'campagne_recensements',
                    'idCampagne'
                )
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Affectation
            |--------------------------------------------------------------------------
            | Permet de savoir dans quelle affectation la fiche
            | a été collectée.
            */
            $table->foreignId('affectation_id')
                ->constrained(
                    'affectations',
                    'idAffectation'
                )
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Agent ayant réalisé la collecte
            |--------------------------------------------------------------------------
            */
            $table->foreignId('agent_id')
                ->constrained('users')
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Maison recensée
            |--------------------------------------------------------------------------
            | La maison est une entité permanente.
            | Le recensement est la fiche correspondant à cette maison
            | pour une campagne donnée.
            */
            $table->foreignId('maison_id')
                ->constrained(
                    'maisons',
                    'idMaison'
                )
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | État de la collecte
            |--------------------------------------------------------------------------
            */
            $table->enum('statut', [
                'brouillon',
                'en_cours',
                'termine',
                'valide',
            ])->default('brouillon');

            /*
            |--------------------------------------------------------------------------
            | Suivi de la collecte
            |--------------------------------------------------------------------------
            */
            $table->timestamp('dateDebut')->nullable();

            $table->timestamp('dateDerniereModification')->nullable();

            $table->timestamp('dateTerminaison')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Validation
            |--------------------------------------------------------------------------
            */
            $table->foreignId('validateur_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('dateValidation')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Observations générales de la fiche
            |--------------------------------------------------------------------------
            */
            $table->text('observations')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Une seule fiche de recensement par maison et par campagne
            |--------------------------------------------------------------------------
            */
            $table->unique([
                'campagne_id',
                'maison_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recensements');
    }
};