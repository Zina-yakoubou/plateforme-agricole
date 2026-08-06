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
        Schema::create('campagne_recensements', function (Blueprint $table) {

            $table->id('idCampagne');

            // Référence officielle de la campagne
            $table->string('codeRNA')
                ->unique();

            // Exemple : Recensement Agricole National 2026
            $table->string('libelle');


            // Période de déroulement
            $table->date('dateDebut');

            $table->date('dateFin')
                ->nullable();


            // Préparation, Active, Clôturée, Archivée
            $table->string('statut')
                ->default('Préparation');


            // Une seule campagne peut être active
            $table->boolean('active')
                ->default(false);


            // Campagne officiellement reconnue par le MAEH
            $table->boolean('estOfficielle')
                ->default(false);


            // Responsable administratif de la campagne
            $table->foreignId('responsable_id')
                ->nullable()
                ->constrained('users', 'id')
                ->nullOnDelete();


            $table->timestamps();

        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campagne_recensements');
    }
};