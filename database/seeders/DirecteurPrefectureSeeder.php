<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Prefecture;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DirecteurPrefectureSeeder extends Seeder
{
    /**
     * Affecte le directeur préfectoral
     * à la préfecture de Mô.
     */
    public function run(): void
    {
        $directeur = User::where('login', 'directeur01')
            ->where('role_id', 2)
            ->firstOrFail();

        $prefecture = Prefecture::where('nom', 'Mô')
            ->firstOrFail();

        /*
         * Le directeur est rattaché à une préfecture.
         *
         * Pas de campagne :
         * son affectation est administrative.
         *
         * Pas de canton ni de village :
         * le directeur supervise toute la préfecture.
         */
        DB::table('affectations')->updateOrInsert(
            [
                'user_id' => $directeur->id,
                'prefecture_id' => $prefecture->idPrefecture,
                'village_id' => null,
            ],
            [
                'reference' => 'DIR-MO-001',
                'dateDebut' => now()->toDateString(),
                'dateFin' => null,
                'statut' => 'ACTIVE',
                'campagne_id' => null,
                'canton_id' => null,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}