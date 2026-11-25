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
    Schema::create('visites_supervision', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('superviseur_id');
        $table->unsignedBigInteger('agent_id');
        $table->unsignedBigInteger('village_id')->nullable();
        $table->dateTime('date_visite');
        $table->string('statut'); // conforme, a_corriger, probleme
        $table->text('observation')->nullable();
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visite_supervisions');
    }
};
