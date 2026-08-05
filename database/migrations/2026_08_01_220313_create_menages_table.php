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
        Schema::create('menages', function (Blueprint $table) {
            $table->id('idMenage');
            $table->string('nomChef');
            $table->integer('nombrePersonnes');
            $table->boolean('aChamp')->default(false);
            $table->foreignId('maison_id')->constrained('maisons', 'idMaison')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menages');
    }
};
