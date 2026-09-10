<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maisons', function (Blueprint $table) {
            $table->id('idMaison');

            // Identifiant technique permanent
            $table->uuid('uid')->unique();

            // Numéro officiel de la maison
            $table->string('numeroMaison')->unique();

            // Localisation administrative permanente
            $table->foreignId('village_id')
                ->constrained('villages', 'idVillage')
                ->cascadeOnDelete();

            // Localisation géographique
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('precisionGPS', 6, 2)->nullable();

            // Adresse descriptive
            $table->string('adresse')->nullable();

            $table->timestamps();

            $table->index('village_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maisons');
    }
};