<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajouter le rattachement actuel de l'utilisateur à une préfecture.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->unsignedBigInteger('prefecture_id')
                ->nullable()
                ->after('role_id');

            $table->foreign('prefecture_id')
                ->references('idPrefecture')
                ->on('prefectures')
                ->nullOnDelete();
        });
    }

    /**
     * Supprimer le rattachement.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign(['prefecture_id']);

            $table->dropColumn('prefecture_id');
        });
    }
};