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
            $table->string('codeRNA')->unique();
            $table->string('libelle');
            $table->string('annee');
            $table->date('dateDebut');
            $table->date('dateFin')->nullable();
            $table->string('statut');
            $table->boolean('estOfficielleMAEH')->default(false);
            $table->foreignId('responsable_id')->nullable()->constrained('utilisateurs', 'idUtilisateur')->onDelete('set null');
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
