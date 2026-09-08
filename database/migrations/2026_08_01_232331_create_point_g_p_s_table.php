<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('point_gps', function (Blueprint $table) {

            $table->id('idPointGPS');

            $table->foreignId('parcelle_id')
                ->constrained('parcelles','idParcelle')
                ->cascadeOnDelete();

            // Coordonnées
            $table->decimal('latitude',10,7);

            $table->decimal('longitude',10,7);

            $table->decimal('altitude',8,2)->nullable();

            $table->decimal('precisionGPS',6,2)->nullable();

            // Ordre du sommet
            $table->unsignedSmallInteger('ordre');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_gps');
    }
};