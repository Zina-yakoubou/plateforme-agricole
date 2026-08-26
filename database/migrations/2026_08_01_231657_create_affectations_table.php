<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('affectations', function (Blueprint $table) {

            $table->id('idAffectation');

            // Référence unique de l'affectation
            $table->string('reference')->unique();

            // Période de validité
            $table->date('dateDebut')->nullable();
            $table->date('dateFin')->nullable();

            // Statut de l'affectation
            $table->string('statut')->nullable()->default('ACTIVE');

            /*
            |--------------------------------------------------------------------------
            | Relations
            |--------------------------------------------------------------------------
            */

            // Utilisateur concerné
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Campagne de recensement
            $table->foreignId('campagne_id')
                ->nullable()
                ->constrained('campagne_recensements', 'idCampagne')
                ->nullOnDelete();

            // Préfecture de rattachement (Directeur préfectoral)
            $table->foreignId('prefecture_id')
                ->nullable()
                ->constrained('prefectures', 'idPrefecture')
                ->nullOnDelete();

            // Canton d'affectation (si nécessaire)
            $table->foreignId('canton_id')
                ->nullable()
                ->constrained('cantons', 'idCanton')
                ->nullOnDelete();

            // Village d'affectation (Agent recenseur)
            $table->foreignId('village_id')
                ->nullable()
                ->constrained('villages', 'idVillage')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affectations');
    }
};