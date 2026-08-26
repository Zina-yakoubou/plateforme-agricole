<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Corriger la clé étrangère superviseur_id
     * de la table alertes.
     */
    public function up(): void
    {
        Schema::table('alertes', function (Blueprint $table) {
            $table->dropForeign(['superviseur_id']);
        });

        Schema::table('alertes', function (Blueprint $table) {
            $table->foreign('superviseur_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    /**
     * Annuler la correction.
     */
    public function down(): void
    {
        Schema::table('alertes', function (Blueprint $table) {
            $table->dropForeign(['superviseur_id']);
        });

        Schema::table('alertes', function (Blueprint $table) {
            $table->foreign('superviseur_id')
                ->references('idUser')
                ->on('users')
                ->nullOnDelete();
        });
    }
};