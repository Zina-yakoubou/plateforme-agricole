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
        Schema::create('cultures', function (Blueprint $table) {
           $table->id('idCulture');
            $table->string('typeCulture');
            $table->date('dateDebut');
            $table->date('dateFin')->nullable();
            $table->float('superficieCultivee');
            $table->string('campagne');
            $table->foreignId('parcelle_id')->constrained('parcelles', 'idParcelle')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cultures');
    }
};
