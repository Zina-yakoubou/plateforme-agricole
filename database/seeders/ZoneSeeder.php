<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Throwable;

class ZoneSeeder extends Seeder
{
    /**
     * Importe la hiérarchie géographique depuis zone.json.
     *
     * Structure :
     * Pays
     *  └── Région
     *       └── Préfecture
     *            └── Commune
     *                 └── Canton
     *                      └── Village (district dans le JSON)
     */
    public function run(): void
    {
        $path = database_path('data/zone.json');

        if (!File::exists($path)) {
            $this->command->error("Fichier introuvable : {$path}");
            return;
        }

        $json = File::get($path);

        // Suppression éventuelle d'un BOM UTF-8
        $json = preg_replace('/^\xEF\xBB\xBF/', '', $json);

        try {
            $data = json_decode(
                $json,
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (Throwable $e) {
            $this->command->error(
                'Erreur lors de la lecture de zone.json : ' . $e->getMessage()
            );

            return;
        }

        if (!isset($data['pays']) || !is_array($data['pays'])) {
            $this->command->error(
                'Structure invalide : la clé "pays" est absente de zone.json.'
            );

            return;
        }

        DB::transaction(function () use ($data) {

            $nombreRegions = 0;
            $nombrePrefectures = 0;
            $nombreCommunes = 0;
            $nombreCantons = 0;
            $nombreVillages = 0;

            foreach ($data['pays'] as $pays) {

                if (
                    !isset($pays['regions']) ||
                    !is_array($pays['regions'])
                ) {
                    continue;
                }

                /*
                 * =====================================================
                 * RÉGIONS
                 * =====================================================
                 */
                foreach ($pays['regions'] as $regionData) {

                    if (
                        empty($regionData['code']) ||
                        empty($regionData['name'])
                    ) {
                        continue;
                    }

                    $region = DB::table('regions')
                        ->where('code', $regionData['code'])
                        ->first();

                    if ($region) {
                        DB::table('regions')
                            ->where('idRegion', $region->idRegion)
                            ->update([
                                'nom' => $regionData['name'],
                                'updated_at' => now(),
                            ]);

                        $regionId = $region->idRegion;
                    } else {
                        $regionId = DB::table('regions')->insertGetId([
                            'nom' => $regionData['name'],
                            'code' => $regionData['code'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    $nombreRegions++;

                    /*
                     * =================================================
                     * PRÉFECTURES
                     * =================================================
                     */
                    foreach ($regionData['prefectures'] ?? [] as $prefectureData) {

                        if (
                            empty($prefectureData['code']) ||
                            empty($prefectureData['name'])
                        ) {
                            continue;
                        }

                        $prefecture = DB::table('prefectures')
                            ->where('code', $prefectureData['code'])
                            ->first();

                        if ($prefecture) {
                            DB::table('prefectures')
                                ->where(
                                    'idPrefecture',
                                    $prefecture->idPrefecture
                                )
                                ->update([
                                    'nom' => $prefectureData['name'],
                                    'region_id' => $regionId,
                                    'updated_at' => now(),
                                ]);

                            $prefectureId = $prefecture->idPrefecture;
                        } else {
                            $prefectureId = DB::table('prefectures')
                                ->insertGetId([
                                    'nom' => $prefectureData['name'],
                                    'code' => $prefectureData['code'],
                                    'region_id' => $regionId,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                        }

                        $nombrePrefectures++;

                        /*
                         * =============================================
                         * COMMUNES
                         * =============================================
                         */
                        foreach (
                            $prefectureData['municipalities'] ?? []
                            as $communeData
                        ) {

                            if (
                                empty($communeData['code']) ||
                                empty($communeData['name'])
                            ) {
                                continue;
                            }

                            $commune = DB::table('communes')
                                ->where('code', $communeData['code'])
                                ->first();

                            if ($commune) {
                                DB::table('communes')
                                    ->where(
                                        'idCommune',
                                        $commune->idCommune
                                    )
                                    ->update([
                                        'nom' => $communeData['name'],
                                        'prefecture_id' => $prefectureId,
                                        'updated_at' => now(),
                                    ]);

                                $communeId = $commune->idCommune;
                            } else {
                                $communeId = DB::table('communes')
                                    ->insertGetId([
                                        'nom' => $communeData['name'],
                                        'code' => $communeData['code'],
                                        'prefecture_id' => $prefectureId,
                                        'created_at' => now(),
                                        'updated_at' => now(),
                                    ]);
                            }

                            $nombreCommunes++;

                            /*
                             * =========================================
                             * CANTONS
                             * =========================================
                             */
                            foreach (
                                $communeData['cantons'] ?? []
                                as $cantonData
                            ) {

                                if (
                                    empty($cantonData['code']) ||
                                    empty($cantonData['name'])
                                ) {
                                    continue;
                                }

                                $canton = DB::table('cantons')
                                    ->where('code', $cantonData['code'])
                                    ->first();

                                if ($canton) {
                                    DB::table('cantons')
                                        ->where(
                                            'idCanton',
                                            $canton->idCanton
                                        )
                                        ->update([
                                            'nom' => $cantonData['name'],
                                            'commune_id' => $communeId,
                                            'updated_at' => now(),
                                        ]);

                                    $cantonId = $canton->idCanton;
                                } else {
                                    $cantonId = DB::table('cantons')
                                        ->insertGetId([
                                            'nom' => $cantonData['name'],
                                            'code' => $cantonData['code'],
                                            'commune_id' => $communeId,
                                            'created_at' => now(),
                                            'updated_at' => now(),
                                        ]);
                                }

                                $nombreCantons++;

                                /*
                                 * =====================================
                                 * VILLAGES
                                 *
                                 * Dans zone.json :
                                 * districts = villages
                                 * =====================================
                                 */
                                foreach (
                                    $cantonData['districts'] ?? []
                                    as $villageData
                                ) {

                                    if (
                                        empty($villageData['code']) ||
                                        empty($villageData['name'])
                                    ) {
                                        continue;
                                    }

                                    $village = DB::table('villages')
                                        ->where(
                                            'code',
                                            $villageData['code']
                                        )
                                        ->first();

                                    if ($village) {
                                        DB::table('villages')
                                            ->where(
                                                'idVillage',
                                                $village->idVillage
                                            )
                                            ->update([
                                                'nom' => $villageData['name'],
                                                'canton_id' => $cantonId,
                                                'updated_at' => now(),
                                            ]);
                                    } else {
                                        DB::table('villages')
                                            ->insert([
                                                'nom' => $villageData['name'],
                                                'code' => $villageData['code'],
                                                'canton_id' => $cantonId,
                                                'created_at' => now(),
                                                'updated_at' => now(),
                                            ]);
                                    }

                                    $nombreVillages++;
                                }
                            }
                        }
                    }
                }
            }

            $this->command->newLine();

            $this->command->info(
                "Importation géographique terminée."
            );

            $this->command->table(
                [
                    'Niveau',
                    'Nombre traité',
                ],
                [
                    ['Régions', $nombreRegions],
                    ['Préfectures', $nombrePrefectures],
                    ['Communes', $nombreCommunes],
                    ['Cantons', $nombreCantons],
                    ['Villages', $nombreVillages],
                ]
            );
        });
    }
}