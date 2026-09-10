<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('besoin_intrants', function (Blueprint $table) {

            $table->id('idBesoin');

            /*
            |--------------------------------------------------------------------------
            | Culture recensée concernée
            |--------------------------------------------------------------------------
            */

            $table->foreignId('culture_parcelle_id')
                ->constrained('cultures_parcelles', 'idCultureParcelle')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Intrant demandé
            |--------------------------------------------------------------------------
            */

            $table->foreignId('intrant_id')
                ->constrained('intrants', 'idIntrant')
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Évaluation du besoin
            |--------------------------------------------------------------------------
            */

            $table->decimal('quantitePrevue', 10, 2);

            $table->string('unite');

            $table->date('dateEvaluation');

            /*
            |--------------------------------------------------------------------------
            | Agent ayant évalué le besoin
            |--------------------------------------------------------------------------
            */

            $table->foreignId('agentEvaluateur_id')
                ->constrained('users')
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Observations
            |--------------------------------------------------------------------------
            */

            $table->text('observations')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Un intrant ne peut être évalué qu'une fois
            | pour une culture de la même campagne.
            |--------------------------------------------------------------------------
            */

            $table->unique([
                'culture_parcelle_id',
                'intrant_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('besoin_intrants');
    }
};