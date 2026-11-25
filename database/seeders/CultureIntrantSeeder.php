<?php

namespace Database\Seeders;

use App\Models\Culture;
use App\Models\Intrant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CultureIntrantSeeder extends Seeder
{
    public function run(): void
    {
        $specifiques = [
            'Maïs'     => ['NPK 15-15-15', 'Urée 46% N', 'Herbicide sélectif maïs', 'Insecticide maïs', 'Fongicide maïs', 'Fumier', 'Compost', 'Traitement des semences'],
            'Riz'      => ['NPK 15-15-15', 'Urée 46% N', 'DAP', 'Herbicide sélectif riz', 'Fongicide riz', 'Insecticide général', 'Fumier'],
            'Sorgho'   => ['NPK 15-15-15', 'Urée 46% N', 'Herbicide total', 'Insecticide général', 'Fumier', 'Compost'],
            'Mil'      => ['NPK 15-15-15', 'Urée 46% N', 'Herbicide total', 'Insecticide général', 'Fumier', 'Compost'],
            'Fonio'    => ['NPK 15-15-15', 'Herbicide total', 'Fumier', 'Compost'],
            'Soja'     => ['TSP', 'KCl', 'Inoculum de soja', 'Herbicide sélectif soja', 'Insecticide soja', 'Traitement des semences'],
            'Niébé'    => ['TSP', 'Herbicide total', 'Insecticide niébé', 'Traitement des semences'],
            'Haricot'  => ['TSP', 'Herbicide total', 'Insecticide général', 'Fumier'],
            'Arachide' => ['TSP', 'KCl', 'Herbicide prélevée', 'Herbicide postlevée', 'Fongicide général', 'Traitement des semences'],
            'Voandzou' => ['TSP', 'Herbicide total', 'Fumier'],
            'Sésame'   => ['NPK 15-15-15', 'Herbicide total', 'Insecticide général'],
            'Coton'    => ['NPK 15-15-15', 'Urée 46% N', 'Herbicide prélevée', 'Herbicide postlevée', 'Insecticide coton', 'Traitement des semences'],
            'Igname'   => ['NPK 15-15-15', 'Herbicide total', 'Fumier', 'Compost'],
            'Manioc'   => ['NPK 15-15-15', 'Herbicide total', 'Fumier', 'Compost'],
            'Ananas'   => ['NPK 15-15-15', 'Urée 46% N', 'Sulfate de potassium', 'Herbicide total', 'Compost'],
            'Tomate'   => ['NPK 15-15-15', 'Urée 46% N', 'Sulfate de potassium', 'Insecticide maraîcher', 'Fongicide maraîcher', 'Fientes de volaille', 'Compost', 'Biopesticide'],
            'Piment'   => ['NPK 15-15-15', 'Urée 46% N', 'Insecticide maraîcher', 'Fongicide maraîcher', 'Fientes de volaille', 'Compost'],
            'Gombo'    => ['NPK 15-15-15', 'Urée 46% N', 'Insecticide maraîcher', 'Fientes de volaille', 'Compost'],
            'Oignon'   => ['NPK 15-15-15', 'Urée 46% N', 'Insecticide maraîcher', 'Fongicide maraîcher', 'Fientes de volaille'],
            'Chou'     => ['NPK 15-15-15', 'Urée 46% N', 'Insecticide maraîcher', 'Fongicide maraîcher', 'Biopesticide', 'Compost'],
        ];

        $parCategorie = [
            'vivriere'   => ['NPK 15-15-15', 'Urée 46% N', 'Herbicide total', 'Insecticide général', 'Fumier', 'Compost'],
            'rente'      => ['NPK 15-15-15', 'Herbicide total', 'Insecticide général', 'Fongicide général', 'Fumier', 'Compost'],
            'maraichere' => ['NPK 15-15-15', 'Urée 46% N', 'Insecticide maraîcher', 'Fongicide maraîcher', 'Fientes de volaille', 'Compost', 'Biopesticide'],
            'fruitiere'  => ['NPK 15-15-15', 'Sulfate de potassium', 'Insecticide général', 'Fongicide général', 'Fumier', 'Compost'],
            'fourragere' => ['Urée 46% N', 'Fumier', 'Compost'],
            'autre'      => ['Compost'],
        ];

        $norm = fn (string $t) => (string) Str::of($t)->ascii()->lower()->squish();

        $intrants = Intrant::all();
        $parNom   = $intrants->keyBy('nom');

        // Semences indexées par nom de culture : "Semence d’arachide" => "arachide"
        $semencesParCulture = [];
        foreach ($intrants->where('type', 'semence') as $s) {
            $cle = preg_replace("/^semence d[e'’]\s?/u", '', $norm($s->nom));
            $semencesParCulture[$cle] = $s;
        }

        $liens = 0;
        $manquants = [];
        $sansSemence = [];

        DB::transaction(function () use ($specifiques, $parCategorie, $parNom, $semencesParCulture, $norm, &$liens, &$manquants, &$sansSemence) {
            foreach (Culture::all() as $culture) {
                $noms = $specifiques[$culture->nomCulture]
                    ?? ($parCategorie[$culture->categorie] ?? []);

                $ids = [];

                foreach ($noms as $nom) {
                    $intrant = $parNom->get($nom);

                    if (! $intrant) {
                        $manquants[$nom] = true;
                        continue;
                    }

                    $ids[] = $intrant->getKey();
                }

                $semence = $semencesParCulture[$norm($culture->nomCulture)] ?? null;

                if ($semence) {
                    $ids[] = $semence->getKey();
                } else {
                    $sansSemence[] = $culture->nomCulture;
                }

                foreach (array_unique($ids) as $intrantId) {
                    DB::table('referentiel_culture_intrants')->insertOrIgnore([
                        'culture_id' => $culture->getKey(),
                        'intrant_id' => $intrantId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $liens++;
                }
            }
        });

        $this->command->info("Liens culture → intrant traités : $liens");

        if ($manquants) {
            $this->command->warn('Intrants introuvables : ' . implode(', ', array_keys($manquants)));
        }

        if ($sansSemence) {
            $this->command->line('Cultures sans semence : ' . implode(', ', $sansSemence));
        }
    }
}