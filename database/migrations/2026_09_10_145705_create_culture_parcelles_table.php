<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cultures_parcelles', function (Blueprint $table) {

            $table->id('idCultureParcelle');

            // Identifiant technique
            $table->uuid('uid')->unique();

            // Parcelle concernée
            $table->foreignId('parcelle_id')
                ->constrained('parcelles', 'idParcelle')
                ->cascadeOnDelete();

            // Culture concernée
            $table->foreignId('culture_id')
                ->constrained('cultures', 'idCulture')
                ->restrictOnDelete();

            // Campagne agricole
            $table->string('campagneAgricole');

            // Type de culture sur la parcelle
            $table->enum('modeCulture', [
                'principale',
                'associee',
                'rotation'
            ]);

            // Superficie occupée par cette culture
            $table->decimal('superficieCultivee', 8, 2);

            // Calendrier agricole
            $table->date('dateSemis')->nullable();

            $table->date('dateRecoltePrevue')->nullable();

            $table->date('dateRecolteEffective')->nullable();

            // Irrigation
            $table->boolean('irriguee')->default(false);

            // État de la culture
            $table->enum('etatCulture', [
                'semis',
                'croissance',
                'floraison',
                'recolte',
                'terminee'
            ])->default('semis');

            // Observations
            $table->text('observations')->nullable();

            $table->timestamps();

            // Une même culture ne doit pas être enregistrée
            // deux fois pour la même parcelle et la même campagne.
            $table->unique([
                'parcelle_id',
                'culture_id',
                'campagneAgricole'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cultures_parcelles');
    }
};