<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('baremes_agronomiques', function (Blueprint $table) {
            $table->id('idBareme');

            // Préfecture concernée (Décentralisation DPA)
            $table->foreignId('prefecture_id')
                ->constrained('prefectures', 'idPrefecture')
                ->cascadeOnDelete();

            // Culture et Intrant ciblés
            $table->foreignId('culture_id')
                ->constrained('cultures', 'idCulture')
                ->cascadeOnDelete();

            $table->foreignId('intrant_id')
                ->constrained('intrants', 'idIntrant')
                ->cascadeOnDelete();

            // Dose agronomique & Seuil
            $table->decimal('dose_par_hectare', 8, 2);
            $table->string('unite')->default('kg/ha');
            $table->decimal('plafond_max_producteur', 10, 2)->nullable();

            // Statut
            $table->boolean('est_actif')->default(true);

            $table->timestamps();

            // Unicité : Un seul barème par culture + intrant dans une préfecture
            $table->unique(
                ['prefecture_id', 'culture_id', 'intrant_id'],
                'bareme_unique_pref_culture_intrant'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('baremes_agronomiques');
    }
};