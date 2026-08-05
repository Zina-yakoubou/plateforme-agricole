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
        Schema::create('point_g_p_s', function (Blueprint $table) {
           $table->id('idPointGPS');
            $table->float('latitude');
            $table->float('longitude');
            $table->float('altitude')->nullable();
            $table->float('precision')->nullable();
            $table->foreignId('parcelle_id')->constrained('parcelles', 'idParcelle')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_g_p_s');
    }
};
