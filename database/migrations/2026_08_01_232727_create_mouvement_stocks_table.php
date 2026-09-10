<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mouvement_stocks', function (Blueprint $table) {

            $table->id('idMouvement');

            $table->foreignId('magasin_id')
                ->constrained('magasins','idMagasin')
                ->cascadeOnDelete();

            $table->foreignId('intrant_id')
                ->constrained('intrants','idIntrant')
                ->cascadeOnDelete();

            $table->foreignId('agentResponsable_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('fournisseur_id')
                ->nullable()
                ->constrained('fournisseurs','idFournisseur')
                ->nullOnDelete();

            $table->foreignId('distribution_id')
                ->nullable()
                ->constrained('distributions','idDistribution')
                ->nullOnDelete();

            $table->enum('type',[
                'entree',
                'sortie',
                'transfert',
                'correction'
            ]);

            $table->decimal('quantite',10,2);

            $table->string('unite');

            $table->timestamp('dateMouvement');

            $table->string('reference')->nullable();

            $table->text('motif')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mouvement_stocks');
    }
};