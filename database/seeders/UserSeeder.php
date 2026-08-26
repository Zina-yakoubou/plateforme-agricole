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
        | Superviseur
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
        | Agent recenseur
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
    }
}