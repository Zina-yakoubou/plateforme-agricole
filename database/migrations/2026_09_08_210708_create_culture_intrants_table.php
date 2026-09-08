<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('culture_intrants', function (Blueprint $table) {

            $table->id();

            $table->foreignId('culture_id')
                ->constrained('cultures','idCulture')
                ->cascadeOnDelete();

            $table->foreignId('intrant_id')
                ->constrained('intrants','idIntrant')
                ->cascadeOnDelete();

            // Quantité réellement appliquée
            $table->decimal('quantite',8,2);

            // Nombre d'applications
            $table->unsignedTinyInteger('nombreApplications')->default(1);

            $table->date('dateApplication')->nullable();

            $table->text('observations')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('culture_intrants');
    }
};