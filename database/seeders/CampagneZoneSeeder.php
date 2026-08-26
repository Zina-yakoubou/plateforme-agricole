<?php

namespace Database\Seeders;

use App\Models\CampagneZone;
use App\Models\Prefecture;
use App\Models\Region;
use Illuminate\Database\Seeder;

class CampagneZoneSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | RÉCUPÉRATION DES CAMPAGNES
        |--------------------------------------------------------------------------
        */

        $campagneNationale = \App\Models\CampagneRecensement::where(
            'codeCampagne',
            'CAM-2026-001'
        )->firstOrFail();

        $campagneRegionale = \App\Models\CampagneRecensement::where(
            'codeCampagne',
            'CAM-2026-002'
        )->firstOrFail();

        $campagnePrefectorale = \App\Models\CampagneRecensement::where(
            'codeCampagne',
            'CAM-2026-003'
        )->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | CAMPAGNE NATIONALE
        |--------------------------------------------------------------------------
        |
        | Une campagne nationale peut couvrir plusieurs régions.
        |
        | Ici, pour les données de démonstration, on prend les trois
        | premières régions disponibles.
        |
        */

        $regions = Region::orderBy('idRegion')
            ->limit(3)
            ->get();

        foreach ($regions as $region) {

            CampagneZone::updateOrCreate(
                [
                    'campagne_id' => $campagneNationale->idCampagne,
                    'region_id' => $region->idRegion,
                    'prefecture_id' => null,
                    'commune_id' => null,
                    'canton_id' => null,
                    'village_id' => null,
                ],
                [
                    'dateDebut' => $campagneNationale->dateDebut,
                    'dateFin' => $campagneNationale->dateFin,
                    'statut' => 'planifiee',
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CAMPAGNE RÉGIONALE
        |--------------------------------------------------------------------------
        |
        | La campagne concerne une région déterminée et plusieurs
        | préfectures appartenant à cette région.
        |
        */

        $region = Region::orderBy('idRegion')
            ->firstOrFail();

        $prefectures = $region->prefectures()
            ->orderBy('idPrefecture')
            ->limit(3)
            ->get();

        foreach ($prefectures as $prefecture) {

            CampagneZone::updateOrCreate(
                [
                    'campagne_id' => $campagneRegionale->idCampagne,
                    'region_id' => $region->idRegion,
                    'prefecture_id' => $prefecture->idPrefecture,
                    'commune_id' => null,
                    'canton_id' => null,
                    'village_id' => null,
                ],
                [
                    'dateDebut' => $campagneRegionale->dateDebut,
                    'dateFin' => $campagneRegionale->dateFin,
                    'statut' => 'planifiee',
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CAMPAGNE PRÉFECTORALE — MÔ
        |--------------------------------------------------------------------------
        */

        $prefectureMo = Prefecture::where(
            'nom',
            'like',
            '%Mô%'
        )->first();

        if ($prefectureMo) {

            /*
            |----------------------------------------------------------------------
            | RÉCUPÉRATION DE LA RÉGION DE MÔ
            |----------------------------------------------------------------------
            */

            $regionMo = $prefectureMo->region;

            CampagneZone::updateOrCreate(
                [
                    'campagne_id' => $campagnePrefectorale->idCampagne,

                    'region_id' => $regionMo?->idRegion,

                    'prefecture_id' => $prefectureMo->idPrefecture,

                    'commune_id' => null,
                    'canton_id' => null,
                    'village_id' => null,
                ],
                [
                    'dateDebut' => $campagnePrefectorale->dateDebut,
                    'dateFin' => $campagnePrefectorale->dateFin,
                    'statut' => 'planifiee',
                ]
            );
        }
    }
}

