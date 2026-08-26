<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reference_sequences', function (Blueprint $table) {
            $table->id();

            /**
             * Type de référence.
             *
             * Exemple :
             * maison
             * menage
             */
            $table->string('type');

            /**
             * Type de l'entité parente.
             *
             * Exemple :
             * village
             * maison
             */
            $table->string('parent_type');

            /**
             * Identifiant de l'entité parente.
             *
             * maison :
             * village_id
             *
             * menage :
             * maison_id
             */
            $table->unsignedBigInteger('parent_id');

            /**
             * Dernier numéro attribué.
             */
            $table->unsignedInteger('last_number')->default(0);

            $table->timestamps();

            /**
             * Une seule séquence pour un type
             * et un parent donné.
             *
             * Exemple :
             * maison + village + 12
             */
            $table->unique(
                ['type', 'parent_type', 'parent_id'],
                'reference_sequences_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reference_sequences');
    }
};

