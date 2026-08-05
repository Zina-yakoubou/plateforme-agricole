<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaisonDistribution extends Model
{
    protected $primaryKey = 'idSaison';
    protected $fillable = ['libelle', 'dateDebut', 'dateFin', 'statut', 'baseSurDerniereAnnee', 'campagneBase_id'];

    public function campagneBase(): BelongsTo
    {
        return $this->belongsTo(CampagneRecensement::class, 'campagneBase_id', 'idCampagne');
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(Allocation::class, 'saison_id', 'idSaison');
    }
}