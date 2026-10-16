<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // La table saison_distributions est créée
        // par la migration initiale du 1er août 2026.
        if (! Schema::hasTable('saison_distributions')) {
            throw new \RuntimeException(
                'La table saison_distributions n’existe pas. Exécutez d’abord la migration initiale.'
            );
        }
    }

    public function down(): void
    {
        // Ne pas supprimer la table existante.
    }
};
