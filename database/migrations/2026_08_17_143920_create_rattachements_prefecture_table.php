<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Créer l'historique des rattachements
     * des utilisateurs aux préfectures.
     */
    public function up(): void
    {
        Schema::create('rattachements_prefecture', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Identifiant
            |--------------------------------------------------------------------------
            */

            $table->id();


            /*
            |--------------------------------------------------------------------------
            | Utilisateur
            |--------------------------------------------------------------------------
            |
            | Le compte utilisateur reste permanent.
            | Ses rattachements, eux, peuvent évoluer dans le temps.
            |
            */

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Préfecture
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('prefecture_id');

            $table->foreign('prefecture_id')
                ->references('idPrefecture')
                ->on('prefectures')
                ->restrictOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Période du rattachement
            |--------------------------------------------------------------------------
            |
            | dateDebut = début administratif du rattachement
            | dateFin   = fin administrative du rattachement
            |
            | dateFin est NULL lorsque le rattachement est toujours actif.
            |
            */

            $table->date('dateDebut')->nullable();

            $table->date('dateFin')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Statut du rattachement
            |--------------------------------------------------------------------------
            |
            | actif   = rattachement actuel
            | termine = ancien rattachement
            |
            */

            $table->enum('statut', [
                'actif',
                'termine',
            ])->default('actif');


            /*
            |--------------------------------------------------------------------------
            | Traçabilité
            |--------------------------------------------------------------------------
            |
            | created_at = date d'enregistrement dans SIRA-Mô
            | updated_at = dernière modification de l'enregistrement
            |
            */

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index('user_id');

            $table->index('prefecture_id');

            $table->index([
                'user_id',
                'statut',
            ]);

            $table->index([
                'prefecture_id',
                'statut',
            ]);
        });
    }


    /**
     * Supprimer la table.
     */
    public function down(): void
    {
        Schema::dropIfExists('rattachements_prefecture');
    }
};