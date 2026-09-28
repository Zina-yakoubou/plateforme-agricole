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

            $table->uuid('uid')
                ->unique();

            /*
             * L'exploitation est déterminée par le serveur.
             */
            $table->foreignId('exploitation_id')
                ->constrained(
                    'exploitations',
                    'idExploitation'
                )
                ->cascadeOnDelete();

            /*
             * Généré automatiquement :
             * P-001, P-002, P-003...
             */
            $table->string('numeroParcelle');

            /*
             * Superficie calculée à partir des points GPS
             * côté serveur.
             */
            $table->decimal('superficie', 8, 2);

            /*
             * État de la parcelle.
             */
            $table->enum('statutParcelle', [
                'exploitee',
                'jachere',
                'non_exploitee',
            ])->default('exploitee');

            /*
             * Caractéristiques de la parcelle.
             */
            $table->string('typeSol')
                ->nullable();

            $table->enum('modeFaireValoir', [
                'proprietaire',
                'location',
                'pret',
                'metayage',
                'autre',
            ]);

            $table->enum('modeIrrigation', [
                'pluvial',
                'gravitaire',
                'pompage',
                'aucun',
                'autre',
            ])->default('pluvial');

            $table->boolean('presenceArbres')
                ->default(false);

            $table->text('observations')
                ->nullable();

            $table->timestamps();

            /*
             * Une exploitation ne peut pas avoir deux fois
             * le même numéro de parcelle.
             */
            $table->unique([
                'exploitation_id',
                'numeroParcelle',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parcelles');
    }
};