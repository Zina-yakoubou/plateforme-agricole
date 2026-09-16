<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Ajouter la nouvelle colonne, nullable pour l'instant
        Schema::table('menages', function (Blueprint $table) {
            $table->unsignedBigInteger('recensement_id')->nullable()->after('maison_id');
        });

        // 2. Rattacher chaque ménage existant à un recensement
        $menages = DB::table('menages')->get();

        foreach ($menages as $menage) {
            $recensement = DB::table('recensements')
                ->where('maison_id', $menage->maison_id)
                ->orderByDesc('idRecensement')
                ->first();

            if ($recensement) {
                DB::table('menages')
                    ->where('idMenage', $menage->idMenage)
                    ->update(['recensement_id' => $recensement->idRecensement]);
            }
        }

        // 3. Rendre la colonne obligatoire et ajouter la contrainte
        Schema::table('menages', function (Blueprint $table) {
            $table->unsignedBigInteger('recensement_id')->nullable(false)->change();
            $table->foreign('recensement_id')
                  ->references('idRecensement')->on('recensements')
                  ->cascadeOnDelete();
        });

        // 4. Retirer l'index unique, la contrainte FK, puis la colonne — tout ensemble
        Schema::table('menages', function (Blueprint $table) {
            $table->dropUnique('menages_maison_id_numeromenage_unique');
            $table->dropForeign(['maison_id']);
            $table->dropColumn('maison_id');
        });

        // 5. Recréer l'équivalent logique de cet index, mais sur recensement_id
        Schema::table('menages', function (Blueprint $table) {
            $table->unique(['recensement_id', 'numeroMenage']);
        });
    }

    public function down(): void
    {
        Schema::table('menages', function (Blueprint $table) {
            $table->dropUnique(['recensement_id', 'numeroMenage']);
            $table->unsignedBigInteger('maison_id')->nullable()->after('idMenage');
        });

        Schema::table('menages', function (Blueprint $table) {
            $table->dropForeign(['recensement_id']);
            $table->dropColumn('recensement_id');
            $table->unique(['maison_id', 'numeroMenage']);
        });
    }
};