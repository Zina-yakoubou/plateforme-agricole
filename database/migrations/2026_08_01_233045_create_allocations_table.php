<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('allocations', function (Blueprint $table) {

            $table->id('idAllocation');

            $table->foreignId('besoin_id')
                ->constrained('besoin_intrants','idBesoin')
                ->cascadeOnDelete();

            $table->foreignId('saison_id')
                ->constrained('saison_distributions','idSaison')
                ->cascadeOnDelete();

            $table->decimal('quantiteAllouee',10,2);

            $table->date('dateAllocation');

            $table->foreignId('agentValidateur_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->enum('statut',[
                'en_attente',
                'approuvee',
                'partielle',
                'rejetee'
            ])->default('en_attente');

            $table->text('motifRejet')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('allocations');
    }
};