<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parcelles', function (Blueprint $table) {

            $table->id('idParcelle');

            // Identifiant technique permanent
            $table->uuid('uid')->unique();

            // Exploitant auquel appartient la parcelle
            $table->foreignId('exploitant_id')
                ->constrained('exploitants', 'idExploitant')
                ->cascadeOnDelete();

            // Identification
            $table->string('numeroParcelle');

            // Caractéristiques
            $table->decimal('superficie', 8, 2);

            $table->string('typeSol')->nullable();

            $table->enum('modeFaireValoir', [
                'proprietaire',
                'location',
                'pret',
                'metayage',
                'autre'
            ]);

            $table->enum('modeIrrigation', [
                'pluvial',
                'gravitaire',
                'pompage',
                'aucun',
                'autre'
            ])->default('pluvial');

            // Utilisation de la parcelle
            $table->boolean('estCultivee')->default(true);

            $table->boolean('estJachere')->default(false);

            $table->boolean('presenceArbres')->default(false);

            // Observations
            $table->text('observations')->nullable();

            $table->timestamps();

            // Un exploitant ne peut pas avoir deux fois
            // le même numéro de parcelle.
            $table->unique([
                'exploitant_id',
                'numeroParcelle'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parcelles');
    }
};