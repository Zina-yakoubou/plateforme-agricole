<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Création des utilisateurs par défaut.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrateur SIRA-MO',
            'login' => 'admin',
            'email' => 'admin@siramo.tg',
            'telephone' => '90000000',
            'password' => Hash::make('admin123'),
            'statut' => 'actif',
            'role_id' => 1,
        ]);

        User::create([
            'name' => 'Directeur Préfectoral',
            'login' => 'directeur01',
            'email' => 'directeur@siramo.tg',
            'telephone' => '91111111',
            'password' => Hash::make('directeur123'),
            'statut' => 'actif',
            'role_id' => 2,
        ]);

        User::create([
            'name' => 'Agent Recenseur',
            'login' => 'agent001',
            'email' => 'agent@siramo.tg',
            'telephone' => '92222222',
            'password' => Hash::make('agent123'),
            'statut' => 'actif',
            'role_id' => 3,
        ]);
    }
}