<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cheptels', function (Blueprint $table) {

            $table->id('idCheptel');

            $table->uuid('uid')->unique();

            $table->foreignId('exploitation_id')
                ->constrained('exploitations', 'idExploitation')
                ->cascadeOnDelete();

            $table->enum('typeAnimal', [
                'bovin',
                'ovin',       // mouton
                'caprin',     // chèvre
                'porcin',
                'volaille',
                'lapin',
                'asin',       // âne
                'equin',      // cheval
                'autre'
            ]);

            $table->unsignedInteger('effectif');

            $table->enum('modeElevage', [
                'extensif',
                'semi_intensif',
                'intensif'
            ])->nullable();

            $table->text('observations')->nullable();

            $table->timestamps();

            // Un même type d'animal ne peut apparaître qu'une fois par exploitation
            $table->unique([
                'exploitation_id',
                'typeAnimal'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cheptels');
    }
};