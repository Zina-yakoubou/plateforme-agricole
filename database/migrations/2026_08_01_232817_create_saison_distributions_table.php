<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saison_distributions', function (Blueprint $table) {

            $table->id('idSaison');

            $table->string('libelle');

            $table->date('dateDebut');

            $table->date('dateFin')->nullable();

            $table->enum('statut',[
                'planifiee',
                'ouverte',
                'cloturee',
                'archivee'
            ])->default('planifiee');

            $table->boolean('baseSurDerniereAnnee')->default(false);

            $table->foreignId('campagneBase_id')
                ->constrained('campagne_recensements','idCampagne')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saison_distributions');
    }
};