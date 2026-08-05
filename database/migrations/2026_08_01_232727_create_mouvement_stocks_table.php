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
        Schema::create('mouvement_stocks', function (Blueprint $table) {
            $table->id('idMouvement');
            $table->string('type');
            $table->float('quantite');
            $table->timestamp('date');
            $table->string('motif')->nullable();
            $table->foreignId('agentResponsable_id')->constrained('users', 'idUser')->onDelete('cascade');
            $table->foreignId('intrant_id')->constrained('intrants', 'idIntrant')->onDelete('cascade');
            $table->foreignId('magasin_id')->constrained('magasins', 'idMagasin')->onDelete('cascade');
            $table->foreignId('fournisseur_id')->nullable()->constrained('fournisseurs', 'idFournisseur')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mouvement_stocks');
    }
};
