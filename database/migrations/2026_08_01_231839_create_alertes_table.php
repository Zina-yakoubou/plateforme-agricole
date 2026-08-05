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
        Schema::create('alertes', function (Blueprint $table) {
          $table->id('idAlerte');
            $table->string('type');
            $table->timestamp('dateAlerte');
            $table->string('statut');
            $table->text('message');
            $table->foreignId('superviseur_id')->nullable()->constrained('users', 'idUser')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alertes');
    }
};
