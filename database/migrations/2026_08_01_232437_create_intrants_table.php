<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intrants', function (Blueprint $table) {

            $table->id('idIntrant');

            $table->string('nom');

            $table->enum('type',[
                'semence',
                'engrais',
                'herbicide',
                'insecticide',
                'fongicide',
                'fumure_organique',
                'autre'
            ]);

            $table->string('unite');

            $table->boolean('actif')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intrants');
    }
};