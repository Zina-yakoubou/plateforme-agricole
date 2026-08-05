<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CampagneRecensement extends Model
{
    protected $primaryKey = 'idCampagne';
    protected $fillable = ['codeRNA', 'libelle', 'annee', 'dateDebut', 'dateFin', 'statut', 'estOfficielleMAEH', 'responsable_id'];

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_id', 'idUser');
    }

    public function affectations(): HasMany
    {
        return $this->hasMany(Affectation::class, 'campagne_id', 'idCampagne');
    }

    public function recensements(): HasMany
    {
        return $this->hasMany(Recensement::class, 'campagne_id', 'idCampagne');
    }

    public function saisonsDistribution(): HasMany
    {
        return $this->hasMany(SaisonDistribution::class, 'campagneBase_id', 'idCampagne');
    }
}