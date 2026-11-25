<?php

namespace Database\Seeders;

use App\Models\BaremeAgronomique;
use App\Models\CampagneRecensement;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EquipesChaineAgricoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            // 0. CAMPAGNE SOURCE : la dernière clôturée
            $campagne = CampagneRecensement::where('statut', 'cloturee')
                ->orderByDesc('dateFin')
                ->first();

            if (! $campagne) {
                throw new \RuntimeException("Aucune campagne clôturée : lancez d'abord CampagneRecensementSeeder.");
            }

            $campagneId = (int) $campagne->getKey();
            $debut = $campagne->dateDebut?->toDateString();
            $fin   = $campagne->dateFin?->toDateString();

            // Rattache à la campagne clôturée les fiches dont la maison n'a pas
            // déjà de fiche dans cette campagne (évite la contrainte unique)
            $deplaces = DB::table('recensements')
                ->where('campagne_id', '!=', $campagneId)
                ->whereNotIn('maison_id', function ($q) use ($campagneId) {
                    $q->select('maison_id')->from('recensements')->where('campagne_id', $campagneId);
                })
                ->update(['campagne_id' => $campagneId, 'updated_at' => now()]);

            $this->command->info("Campagne {$campagne->codeCampagne} | recensements rattachés : $deplaces");

            // 1. ÉQUIPES ET AFFECTATIONS
            $villagesMo = $this->villagesDePrefecture('Mô');

            if ($villagesMo->isEmpty()) {
                throw new \RuntimeException('Aucun village trouvé pour la préfecture de Mô.');
            }

            // Récupérer l'ID de la préfecture de Mô pour y associer des barèmes de test
            $prefectureMoId = DB::table('prefectures')->where('nom', 'Mô')->value('idPrefecture');

            if ($prefectureMoId) {
                // S'assurer qu'il existe des barèmes actifs pour le Maïs (culture principale des parcelles)
                $maisId = DB::table('cultures')->where('nomCulture', 'Maïs')->value('idCulture');
                $ureeId = DB::table('intrants')->where('nom', 'like', '%Urée%')->value('idIntrant');
                $npkId  = DB::table('intrants')->where('nom', 'like', '%NPK%')->value('idIntrant');

                if ($maisId) {
                    if ($ureeId) {
                        BaremeAgronomique::updateOrCreate(
                            [
                                'prefecture_id' => $prefectureMoId,
                                'culture_id'    => $maisId,
                                'intrant_id'    => $ureeId,
                            ],
                            [
                                'dose_par_hectare'       => 100.00,
                                'plafond_max_producteur' => 250.00,
                                'unite'                  => 'kg/ha',
                                'est_actif'              => true,
                            ]
                        );
                    }
                    if ($npkId) {
                        BaremeAgronomique::updateOrCreate(
                            [
                                'prefecture_id' => $prefectureMoId,
                                'culture_id'    => $maisId,
                                'intrant_id'    => $npkId,
                            ],
                            [
                                'dose_par_hectare'       => 150.00,
                                'plafond_max_producteur' => 400.00,
                                'unite'                  => 'kg/ha',
                                'est_actif'              => true,
                            ]
                        );
                    }
                }
                $this->command->info("Barèmes agronomiques configurés pour la préfecture de Mô.");
            }

            $part   = max(1, (int) ceil($villagesMo->count() / 3));
            $eyram  = $villagesMo->slice(0, $part);
            $reste  = $villagesMo->slice($part)->values();
            $moitie = (int) ceil($reste->count() / 2);

            // [référence, nom, tél. superviseur, tél. agents, villages]
            $equipes = [
                ['EQ-MO-01', 'Équipe Eyram (Mô)', '92222001', ['95555001', '95555002', '95555003'], $eyram],
                ['EQ-MO-02', 'Équipe Mô 2',       '92222002', ['95555004', '95555005'],             $reste->slice(0, $moitie)],
                ['EQ-MO-03', 'Équipe Mô 3',       '92222003', ['95555006', '95555007'],             $reste->slice($moitie)],
            ];

            foreach ([
                ['Tchaoudjo',  'EQ-TCH-01', '92222004', ['95555008', '95555009']],
                ['Kozah',      'EQ-KOZ-01', '92222007', ['95555013', '95555014']],
                ['Agoé-Nyivé', 'EQ-AGO-01', '92222010', ['95555018', '95555019']],
            ] as [$prefNom, $ref, $superv, $agents]) {
                $equipes[] = [$ref, "Équipe $prefNom", $superv, $agents, $this->villagesDePrefecture($prefNom)->take(2)];
            }

            $affectationsParVillage = []; // village_id => [affectation_id, ids des agents]

            foreach ($equipes as [$ref, $nom, $telSuperviseur, $telAgents, $villages]) {
                $superviseur = User::where('telephone', $telSuperviseur)->first();

                if (! $superviseur) {
                    $this->command->warn("Superviseur $telSuperviseur introuvable : équipe $ref ignorée.");
                    continue;
                }

                $equipeId = $this->idOuCreer(
                    'equipes', 'idEquipe', ['reference' => $ref],
                    ['nom' => $nom, 'superviseur_id' => $superviseur->getKey(), 'statut' => 'ACTIVE']
                );

                $agentIds = User::whereIn('telephone', $telAgents)->pluck('id')->all();

                foreach ($agentIds as $userId) {
                    DB::table('equipe_membres')->updateOrInsert(
                        ['equipe_id' => $equipeId, 'user_id' => $userId],
                        ['created_at' => now(), 'updated_at' => now()]
                    );
                }

                foreach ($villages as $village) {
                    $affectationId = $this->idOuCreer(
                        'affectations', 'idAffectation',
                        ['campagne_id' => $campagneId, 'equipe_id' => $equipeId, 'village_id' => $village->idVillage],
                        [
                            'reference' => "AFF-C{$campagneId}-{$ref}-V{$village->idVillage}",
                            'dateDebut' => $debut,
                            'dateFin'   => $fin,
                            'statut'    => 'active',
                        ]
                    );

                    $affectationsParVillage[$village->idVillage] = [$affectationId, $agentIds];
                }
            }

            // 2. RECENSEMENTS → AFFECTATION + AGENT (seulement si pas déjà liés)
            $liens = 0;

            foreach ($affectationsParVillage as $villageId => [$affectationId, $agentIds]) {
                if (empty($agentIds)) {
                    continue;
                }

                $ids = DB::table('recensements')
                    ->join('maisons', 'maisons.idMaison', '=', 'recensements.maison_id')
                    ->where('maisons.village_id', $villageId)
                    ->whereNull('recensements.affectation_id')
                    ->pluck('recensements.idRecensement');

                foreach ($ids as $i => $idRecensement) {
                    DB::table('recensements')->where('idRecensement', $idRecensement)->update([
                        'affectation_id' => $affectationId,
                        'agent_id'       => $agentIds[$i % count($agentIds)],
                        'updated_at'     => now(),
                    ]);
                    $liens++;
                }
            }

            // 3. CHAÎNE AGRICOLE : exploitation → parcelles → cultures
            $cultureIds = DB::table('cultures')->orderBy('idCulture')->pluck('idCulture')
                ->map(fn ($v) => (int) $v)->all();

            if (empty($cultureIds)) {
                throw new \RuntimeException('La table cultures est vide : lancez CultureSeeder.');
            }

            // Culture ayant un barème actif (sinon la première culture)
            $cultureAvecBareme = BaremeAgronomique::where('est_actif', true)
                ->orderBy('idBareme')
                ->value('culture_id');

            $culturePrincipale = ($cultureAvecBareme && in_array((int) $cultureAvecBareme, $cultureIds, true))
                ? (int) $cultureAvecBareme
                : $cultureIds[0];

            $autres = array_values(array_diff($cultureIds, [$culturePrincipale]));

            $nbExploitations = $nbParcelles = $nbCultures = 0;

            foreach (DB::table('exploitants')->orderBy('idExploitant')->pluck('idExploitant') as $n) {
                $n = (int) $n;

                $exploitationId = DB::table('exploitations')->where('exploitant_id', $n)->value('idExploitation');

                if (! $exploitationId) {
                    $exploitationId = DB::table('exploitations')->insertGetId([
                        'uid'                => (string) Str::uuid(),
                        'exploitant_id'      => $n,
                        'typeExploitation'   => 'agricole',
                        'statutJuridique'    => 'individuelle',
                        'activitePrincipale' => 'culture_vivriere',
                        'created_at'         => now(),
                        'updated_at'         => now(),
                    ]);
                    $nbExploitations++;
                }

                if (DB::table('parcelles')->where('exploitation_id', $exploitationId)->exists()) {
                    continue; // déjà renseignée
                }

                $nbP = 1 + ($n % 3); // 1 à 3 parcelles

                for ($k = 1; $k <= $nbP; $k++) {
                    $superficie = round(0.5 + (($n * 7 + $k * 3) % 40) / 10, 2); // 0,5 à 4,4

                    $parcelleId = DB::table('parcelles')->insertGetId([
                        'uid'             => (string) Str::uuid(),
                        'exploitation_id' => $exploitationId,
                        'numeroParcelle'  => sprintf('P-%03d', $k),
                        'superficie'      => $superficie,
                        'statutParcelle'  => 'exploitee',
                        'modeFaireValoir' => 'proprietaire',
                        'modeIrrigation'  => 'pluvial',
                        'presenceArbres'  => false,
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ]);
                    $nbParcelles++;

                    // Parcelle 1 : culture avec barème. Les suivantes : autres cultures.
                    $cultureId = ($k === 1 || empty($autres))
                        ? $culturePrincipale
                        : $autres[($n + $k) % count($autres)];

                    DB::table('cultures_parcelles')->insert([
                        'uid'                => (string) Str::uuid(),
                        'parcelle_id'        => $parcelleId,
                        'culture_id'         => $cultureId,
                        'modeCulture'        => 'principale',
                        'superficieCultivee' => $superficie,
                        'irriguee'           => false,
                        'etatCulture'        => 'croissance',
                        'created_at'         => now(),
                        'updated_at'         => now(),
                    ]);
                    $nbCultures++;
                }
            }

            $this->command->info("Recensements liés aux affectations : $liens");
            $this->command->info("Exploitations : $nbExploitations | parcelles : $nbParcelles | cultures : $nbCultures");
        });
    }

    /** Villages d'une préfecture, via canton → commune. */
    private function villagesDePrefecture(string $nomPrefecture)
    {
        $prefectureId = DB::table('prefectures')->where('nom', $nomPrefecture)->value('idPrefecture');

        if (! $prefectureId) {
            return collect();
        }

        return DB::table('villages')
            ->join('cantons', 'cantons.idCanton', '=', 'villages.canton_id')
            ->join('communes', 'communes.idCommune', '=', 'cantons.commune_id')
            ->where('communes.prefecture_id', $prefectureId)
            ->orderBy('villages.idVillage')
            ->get(['villages.idVillage', 'villages.nom']);
    }

    /** Retourne l'id d'une ligne existante (selon $cle) ou la crée. */
    private function idOuCreer(string $table, string $pk, array $cle, array $valeurs): int
    {
        $existant = DB::table($table)->where("$table." . key($cle), reset($cle))->value($pk);
        // Version simplifiée robuste :
        $existant = DB::table($table)->where($cle)->value($pk);

        if ($existant) {
            return (int) $existant;
        }

        return (int) DB::table($table)->insertGetId(
            array_merge($cle, $valeurs, ['created_at' => now(), 'updated_at' => now()])
        );
    }
}