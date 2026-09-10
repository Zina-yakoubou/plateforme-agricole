<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('magasins', function (Blueprint $table) {

            $table->id('idMagasin');

            $table->string('nom');

            $table->string('code')->unique();

            $table->string('localisation');

            $table->decimal('capacite',10,2);

            $table->string('uniteCapacite')->default('kg');

            $table->foreignId('prefecture_id')
                ->constrained('prefectures','idPrefecture')
                ->cascadeOnDelete();

            $table->foreignId('responsable_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->boolean('actif')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('magasins');
    }
};