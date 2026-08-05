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
        Schema::create('affectations', function (Blueprint $table) {
            $table->id('idAffectation');
            $table->string('reference')->unique();
            $table->date('dateDebut')->nullable();
            $table->date('dateFin')->nullable();
            $table->string('statut');
            $table->foreignId('user_id')->constrained('users', 'idUser')->onDelete('cascade');
            $table->foreignId('campagne_id')->constrained('campagne_recensements', 'idCampagne')->onDelete('cascade');
            $table->foreignId('prefecture_id')->constrained('prefectures', 'idPrefecture')->onDelete('cascade')->nullable();
            $table->foreignId('canton_id')->constrained('cantons', 'idCanton')->onDelete('cascade')->nullable();
            $table->foreignId('village_id')->nullable()->constrained('villages', 'idVillage')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affectations');
    }
};
