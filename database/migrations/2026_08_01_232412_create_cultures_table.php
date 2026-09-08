<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cultures', function (Blueprint $table) {

            $table->id('idCulture');

            $table->foreignId('parcelle_id')
                ->constrained('parcelles','idParcelle')
                ->cascadeOnDelete();

            // Culture
            $table->string('nomCulture');

            $table->enum('categorie',[
                'vivriere',
                'rente',
                'maraichere',
                'fruitiere',
                'fourragere',
                'autre'
            ]);

            $table->enum('modeCulture',[
                'principale',
                'associee',
                'rotation'
            ]);

            // Campagne agricole
            $table->string('campagneAgricole');

            // Superficie occupée
            $table->decimal('superficieCultivee',8,2);

            // Calendrier agricole
            $table->date('dateSemis')->nullable();

            $table->date('dateRecoltePrevue')->nullable();

            $table->date('dateRecolteEffective')->nullable();

            // Production
            $table->decimal('productionEstimee',10,2)->nullable();

            $table->decimal('productionRecoltee',10,2)->nullable();

            $table->string('uniteProduction')->default('kg');

            // Irrigation
            $table->boolean('irriguee')->default(false);

            // État de la culture
            $table->enum('etatCulture',[
                'semis',
                'croissance',
                'floraison',
                'recolte',
                'terminee'
            ])->default('semis');

            $table->text('observations')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cultures');
    }
};