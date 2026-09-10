<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cultures', function (Blueprint $table) {

            $table->id('idCulture');

            // Référentiel de la culture
            $table->string('nomCulture')->unique();

            $table->enum('categorie', [
                'vivriere',
                'rente',
                'maraichere',
                'fruitiere',
                'fourragere',
                'autre'
            ]);

            $table->text('description')->nullable();

            $table->boolean('active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cultures');
    }
};