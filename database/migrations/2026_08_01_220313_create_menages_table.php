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

            $table->uuid('uid')->unique();

            $table->foreignId('maison_id')
                ->constrained('maisons','idMaison')
                ->cascadeOnDelete();

            // Numéro du ménage dans la maison
            $table->string('numeroMenage');

            // Chef du ménage
            $table->string('nomChef');
            $table->string('prenomChef')->nullable();
            $table->enum('sexeChef',['M','F']);

            // Composition
            $table->unsignedSmallInteger('nombreHommes')->default(0);
            $table->unsignedSmallInteger('nombreFemmes')->default(0);
            $table->unsignedSmallInteger('nombreGarcons')->default(0);
            $table->unsignedSmallInteger('nombreFilles')->default(0);

            // Activité agricole
            $table->boolean('possedeExploitation')->default(false);

            // Observation
            $table->text('observations')->nullable();

            $table->enum('statut',[
                'brouillon',
                'en_cours',
                'terminee'
            ])->default('brouillon');

            $table->timestamps();

            $table->unique(['maison_id','numeroMenage']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menages');
    }
};