<?php

namespace Database\Seeders;

use App\Models\Prefecture;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Fonction de rattachement à une préfecture
        |--------------------------------------------------------------------------
        |
        | On utilise le nom de la préfecture et non son ID.
        |
        */
        $rattacherPrefecture = function (
            User $user,
            string $nomPrefecture
        ): void {

            $prefecture = Prefecture::query()
                ->where('nom', $nomPrefecture)
                ->firstOrFail();

            $rattachement = $user
                ->rattachementsPrefecture()
                ->firstOrNew([
                    'prefecture_id' => $prefecture->getKey(),
                ]);

            $rattachement->dateDebut ??= now()->startOfDay();
            $rattachement->dateFin = null;
            $rattachement->statut = 'actif';

            $rattachement->save();
        };


        /*
        |--------------------------------------------------------------------------
        | ADMINISTRATEUR
        |--------------------------------------------------------------------------
        |
        | L'administrateur n'est rattaché à aucune préfecture.
        |
        */

        User::updateOrCreate(
            [
                'telephone' => '90000001',
            ],
            [
                'name' => 'Administrateur SIRA-Mô',
                'email' => 'administrateur.siramo@gmail.com',
                'password' => Hash::make('password'),
                'telephone_verified_at' => now(),
                'statut' => true,
                'role_id' => 'R01',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | DPA - MÔ
        |--------------------------------------------------------------------------
        */

        $dpaMo = User::updateOrCreate(
            [
                'telephone' => '90000002',
            ],
            [
                'name' => 'DPA Mô',
                'email' => 'dpa.mo@gmail.com',
                'password' => Hash::make('password'),
                'telephone_verified_at' => now(),
                'statut' => true,
                'role_id' => 'R02',
            ]
        );

        $rattacherPrefecture($dpaMo, 'Mô');


        /*
        |--------------------------------------------------------------------------
        | SUPERVISEUR PRINCIPAL - MÔ
        |--------------------------------------------------------------------------
        */

        $superviseurPrincipalMo = User::updateOrCreate(
            [
                'telephone' => '90000003',
            ],
            [
                'name' => 'Superviseur Principal Mô',
                'email' => 'superviseur.principal.mo@gmail.com',
                'password' => Hash::make('password'),
                'telephone_verified_at' => now(),
                'statut' => true,
                'role_id' => 'R03',
            ]
        );

        $rattacherPrefecture($superviseurPrincipalMo, 'Mô');


        /*
        |--------------------------------------------------------------------------
        | TECHNICIEN - MÔ
        |--------------------------------------------------------------------------
        */

        $technicienMo = User::updateOrCreate(
            [
                'telephone' => '90000004',
            ],
            [
                'name' => 'Technicien Mô',
                'email' => 'technicien.mo@gmail.com',
                'password' => Hash::make('password'),
                'telephone_verified_at' => now(),
                'statut' => true,
                'role_id' => 'R04',
            ]
        );

        $rattacherPrefecture($technicienMo, 'Mô');


        /*
        |--------------------------------------------------------------------------
        | CACH - MÔ
        |--------------------------------------------------------------------------
        */

        $cachMo = User::updateOrCreate(
            [
                'telephone' => '90000005',
            ],
            [
                'name' => 'CACH Mô',
                'email' => 'cach.mo@gmail.com',
                'password' => Hash::make('password'),
                'telephone_verified_at' => now(),
                'statut' => true,
                'role_id' => 'R05',
            ]
        );

        $rattacherPrefecture($cachMo, 'Mô');


        /*
        |--------------------------------------------------------------------------
        | AGENT RECENSEUR PRINCIPAL - MÔ
        |--------------------------------------------------------------------------
        */

        $agentPrincipalMo = User::updateOrCreate(
            [
                'telephone' => '90000006',
            ],
            [
                'name' => 'Agent Recenseur Principal Mô',
                'email' => 'agent.principal.mo@gmail.com',
                'password' => Hash::make('password'),
                'telephone_verified_at' => now(),
                'statut' => true,
                'role_id' => 'R06',
            ]
        );

        $rattacherPrefecture($agentPrincipalMo, 'Mô');


        /*
        |--------------------------------------------------------------------------
        | SUPERVISEURS
        |--------------------------------------------------------------------------
        |
        | Chaque superviseur possède sa propre préfecture.
        |
        */

        $superviseurs = [
            [
                'name' => 'Kokou Abalo',
                'telephone' => '91000001',
                'email' => 'kokou.abalo@gmail.com',
                'prefecture' => 'Mô',
            ],
            [
                'name' => 'Essowe Tete',
                'telephone' => '91000002',
                'email' => 'essowe.tete@gmail.com',
                'prefecture' => 'Mô',
            ],
            [
                'name' => 'Bena Tchala',
                'telephone' => '91000003',
                'email' => 'bena.tchala@gmail.com',
                'prefecture' => 'Assoli',
            ],
            [
                'name' => 'Koffi Mensah',
                'telephone' => '91000004',
                'email' => 'koffi.mensah@gmail.com',
                'prefecture' => 'Tchaoudjo',
            ],
            [
                'name' => 'Kodjo Amégan',
                'telephone' => '91000005',
                'email' => 'kodjo.amegan@gmail.com',
                'prefecture' => 'Sotouboua',
            ],
        ];

        foreach ($superviseurs as $data) {

            $superviseur = User::updateOrCreate(
                [
                    'telephone' => $data['telephone'],
                ],
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make('password'),
                    'telephone_verified_at' => now(),
                    'statut' => true,
                    'role_id' => 'R03',
                ]
            );

            $rattacherPrefecture(
                $superviseur,
                $data['prefecture']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | AGENTS RECENSEURS
        |--------------------------------------------------------------------------
        |
        | Chaque agent est rattaché à sa préfecture.
        |
        */

        $agents = [
            [
                'name' => 'Afi Eyram',
                'telephone' => '92000001',
                'email' => 'afi.eyram@gmail.com',
                'prefecture' => 'Mô',
            ],
            [
                'name' => 'Akouvi Mensah',
                'telephone' => '92000002',
                'email' => 'akouvi.mensah@gmail.com',
                'prefecture' => 'Mô',
            ],
            [
                'name' => 'Anani Kodjo',
                'telephone' => '92000003',
                'email' => 'anani.kodjo@gmail.com',
                'prefecture' => 'Mô',
            ],
            [
                'name' => 'Kossi Agbeko',
                'telephone' => '92000004',
                'email' => 'kossi.agbeko@gmail.com',
                'prefecture' => 'Assoli',
            ],
            [
                'name' => 'Ama Adjo',
                'telephone' => '92000005',
                'email' => 'ama.adjo@gmail.com',
                'prefecture' => 'Assoli',
            ],
            [
                'name' => 'Koffi Atsu',
                'telephone' => '92000006',
                'email' => 'koffi.atsu@gmail.com',
                'prefecture' => 'Tchaoudjo',
            ],
            [
                'name' => 'Esi Dede',
                'telephone' => '92000007',
                'email' => 'esi.dede@gmail.com',
                'prefecture' => 'Tchaoudjo',
            ],
            [
                'name' => 'Komlan Yao',
                'telephone' => '92000008',
                'email' => 'komlan.yao@gmail.com',
                'prefecture' => 'Sotouboua',
            ],
            [
                'name' => 'Abla Kossi',
                'telephone' => '92000009',
                'email' => 'abla.kossi@gmail.com',
                'prefecture' => 'Sotouboua',
            ],
            [
                'name' => 'Mawuli Sena',
                'telephone' => '92000010',
                'email' => 'mawuli.sena@gmail.com',
                'prefecture' => 'Mô',
            ],
        ];

        foreach ($agents as $data) {

            $agent = User::updateOrCreate(
                [
                    'telephone' => $data['telephone'],
                ],
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make('password'),
                    'telephone_verified_at' => now(),
                    'statut' => true,
                    'role_id' => 'R06',
                ]
            );

            $rattacherPrefecture(
                $agent,
                $data['prefecture']
            );
        }
    }
}