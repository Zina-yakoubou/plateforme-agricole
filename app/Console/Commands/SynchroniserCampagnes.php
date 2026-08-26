<?php

namespace App\Console\Commands;

use App\Models\CampagneRecensement;
use Illuminate\Console\Command;

class SynchroniserCampagnes extends Command
{
    protected $signature = 'campagnes:synchroniser';

    protected $description =
        'Active et clôture automatiquement les campagnes selon leurs dates';

    public function handle(): int
    {
        $campagnes = CampagneRecensement::whereIn('statut', [
            'planifiee',
            'active',
        ])->get();

        foreach ($campagnes as $campagne) {

            $campagne->synchroniserStatut();
        }

        $this->info('Statuts des campagnes synchronisés.');

        return self::SUCCESS;
    }
}