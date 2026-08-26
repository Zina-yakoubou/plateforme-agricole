<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Création des rôles SIRA-Mô.
     */
    public function run(): void
    {
        $roles = [
            [
                'idRole' => "R01",
                'nom' => 'Administrateur',
                'description' => 'Gestion complète du système SIRA-Mô.',
            ],
            [
                'idRole' => "R02",
                'nom' => 'DPA',
                'description' => 'Directeur Préfectoral de l’Agriculture chargé de la coordination et du suivi du recensement agricole au niveau préfectoral.',
            ],
            [
                'idRole' => "R03",
                'nom' => 'Superviseur',
                'description' => 'Supervision et contrôle des opérations de recensement agricole au niveau de la préfecture.',
            ],
            [
                'idRole' => "R04",
                'nom' => 'Technicien',
                'description' => 'Appui et contrôle technique des opérations de recensement agricole.',
            ],
            [
                'idRole' => "R05",
                'nom' => 'CACH',
                'description' => 'Évaluation technique des besoins des exploitants agricoles, notamment en intrants.',
            ],
            [
                'idRole' => "R06",
                'nom' => 'Agent recenseur',
                'description' => 'Collecte et saisie des données auprès des ménages, exploitants et exploitations agricoles.',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['idRole' => $role['idRole']],
                [
                    'nom' => $role['nom'],
                    'description' => $role['description'],
                ]
            );
        }
    }
}