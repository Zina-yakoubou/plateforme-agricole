<?php

namespace Database\Seeders;

use App\Models\Affectation;
use App\Models\CampagneDeploiement;
use App\Models\CampagneRecensement;
use App\Models\CampagneZone;
use App\Models\Equipe;
use App\Models\Exploitant;
use App\Models\Maison;
use App\Models\Menage;
use App\Models\Prefecture;
use App\Models\Recensement;
use App\Models\User;
use App\Models\Village;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RecensementMockSeeder extends Seeder
{
    /**
     * Groupes (équipes) de test de la préfecture de Mô.
     * Les utilisateurs viennent de UserSeeder (retrouvés par téléphone).
     *
     * Comptes de test principaux :
     *  - Kossi Amouzou (92222001) : superviseur de 2 équipes (A et B)
     *  - Afi Eyram     (95555002) : membre des équipes A et B
     *
     * Cas couverts :
     *  - Kossi voit 2 équipes, avec des membres différents ;
     *  - Afi travaille dans 2 équipes, donc a 2 villages affectés ;
     *  - l'équipe C (autre superviseur) ne doit PAS apparaître
     *    pour Kossi ni pour Afi ;
     *  - l'agent 95555007 (Ayélé Esso) n'est dans aucune équipe.
     */
    private const GROUPES = [
        [
            'reference' => 'EQ-MO-01',
            'nom' => 'Équipe Mô A',
            'superviseur' => '92222001', // Kossi Amouzou
            'agents' => [
                '95555002', // Afi Eyram
                '95555001', // Komlan Adjeoda
                '95555003', // Akouvi Mensah
            ],
        ],
        [
            'reference' => 'EQ-MO-02',
            'nom' => 'Équipe Mô B',
            'superviseur' => '92222001', // Kossi Amouzou
            'agents' => [
                '95555002', // Afi Eyram
                '95555004', // Amegbor Kossi
            ],
        ],
        [
            'reference' => 'EQ-MO-03',
            'nom' => 'Équipe Mô C',
            'superviseur' => '92222002', // Abalo Kokou
            'agents' => [
                '95555005', // Anani Kodjo
                '95555006', // Atayi Mawuli
            ],
        ],
    ];

    public function run(): void
    {
        DB::transaction(function (): void {
            /*
             * 1. Vérifier les données nécessaires.
             */
            $prefecture = Prefecture::query()
                ->whereIn('nom', ['Mô', 'Mo'])
                ->first()
                ?? Prefecture::query()
                    ->where('nom', 'like', '%Mô%')
                    ->first();

            if (!$prefecture) {
                $this->command?->error('Préfecture de Mô introuvable.');

                return;
            }

            $prefectureId = $prefecture->idPrefecture;

            $villages = Village::query()
                ->whereHas(
                    'canton.commune.prefecture',
                    fn ($q) => $q->where('idPrefecture', $prefectureId)
                )
                ->orderBy('idVillage')
                ->get()
                ->values();

            if ($villages->isEmpty()) {
                $this->command?->error(
                    'Aucun village rattaché à la préfecture de Mô.'
                );

                return;
            }

            $campagnes = CampagneRecensement::query()
                ->whereIn('statut', ['active', 'cloturee'])
                ->orderBy('idCampagne')
                ->get();

            if ($campagnes->isEmpty()) {
                $this->command?->error(
                    'Aucune campagne active ou clôturée disponible.'
                );

                return;
            }

            /*
             * 2. Créer les équipes de Mô et leurs membres.
             */
            $equipes = [];

            foreach (self::GROUPES as $groupe) {
                $superviseur = User::query()
                    ->where('telephone', $groupe['superviseur'])
                    ->first();

                $agentsParTelephone = User::query()
                    ->whereIn('telephone', $groupe['agents'])
                    ->get()
                    ->keyBy('telephone');

                // On garde l'ordre défini dans GROUPES.
                $membres = collect($groupe['agents'])
                    ->map(fn ($tel) => $agentsParTelephone->get($tel))
                    ->filter()
                    ->values();

                if (!$superviseur || $membres->count() !== count($groupe['agents'])) {
                    $this->command?->error(
                        "Utilisateurs manquants pour {$groupe['nom']}. "
                        . 'Lance d’abord UserSeeder.'
                    );

                    return;
                }

                $equipe = Equipe::query()->updateOrCreate(
                    ['reference' => $groupe['reference']],
                    [
                        'nom' => $groupe['nom'],
                        'superviseur_id' => $superviseur->getKey(),
                        'statut' => 'active',
                    ]
                );

                $equipe->membres()->syncWithoutDetaching(
                    $membres->pluck('id')->all()
                );

                $equipes[] = [
                    'equipe' => $equipe,
                    'membres' => $membres,
                ];
            }

            $totalZones = 0;
            $totalDeploiements = 0;
            $totalAffectations = 0;
            $totalRecensements = 0;
            $totalMenages = 0;
            $totalExploitants = 0;
            $totalMaisons = 0;

            /*
             * 3. Chaîne : campagne → zone → déploiement → affectation → ...
             */
            foreach ($campagnes as $campagne) {
                $zone = CampagneZone::firstOrCreate(
                    [
                        'campagne_id' => $campagne->idCampagne,
                        'prefecture_id' => $prefectureId,
                    ],
                    [
                        'dateDebut' => $campagne->dateDebut,
                        'dateFin' => $campagne->dateFin,
                        'statut' => $campagne->statut,
                    ]
                );

                if ($zone->wasRecentlyCreated) {
                    $totalZones++;
                }

                $deploiement = CampagneDeploiement::firstOrCreate(
                    [
                        'campagne_id' => $campagne->idCampagne,
                        'prefecture_id' => $prefectureId,
                    ],
                    [
                        'statut' => 'notifiee',
                    ]
                );

                if ($deploiement->wasRecentlyCreated) {
                    $totalDeploiements++;
                }

                /*
                 * Une équipe par village (A → 1er village, B → 2e, C → 3e).
                 */
                foreach ($equipes as $index => $groupe) {
                    $village = $villages->get($index);

                    if (!$village) {
                        continue;
                    }

                    $equipe = $groupe['equipe'];
                    $membres = $groupe['membres'];

                    $affectation = Affectation::query()
                        ->where('campagne_id', $campagne->idCampagne)
                        ->where('equipe_id', $equipe->getKey())
                        ->where('village_id', $village->getKey())
                        ->first();

                    if (!$affectation) {
                        $affectation = Affectation::create([
                            'reference' => sprintf(
                                'AFF-MOCK-%04d-%04d',
                                $campagne->idCampagne,
                                $village->getKey()
                            ),
                            'campagne_id' => $campagne->idCampagne,
                            'equipe_id' => $equipe->getKey(),
                            'village_id' => $village->getKey(),
                            'dateDebut' => $campagne->dateDebut,
                            'dateFin' => $campagne->dateFin,
                            'statut' => $campagne->statut === 'cloturee'
                                ? 'terminee'
                                : 'active',
                            'observations' => 'Affectation de démonstration',
                        ]);

                        $totalAffectations++;
                    }

                    /*
                     * Trois maisons par village.
                     * numeroMaison est unique dans toute la table.
                     */
                    for ($numero = 1; $numero <= 3; $numero++) {
                        $maisonsDuVillage = Maison::query()
                            ->where('village_id', $village->getKey())
                            ->orderBy('numeroMaison')
                            ->get();

                        if ($maisonsDuVillage->count() >= $numero) {
                            $maison = $maisonsDuVillage->get($numero - 1);
                        } else {
                            $dernierNumero = (int) Maison::query()
                                ->max('numeroMaison');

                            $maison = Maison::create([
                                'numeroMaison' => $dernierNumero + 1,
                                'village_id' => $village->getKey(),
                                'uid' => (string) Str::uuid(),
                            ]);

                            $totalMaisons++;
                        }

                        /*
                         * Recensement réalisé par un membre de l'équipe
                         * (les agents se relaient d'une maison à l'autre).
                         */
                        $agent = $membres->get(($numero - 1) % $membres->count());

                        $recensement = Recensement::query()
                            ->where('campagne_id', $campagne->idCampagne)
                            ->where('maison_id', $maison->getKey())
                            ->first();

                        if (!$recensement) {
                            $recensement = Recensement::create([
                                'uid' => (string) Str::uuid(),
                                'campagne_id' => $campagne->idCampagne,
                                'affectation_id' => $affectation->getKey(),
                                'agent_id' => $agent->id,
                                'maison_id' => $maison->getKey(),
                                'statut' => $campagne->statut === 'cloturee'
                                    ? 'termine'
                                    : ($numero === 1
                                        ? 'en_cours'
                                        : 'termine'),
                                'dateDerniereModification' => now(),
                            ]);

                            $totalRecensements++;
                        }

                        if (Menage::query()
                            ->where('recensement_id', $recensement->getKey())
                            ->exists()) {
                            continue;
                        }

                        $menage = Menage::create([
                            'recensement_id' => $recensement->getKey(),
                            'numeroMenage' => $numero,
                            'nomChef' => 'Kossi',
                            'prenomChef' => 'Mensah ' . $numero,
                            'sexeChef' => 'M',
                            'nombreHommes' => 2,
                            'nombreFemmes' => 3,
                            'nombreGarcons' => 1,
                            'nombreFilles' => 2,
                            'possedeExploitation' => true,
                            'uid' => (string) Str::uuid(),
                        ]);

                        $totalMenages++;

                        /*
                         * Téléphone dérivé de l'id du ménage : toujours unique.
                         */
                        Exploitant::create([
                            'menage_id' => $menage->getKey(),
                            'nom' => 'Kossi',
                            'prenom' => 'Mensah ' . $numero,
                            'sexe' => 'M',
                            'telephone' => sprintf('991%05d', $menage->getKey()),
                            'uid' => (string) Str::uuid(),
                        ]);

                        $totalExploitants++;
                    }
                }
            }

            /*
             * 4. Résumé.
             */
            $this->command?->info('Seeding terminé.');
            $this->command?->info('Préfecture : ' . $prefecture->nom);
            $this->command?->info('Équipes de test : ' . count($equipes));
            $this->command?->info("Nouvelles zones : {$totalZones}");
            $this->command?->info("Nouveaux déploiements : {$totalDeploiements}");
            $this->command?->info("Nouvelles maisons : {$totalMaisons}");
            $this->command?->info("Nouvelles affectations : {$totalAffectations}");
            $this->command?->info("Nouveaux recensements : {$totalRecensements}");
            $this->command?->info("Nouveaux ménages : {$totalMenages}");
            $this->command?->info("Nouveaux exploitants : {$totalExploitants}");
        });
    }
}