<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipe_membres', function (Blueprint $table) {

            $table->id('idEquipeMembre');

            $table->foreignId('equipe_id')
                ->constrained('equipes', 'idEquipe')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();

            /*
            |------------------------------------------------------------------
            | Un utilisateur ne peut pas être ajouté deux fois
            | dans la même équipe.
            |------------------------------------------------------------------
            */

            $table->unique([
                'equipe_id',
                'user_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipe_membres');
    }
};






