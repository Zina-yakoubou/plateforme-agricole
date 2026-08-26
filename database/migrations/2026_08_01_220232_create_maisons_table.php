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
        Schema::create('maisons', function (Blueprint $table) {
            $table->id('idMaison');

            // Identifiant technique unique
            // Utilisé notamment pour le mode hors ligne et la synchronisation
            $table->uuid('uid')->unique();

            // Référence métier visible sur le terrain
            $table->string('numeroMaison')->unique();

            // Adresse ou indication complémentaire
            $table->string('adresse')->nullable();

            // Village auquel appartient la maison
            $table->foreignId('village_id')
                ->constrained('villages', 'idVillage')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maisons');
    }
};

