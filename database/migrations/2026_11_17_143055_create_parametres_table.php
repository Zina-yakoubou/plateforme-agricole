<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parametres', function (Blueprint $table) {
            $table->id();
            $table->string('groupe', 50);
            $table->string('cle', 80);
            $table->text('valeur')->nullable();
            $table->timestamps();

            $table->unique(['groupe', 'cle']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parametres');
    }
};