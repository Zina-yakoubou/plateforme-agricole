<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipes', function (Blueprint $table) {

            $table->id('idEquipe');

            // Référence unique de l'équipe
            $table->string('reference')->unique();

            // Nom de l'équipe
            $table->string('nom');

            // Superviseur responsable de l'équipe
            $table->foreignId('superviseur_id')
                ->constrained('users')
                ->restrictOnDelete();

            // Statut de l'équipe
            $table->string('statut')
                ->default('ACTIVE');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipes');
    }
};