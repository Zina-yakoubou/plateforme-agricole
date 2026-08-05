<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Création des rôles SIRA-MO.
     */
    public function run(): void
    {
        Role::create([
            'idRole' => 1,
            'nom' => 'Administrateur',
            'description' => 'Gestion complète du système SIRA-MO',
        ]);

        Role::create([
            'idRole' => 2,
            'nom' => 'Directeur préfectoral',
            'description' => 'Supervision du recensement au niveau préfectoral',
        ]);

        Role::create([
            'idRole' => 3,
            'nom' => 'Agent recenseur',
            'description' => 'Collecte et saisie des données de recensement',
        ]);
    }
}