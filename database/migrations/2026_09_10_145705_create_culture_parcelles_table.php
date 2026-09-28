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

            $table->uuid('uid')
                ->unique();

            $table->foreignId('parcelle_id')
                ->constrained(
                    'parcelles',
                    'idParcelle'
                )
                ->cascadeOnDelete();

            $table->foreignId('culture_id')
                ->constrained(
                    'cultures',
                    'idCulture'
                )
                ->restrictOnDelete();

            /*
             * Pour l'instant cette information reste facultative.
             */
            $table->string('campagneAgricole')
                ->nullable();

            /*
             * Culture principale ou associée.
             */
            $table->enum('modeCulture', [
                'principale',
                'associee',
            ]);

            /*
             * Une parcelle peut contenir plusieurs cultures.
             */
            $table->decimal('superficieCultivee', 8, 2)
                ->nullable();

            $table->date('dateSemis')
                ->nullable();

            $table->date('dateRecoltePrevue')
                ->nullable();

            $table->date('dateRecolteEffective')
                ->nullable();

            /*
             * Peut différer du mode d'irrigation général
             * de la parcelle.
             */
            $table->boolean('irriguee')
                ->default(false);

            $table->enum('etatCulture', [
                'semis',
                'croissance',
                'floraison',
                'recolte',
                'terminee',
            ])->default('semis');

            $table->text('observations')
                ->nullable();

            $table->timestamps();

            /*
             * Protection contre les doublons lorsque
             * campagneAgricole est renseignée.
             */
            $table->unique([
                'parcelle_id',
                'culture_id',
                'campagneAgricole',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cultures_parcelles');
    }
};