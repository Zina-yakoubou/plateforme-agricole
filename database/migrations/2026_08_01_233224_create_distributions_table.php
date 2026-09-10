<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('distributions', function (Blueprint $table) {

            $table->id('idDistribution');

            $table->foreignId('allocation_id')
                ->constrained('allocations','idAllocation')
                ->cascadeOnDelete();

            $table->foreignId('agentDistributeur_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->decimal('quantiteDistribuee',10,2);

            $table->timestamp('dateDistribution');

            $table->string('lieuDistribution');

            $table->string('numeroBon')->nullable();

            $table->boolean('signatureExploitant')->default(false);

            $table->text('observations')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distributions');
    }
};