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
        Schema::create('besoin_intrants', function (Blueprint $table) {
            $table->id('idBesoin');
            $table->float('quantitePrevue');
            $table->string('unite');
            $table->date('dateEvaluation');
            $table->foreignId('agentEvaluateur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('culture_id')->constrained('cultures', 'idCulture')->onDelete('cascade');
            $table->foreignId('intrant_id')->constrained('intrants', 'idIntrant')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('besoin_intrants');
    }
};
