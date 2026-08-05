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
        Schema::create('allocations', function (Blueprint $table) {
           $table->id('idAllocation');
            $table->float('quantiteAllouee');
            $table->string('statut');
            $table->date('dateAllocation');
            $table->foreignId('exploitant_id')->constrained('exploitants', 'idExploitant')->onDelete('cascade');
            $table->foreignId('intrant_id')->constrained('intrants', 'idIntrant')->onDelete('cascade');
            $table->foreignId('saison_id')->constrained('saison_distributions', 'idSaison')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allocations');
    }
};
