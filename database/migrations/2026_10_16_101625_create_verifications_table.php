<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verifications', function (Blueprint $table) {

            $table->id('idVerification');

            $table->foreignId('affectation_id')
                ->constrained('affectations', 'idAffectation')
                ->cascadeOnDelete();

            $table->foreignId('recensement_id')
                ->constrained('recensements', 'idRecensement')
                ->cascadeOnDelete();

            $table->foreignId('maison_id')
                ->constrained('maisons', 'idMaison')
                ->cascadeOnDelete();

            $table->foreignId('superviseur_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('agent_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->enum('resultat', [
                'conforme',
                'non_conforme',
                'introuvable',
            ]);

            $table->text('remarque')->nullable();

            $table->timestamp('dateVisite');

            $table->timestamps();

            // Une seule vérification par fiche (on la modifie, on ne la duplique pas)
            $table->unique('recensement_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verifications');
    }
};