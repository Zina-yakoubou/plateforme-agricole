<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menages', function (Blueprint $table) {
            $table->id('idMenage');

            // Identifiant technique permanent du ménage
            $table->uuid('uid')->unique();

            // Le ménage appartient au recensement d'une campagne
            $table->foreignId('recensement_id')
                ->constrained('recensements', 'idRecensement')
                ->cascadeOnDelete();

            // Identification du ménage dans le recensement
            $table->string('numeroMenage');

            // Chef du ménage
            $table->string('nomChef');
            $table->string('prenomChef')->nullable();
            $table->enum('sexeChef', ['M', 'F']);

            // Composition du ménage
            $table->unsignedSmallInteger('nombreHommes')->default(0)->nullable();
            $table->unsignedSmallInteger('nombreFemmes')->default(0)->nullable();
            $table->unsignedSmallInteger('nombreGarcons')->default(0)->nullable();
            $table->unsignedSmallInteger('nombreFilles')->default(0)->nullable();

            // Situation agricole du ménage
            $table->boolean('possedeExploitation')->default(false);

            // Informations complémentaires
            $table->text('observations')->nullable();

            $table->timestamps();

            // Un même recensement peut contenir plusieurs ménages,
            // mais chaque ménage possède un numéro distinct.
            $table->unique(['recensement_id', 'numeroMenage']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menages');
    }
};
