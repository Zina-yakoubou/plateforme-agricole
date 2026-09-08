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

            // Identifiant hors ligne
            $table->uuid('uid')->unique();

            // Numéro officiel de recensement
            $table->string('numeroMaison')->unique();

            // Localisation administrative
            $table->foreignId('village_id')
                ->constrained('villages','idVillage')
                ->cascadeOnDelete();

            // Informations observées
            $table->string('chefMaison');

            $table->string('adresse')->nullable();

            $table->unsignedSmallInteger('nombreMenages')->default(1);

            // Coordonnées GPS de la maison
            $table->decimal('latitude',10,7)->nullable();
            $table->decimal('longitude',10,7)->nullable();
            $table->decimal('precisionGPS',6,2)->nullable();

            // Photo éventuelle
            $table->string('photoMaison')->nullable();

            // Suivi terrain
            $table->enum('statut',[
                'brouillon',
                'en_cours',
                'terminee',
                'verifiee'
            ])->default('brouillon');

            $table->foreignId('agent_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamp('dateIdentification')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maisons');
    }
};