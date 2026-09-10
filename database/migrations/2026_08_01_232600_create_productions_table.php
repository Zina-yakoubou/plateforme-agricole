<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productions', function (Blueprint $table) {

            $table->id('idProduction');

            // Culture cultivée sur la parcelle
            $table->foreignId('culture_parcelle_id')
                ->constrained(
                    'cultures_parcelles',
                    'idCultureParcelle'
                )
                ->cascadeOnDelete();

            // Quantité récoltée
            $table->decimal('quantiteProduite', 10, 2);

            // Date de récolte
            $table->date('dateRecolte');

            // Rendement
            $table->decimal('rendement', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productions');
    }
};