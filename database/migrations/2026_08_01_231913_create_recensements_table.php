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
        Schema::create('recensements', function (Blueprint $table) {
           $table->id('idRecensement');
            $table->timestamp('dateSaisie');
            $table->boolean('estNouveauProducteur')->default(true);
            $table->boolean('donneesModifiees')->default(false);
            $table->foreignId('campagne_id')->constrained('campagne_recensements', 'idCampagne')->onDelete('cascade');
            $table->foreignId('exploitant_id')->constrained('exploitants', 'idExploitant')->onDelete('cascade');
            $table->foreignId('agent_id')
                ->constrained('users')
                ->onDelete('cascade');           
            $table->foreignId('alerte_id')->nullable()->constrained('alertes', 'idAlerte')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recensements');
    }
};
