<?php

namespace Database\Seeders;

use App\Models\Prefecture;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Création des utilisateurs de test SIRA-Mô.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Préfecture de Mô
        |--------------------------------------------------------------------------
        |
        | On récupère la préfecture depuis la base.
        | Aucun ID brut n'est utilisé.
        |
        */
        $prefectureMo = Prefecture::query()
            ->where('nom', 'Mô')
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Fonction de création d'un utilisateur
        |--------------------------------------------------------------------------
        */
        $creerUtilisateur = function (array $data) use ($prefectureMo): User {
            $user = User::updateOrCreate(
                [
                    'telephone' => $data['telephone'],
                ],
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make('password'),
                    'telephone_verified_at' => now(),
                    'statut' => true,
                    'role_id' => $data['role_id'],
                ]
            );

            /*
             * Le compte est rattaché à la préfecture de Mô
             * sauf pour l'administrateur.
             */
            if (! empty($data['rattacher_prefecture'])) {

                $rattachement = $user
                    ->rattachementsPrefecture()
                    ->firstOrNew([
                        'prefecture_id' => $prefectureMo->getKey(),
                    ]);

                $rattachement->dateDebut ??= now()->startOfDay();
                $rattachement->dateFin = null;
                $rattachement->statut = 'actif';

                $rattachement->save();
            }

            return $user;
        };

        /*
        |--------------------------------------------------------------------------
        | Administrateur
        |--------------------------------------------------------------------------
        */
        $creerUtilisateur([
            'name' => 'Koffi Mensah',
            'email' => 'koffi.mensah@gmail.com',
            'telephone' => '90000000',
            'role_id' => 'R01',
            'rattacher_prefecture' => false,
        ]);

        /*
        |--------------------------------------------------------------------------
        | DPA
        |--------------------------------------------------------------------------
        */
        $creerUtilisateur([
            'name' => 'Kodjo Agbeko',
            'email' => 'kodjo.agbeko@gmail.com',
            'telephone' => '91111111',
            'role_id' => 'R02',
            'rattacher_prefecture' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Superviseur principal
        |--------------------------------------------------------------------------
        */
        $creerUtilisateur([
            'name' => 'Kossi Amouzou',
            'email' => 'kossi.amouzou@gmail.com',
            'telephone' => '92222222',
            'role_id' => 'R03',
            'rattacher_prefecture' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Technicien
        |--------------------------------------------------------------------------
        */
        $creerUtilisateur([
            'name' => 'Yawovi Lawson',
            'email' => 'yawovi.lawson@gmail.com',
            'telephone' => '93333333',
            'role_id' => 'R04',
            'rattacher_prefecture' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | CACH
        |--------------------------------------------------------------------------
        */
        $creerUtilisateur([
            'name' => 'Ama Koudjo',
            'email' => 'ama.koudjo@gmail.com',
            'telephone' => '94444444',
            'role_id' => 'R05',
            'rattacher_prefecture' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Agent recenseur principal
        |--------------------------------------------------------------------------
        */
        $creerUtilisateur([
            'name' => 'Komlan Adjeoda',
            'email' => 'komlan.adjeoda@gmail.com',
            'telephone' => '95555555',
            'role_id' => 'R06',
            'rattacher_prefecture' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | 10 SUPERVISEURS
        |--------------------------------------------------------------------------
        */
        $superviseurs = [
            'Abalo Kokou',
            'Akakpo Mawuli',
            'Agbo Komi',
            'Akouete Esso',
            'Ayite Sena',
            'Bawa Tchagnao',
            'Dodji Gnama',
            'Eklu Mawuko',
            'Essowè Tete',
            'Tchanile Komlan',
        ];

        foreach ($superviseurs as $index => $nom) {

            $numero = $index + 1;

            $creerUtilisateur([
                'name' => $nom,
                'email' => 'superviseur' . $numero . '@siramo.test',
                'telephone' => '92222' . str_pad(
                    $numero,
                    3,
                    '0',
                    STR_PAD_LEFT
                ),
                'role_id' => 'R03',
                'rattacher_prefecture' => true,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 20 AGENTS RECENSEURS
        |--------------------------------------------------------------------------
        */
        $agents = [
            'Afi Eyram',
            'Akouvi Mensah',
            'Amegbor Kossi',
            'Anani Kodjo',
            'Atayi Mawuli',
            'Ayélé Esso',
            'Bèna Tchala',
            'Blaise Adjakpa',
            'Dodzi Koffi',
            'Edem Gnakade',
            'Eli Kpodar',
            'Essi Akossiwa',
            'Essowè Yawo',
            'Kokouvi Agbeko',
            'Komi Bawa',
            'Komlan Tchalla',
            'Kossi Adom',
            'Mawuko Tete',
            'Sena Agbenyo',
            'Yawovi Dossou',
        ];

        foreach ($agents as $index => $nom) {

            $numero = $index + 1;

            $creerUtilisateur([
                'name' => $nom,
                'email' => 'agent' . $numero . '@siramo.test',
                'telephone' => '95555' . str_pad(
                    $numero,
                    3,
                    '0',
                    STR_PAD_LEFT
                ),
                'role_id' => 'R06',
                'rattacher_prefecture' => true,
            ]);
        }
    }
}