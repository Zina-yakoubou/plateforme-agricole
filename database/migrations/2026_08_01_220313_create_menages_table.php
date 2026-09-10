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

            // Identifiant technique permanent
            $table->uuid('uid')->unique();

            // Une maison peut contenir plusieurs ménages
            $table->foreignId('maison_id')
                ->constrained('maisons', 'idMaison')
                ->cascadeOnDelete();

            // Identification du ménage dans la maison
            $table->string('numeroMenage');

            // Chef du ménage
            $table->string('nomChef');
            $table->string('prenomChef')->nullable();
            $table->enum('sexeChef', ['M', 'F']);

            // Composition du ménage
            $table->unsignedSmallInteger('nombreHommes')->default(0);
            $table->unsignedSmallInteger('nombreFemmes')->default(0);
            $table->unsignedSmallInteger('nombreGarcons')->default(0);
            $table->unsignedSmallInteger('nombreFilles')->default(0);

            // Situation agricole du ménage
            $table->boolean('possedeExploitation')->default(false);

            // Informations complémentaires
            $table->text('observations')->nullable();

            $table->timestamps();

            // Deux ménages différents peuvent exister dans une même maison,
            // mais chacun possède un numéro distinct.
            $table->unique(['maison_id', 'numeroMenage']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menages');
    }
};