<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CampagneRecensement extends Model
{
    protected $primaryKey = 'idCampagne';


    protected $fillable = [
        'codeRNA',
        'libelle',
        'dateDebut',
        'dateFin',
        'statut',
        'active',
        'estOfficielle',
        'responsable_id'
    ];


    protected $casts = [

        'dateDebut' => 'date',

        'dateFin' => 'date',

        'active' => 'boolean',

        'estOfficielle' => 'boolean',
    ];



    public function responsable(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'responsable_id',
            'id'
        );
    }



    public function affectations(): HasMany
    {
        return $this->hasMany(
            Affectation::class,
            'campagne_id',
            'idCampagne'
        );
    }



    public function recensements(): HasMany
    {
        return $this->hasMany(
            Recensement::class,
            'campagne_id',
            'idCampagne'
        );
    }



    public function saisonsDistribution(): HasMany
    {
        return $this->hasMany(
            SaisonDistribution::class,
            'campagneBase_id',
            'idCampagne'
        );
    }
}