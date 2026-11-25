<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visites_supervision', function (Blueprint $table) {

            if (! Schema::hasColumn('visites_supervision', 'campagne_id')) {
                $table->string('campagne_id')->nullable()->after('village_id');
            }

            if (! Schema::hasColumn('visites_supervision', 'maison_id')) {
                $table->unsignedBigInteger('maison_id')->nullable()->after('village_id');
            }

            // La maison n'a pas d'agent : agent_id devient facultatif
            $table->unsignedBigInteger('agent_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('visites_supervision', function (Blueprint $table) {
            $table->dropColumn(['campagne_id', 'maison_id']);
        });
    }
};