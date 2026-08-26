<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('besoins_planification', function (Blueprint $table) {

            $table->id('idBesoin');

            /*
            |--------------------------------------------------------------------------
            | PLANIFICATION PRÉFECTORALE
            |--------------------------------------------------------------------------
            */

            $table->foreignId('planification_prefectorale_id')
                ->constrained(
                    'planifications_prefectorales',
                    'idPlanificationPrefectorale'
                )
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | BESOIN
            |--------------------------------------------------------------------------
            */

            $table->string('categorie');

            $table->string('designation');

            $table->decimal('quantite', 10, 2);

            $table->string('unite')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | OBSERVATIONS
            |--------------------------------------------------------------------------
            */

            $table->text('observations')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('besoins_planification');
    }
};