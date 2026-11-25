<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referentiel_culture_intrants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('culture_id')
                ->constrained('cultures', 'idCulture')
                ->cascadeOnDelete();

            $table->foreignId('intrant_id')
                ->constrained('intrants', 'idIntrant')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['culture_id', 'intrant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referentiel_culture_intrants');
    }
};