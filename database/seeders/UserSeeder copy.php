<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Création des utilisateurs de test SIRA-Mô.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Administrateur
        |--------------------------------------------------------------------------
        */
        User::updateOrCreate(
            ['telephone' => '90000000'],
            [
                'name' => 'Koffi Mensah',
                'email' => 'koffi.mensah@gmail.com',
                'password' => 'password',
                'telephone_verified_at' => now(),
                'statut' => true,
                'role_id' => 'R01',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | DPA
        |--------------------------------------------------------------------------
        */
        User::updateOrCreate(
            ['telephone' => '91111111'],
            [
                'name' => 'Kodjo Agbeko',
                'email' => 'kodjo.agbeko@gmail.com',
                'password' => 'password',
                'telephone_verified_at' => now(),
                'statut' => true,
                'role_id' => 'R02',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Superviseur principal
        |--------------------------------------------------------------------------
        */
        User::updateOrCreate(
            ['telephone' => '92222222'],
            [
                'name' => 'Kossi Amouzou',
                'email' => 'kossi.amouzou@gmail.com',
                'password' => 'password',
                'telephone_verified_at' => now(),
                'statut' => true,
                'role_id' => 'R03',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Technicien
        |--------------------------------------------------------------------------
        */
        User::updateOrCreate(
            ['telephone' => '93333333'],
            [
                'name' => 'Yawovi Lawson',
                'email' => 'yawovi.lawson@gmail.com',
                'password' => 'password',
                'telephone_verified_at' => now(),
                'statut' => true,
                'role_id' => 'R04',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | CACH
        |--------------------------------------------------------------------------
        */
        User::updateOrCreate(
            ['telephone' => '94444444'],
            [
                'name' => 'Ama Koudjo',
                'email' => 'ama.koudjo@gmail.com',
                'password' => 'password',
                'telephone_verified_at' => now(),
                'statut' => true,
                'role_id' => 'R05',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Agent recenseur principal
        |--------------------------------------------------------------------------
        */
        User::updateOrCreate(
            ['telephone' => '95555555'],
            [
                'name' => 'Komlan Adjeoda',
                'email' => 'komlan.adjeoda@gmail.com',
                'password' => 'password',
                'telephone_verified_at' => now(),
                'statut' => true,
                'role_id' => 'R06',
            ]
        );

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

            User::updateOrCreate(
                ['telephone' => '92222' . str_pad($index + 1, 3, '0', STR_PAD_LEFT)],
                [
                    'name' => $nom,
                    'email' => 'superviseur' . ($index + 1) . '@siramo.test',
                    'password' => 'password',
                    'telephone_verified_at' => now(),
                    'statut' => true,
                    'role_id' => 'R03',
                ]
            );
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

            User::updateOrCreate(
                ['telephone' => '95555' . str_pad($index + 1, 3, '0', STR_PAD_LEFT)],
                [
                    'name' => $nom,
                    'email' => 'agent' . ($index + 1) . '@siramo.test',
                    'password' => 'password',
                    'telephone_verified_at' => now(),
                    'statut' => true,
                    'role_id' => 'R06',
                ]
            );
        }
    }
}