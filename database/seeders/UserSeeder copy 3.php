<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Prefecture;
use App\Models\RattachementPrefecture;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Crée les utilisateurs de démonstration
     * et leurs rattachements aux préfectures.
     */
    public function run(): void
    {
        DB::transaction(function () {

            /*
            |--------------------------------------------------------------------------
            | RÔLES
            |--------------------------------------------------------------------------
            | On récupère les rôles par leur nom.
            | Aucun ID de rôle n'est écrit en dur.
            |--------------------------------------------------------------------------
            */

            $roles = Role::query()
                ->get()
                ->keyBy('nom');

            $roleAdministrateur = $roles->get('Administrateur');
            $roleDpa            = $roles->get('DPA');
            $roleSuperviseur    = $roles->get('Superviseur');
            $roleTechnicien     = $roles->get('Technicien');
            $roleCach           = $roles->get('CACH');
            $roleAgent          = $roles->get('Agent recenseur');

            if (!$roleAdministrateur) {
                throw new \RuntimeException(
                    'Le rôle "Administrateur" n’existe pas.'
                );
            }

            if (!$roleDpa) {
                throw new \RuntimeException(
                    'Le rôle "DPA" n’existe pas.'
                );
            }

            if (!$roleSuperviseur) {
                throw new \RuntimeException(
                    'Le rôle "Superviseur" n’existe pas.'
                );
            }

            if (!$roleTechnicien) {
                throw new \RuntimeException(
                    'Le rôle "Technicien" n’existe pas.'
                );
            }

            if (!$roleCach) {
                throw new \RuntimeException(
                    'Le rôle "CACH" n’existe pas.'
                );
            }

            if (!$roleAgent) {
                throw new \RuntimeException(
                    'Le rôle "Agent recenseur" n’existe pas.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | PRÉFECTURES
            |--------------------------------------------------------------------------
            | Les noms correspondent aux valeurs présentes dans la base.
            |--------------------------------------------------------------------------
            */

            $prefectures = Prefecture::query()
                ->whereIn('nom', [
                    'Mô',
                    'Tchaoudjo',
                    'Kozah',
                    'Agoé-Nyivé',
                ])
                ->get()
                ->keyBy('nom');


            foreach ([
                'Mô',
                'Tchaoudjo',
                'Kozah',
                'Agoé-Nyivé',
            ] as $nomPrefecture) {

                if (!$prefectures->has($nomPrefecture)) {
                    throw new \RuntimeException(
                        "La préfecture \"{$nomPrefecture}\" n’existe pas."
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | FONCTION DE CRÉATION D'UN UTILISATEUR
            |--------------------------------------------------------------------------
            */

            $creerUtilisateur = function (
                string $nom,
                string $telephone,
                Role $role,
                ?string $prefectureNom = null
            ) use ($prefectures) {

                /*
                | L'adresse e-mail reste générée uniquement parce que
                | la colonne email peut encore être obligatoire dans users.
                |
                | Le LOGIN est totalement supprimé.
                */

                $email = strtolower(
                    str_replace(
                        [' ', 'é', 'è', 'ê', 'ë', 'à', 'â', 'ä', 'î', 'ï', 'ô', 'ö', 'ù', 'û', 'ü', 'ç'],
                        ['.', 'e', 'e', 'e', 'e', 'a', 'a', 'a', 'i', 'i', 'o', 'o', 'u', 'u', 'u', 'c'],
                        $nom
                    )
                );

                $email = preg_replace('/[^a-z0-9.]/', '', $email);

                $email = $email . '@gmail.com';


                /*
                |--------------------------------------------------------------------------
                | CRÉATION / MISE À JOUR
                |--------------------------------------------------------------------------
                */

                $user = User::query()->updateOrCreate(
                    [
                        'telephone' => $telephone,
                    ],
                    [
                        'name' => $nom,
                        'email' => $email,
                        'telephone' => $telephone,
                        'password' => Hash::make('password'),
                        'role_id' => $role->getKey(),
                        'statut' => true,
                        'telephone_verified_at' => now(),
                    ]
                );


                /*
                |--------------------------------------------------------------------------
                | RATTACHEMENT À UNE PRÉFECTURE
                |--------------------------------------------------------------------------
                */

                if ($prefectureNom !== null) {

                    $prefecture = $prefectures->get($prefectureNom);

                    RattachementPrefecture::query()->updateOrCreate(
                        [
                            'user_id' => $user->getKey(),
                        ],
                        [
                            'prefecture_id' => $prefecture->getKey(),
                        ]
                    );
                }

                return $user;
            };


            /*
            |--------------------------------------------------------------------------
            | ADMINISTRATEUR
            |--------------------------------------------------------------------------
            | L'administrateur n'est rattaché à aucune préfecture.
            |--------------------------------------------------------------------------
            */

            $creerUtilisateur(
                'Koffi Mensah',
                '90000001',
                $roleAdministrateur
            );


            /*
            |--------------------------------------------------------------------------
            | DPA
            |--------------------------------------------------------------------------
            */

            $creerUtilisateur(
                'Kodjo Agbeko',
                '90000002',
                $roleDpa,
                'Mô'
            );


            /*
            |--------------------------------------------------------------------------
            | SUPERVISEUR PRINCIPAL
            |--------------------------------------------------------------------------
            */

            $creerUtilisateur(
                'Kossi Amouzou',
                '92222001',
                $roleSuperviseur,
                'Mô'
            );


            /*
            |--------------------------------------------------------------------------
            | TECHNICIEN
            |--------------------------------------------------------------------------
            */

            $creerUtilisateur(
                'Yawovi Lawson',
                '90000003',
                $roleTechnicien,
                'Mô'
            );


            /*
            |--------------------------------------------------------------------------
            | CACH
            |--------------------------------------------------------------------------
            */

            $creerUtilisateur(
                'Ama Koudjo',
                '90000004',
                $roleCach,
                'Mô'
            );


            /*
            |--------------------------------------------------------------------------
            | AGENT RECENSEUR PRINCIPAL
            |--------------------------------------------------------------------------
            */

            $creerUtilisateur(
                'Komlan Adjeoda',
                '95555001',
                $roleAgent,
                'Mô'
            );


            /*
            |--------------------------------------------------------------------------
            | SUPERVISEURS
            |--------------------------------------------------------------------------
            |
            | Répartition :
            |
            | Mô          : 3
            | Tchaoudjo   : 3
            | Kozah       : 3
            | Agoé-Nyivé  : 2
            |
            |--------------------------------------------------------------------------
            */

            $superviseurs = [

                [
                    'nom' => 'Abalo Kokou',
                    'telephone' => '92222002',
                    'prefecture' => 'Mô',
                ],

                [
                    'nom' => 'Akakpo Mawuli',
                    'telephone' => '92222003',
                    'prefecture' => 'Mô',
                ],

                [
                    'nom' => 'Agbo Komi',
                    'telephone' => '92222004',
                    'prefecture' => 'Tchaoudjo',
                ],

                [
                    'nom' => 'Akouete Esso',
                    'telephone' => '92222005',
                    'prefecture' => 'Tchaoudjo',
                ],

                [
                    'nom' => 'Ayite Sena',
                    'telephone' => '92222006',
                    'prefecture' => 'Tchaoudjo',
                ],

                [
                    'nom' => 'Bawa Tchagnao',
                    'telephone' => '92222007',
                    'prefecture' => 'Kozah',
                ],

                [
                    'nom' => 'Dodji Gnama',
                    'telephone' => '92222008',
                    'prefecture' => 'Kozah',
                ],

                [
                    'nom' => 'Eklu Mawuko',
                    'telephone' => '92222009',
                    'prefecture' => 'Kozah',
                ],

                [
                    'nom' => 'Essowè Tete',
                    'telephone' => '92222010',
                    'prefecture' => 'Agoé-Nyivé',
                ],

                [
                    'nom' => 'Tchanile Komlan',
                    'telephone' => '92222011',
                    'prefecture' => 'Agoé-Nyivé',
                ],
            ];


            foreach ($superviseurs as $superviseur) {

                $creerUtilisateur(
                    $superviseur['nom'],
                    $superviseur['telephone'],
                    $roleSuperviseur,
                    $superviseur['prefecture']
                );
            }


            /*
            |--------------------------------------------------------------------------
            | AGENTS RECENSEURS
            |--------------------------------------------------------------------------
            |
            | Répartition :
            |
            | Mô          : 6
            | Tchaoudjo   : 5
            | Kozah       : 5
            | Agoé-Nyivé  : 5
            |
            |--------------------------------------------------------------------------
            */

            $agents = [

                /*
                |--------------------------------------------------------------------------
                | MÔ — 6 agents
                |--------------------------------------------------------------------------
                */

                [
                    'nom' => 'Afi Eyram',
                    'telephone' => '95555002',
                    'prefecture' => 'Mô',
                ],

                [
                    'nom' => 'Akouvi Mensah',
                    'telephone' => '95555003',
                    'prefecture' => 'Mô',
                ],

                [
                    'nom' => 'Amegbor Kossi',
                    'telephone' => '95555004',
                    'prefecture' => 'Mô',
                ],

                [
                    'nom' => 'Anani Kodjo',
                    'telephone' => '95555005',
                    'prefecture' => 'Mô',
                ],

                [
                    'nom' => 'Atayi Mawuli',
                    'telephone' => '95555006',
                    'prefecture' => 'Mô',
                ],

                [
                    'nom' => 'Ayélé Esso',
                    'telephone' => '95555007',
                    'prefecture' => 'Mô',
                ],


                /*
                |--------------------------------------------------------------------------
                | TCHAoudjo — 5 agents
                |--------------------------------------------------------------------------
                */

                [
                    'nom' => 'Bèna Tchala',
                    'telephone' => '95555008',
                    'prefecture' => 'Tchaoudjo',
                ],

                [
                    'nom' => 'Blaise Adjakpa',
                    'telephone' => '95555009',
                    'prefecture' => 'Tchaoudjo',
                ],

                [
                    'nom' => 'Dodzi Koffi',
                    'telephone' => '95555010',
                    'prefecture' => 'Tchaoudjo',
                ],

                [
                    'nom' => 'Edem Gnakade',
                    'telephone' => '95555011',
                    'prefecture' => 'Tchaoudjo',
                ],

                [
                    'nom' => 'Eli Kpodar',
                    'telephone' => '95555012',
                    'prefecture' => 'Tchaoudjo',
                ],


                /*
                |--------------------------------------------------------------------------
                | KOZAH — 5 agents
                |--------------------------------------------------------------------------
                */

                [
                    'nom' => 'Essi Akossiwa',
                    'telephone' => '95555013',
                    'prefecture' => 'Kozah',
                ],

                [
                    'nom' => 'Essowè Yawo',
                    'telephone' => '95555014',
                    'prefecture' => 'Kozah',
                ],

                [
                    'nom' => 'Kokouvi Agbeko',
                    'telephone' => '95555015',
                    'prefecture' => 'Kozah',
                ],

                [
                    'nom' => 'Komi Bawa',
                    'telephone' => '95555016',
                    'prefecture' => 'Kozah',
                ],

                [
                    'nom' => 'Komlan Tchalla',
                    'telephone' => '95555017',
                    'prefecture' => 'Kozah',
                ],


                /*
                |--------------------------------------------------------------------------
                | AGOÉ-NYIVÉ — 5 agents
                |--------------------------------------------------------------------------
                */

                [
                    'nom' => 'Kossi Adom',
                    'telephone' => '95555018',
                    'prefecture' => 'Agoé-Nyivé',
                ],

                [
                    'nom' => 'Mawuko Tete',
                    'telephone' => '95555019',
                    'prefecture' => 'Agoé-Nyivé',
                ],

                [
                    'nom' => 'Sena Agbenyo',
                    'telephone' => '95555020',
                    'prefecture' => 'Agoé-Nyivé',
                ],

                [
                    'nom' => 'Yawovi Dossou',
                    'telephone' => '95555021',
                    'prefecture' => 'Agoé-Nyivé',
                ],

                /*
                |--------------------------------------------------------------------------
                | 20 agents demandés initialement
                |--------------------------------------------------------------------------
                | Le dernier agent est ajouté à Agoé-Nyivé pour obtenir
                | exactement 21 agents au total avec l'agent principal.
                |--------------------------------------------------------------------------
                */

                [
                    'nom' => 'Essi Kossi',
                    'telephone' => '95555022',
                    'prefecture' => 'Agoé-Nyivé',
                ],
            ];


            foreach ($agents as $agent) {

                $creerUtilisateur(
                    $agent['nom'],
                    $agent['telephone'],
                    $roleAgent,
                    $agent['prefecture']
                );
            }


            /*
            |--------------------------------------------------------------------------
            | MESSAGE
            |--------------------------------------------------------------------------
            */

            $this->command->info(
                'Utilisateurs créés avec succès.'
            );

            $this->command->info(
                'Aucun champ "login" n’est utilisé.'
            );

            $this->command->info(
                'Mot de passe par défaut : password'
            );

            $this->command->info(
                'Répartition : Mô, Tchaoudjo, Kozah et Agoé-Nyivé.'
            );
        });
    }
}
