<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recensement extends Model
{
    protected $primaryKey = 'idRecensement';

    protected $fillable = [
        'uid',
        'campagne_id',
        'affectation_id',
        'agent_id',
        'maison_id',
        'statut',
        'dateDebut',
        'dateDerniereModification',
        'dateTerminaison',
        'validateur_id',
        'dateValidation',
        'observations',
    ];

    protected $casts = [
        'dateDebut' => 'datetime',
        'dateDerniereModification' => 'datetime',
        'dateTerminaison' => 'datetime',
        'dateValidation' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Campagne
    |--------------------------------------------------------------------------
    */

    public function campagne(): BelongsTo
    {
        return $this->belongsTo(
            CampagneRecensement::class,
            'campagne_id',
            'idCampagne'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Affectation
    |--------------------------------------------------------------------------
    */

    public function affectation(): BelongsTo
    {
        return $this->belongsTo(
            Affectation::class,
            'affectation_id',
            'idAffectation'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Agent ayant effectué le recensement
    |--------------------------------------------------------------------------
    */

    public function agent(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'agent_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Maison recensée
    |--------------------------------------------------------------------------
    */

    public function maison(): BelongsTo
    {
        return $this->belongsTo(
            Maison::class,
            'maison_id',
            'idMaison'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Ménages du recensement
    |--------------------------------------------------------------------------
    |
    | IMPORTANT :
    | Le ménage appartient au recensement et non directement à la maison.
    |
    */

    public function menages(): HasMany
    {
        return $this->hasMany(
            Menage::class,
            'recensement_id',
            'idRecensement'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Utilisateur ayant validé
    |--------------------------------------------------------------------------
    */

    public function validateur(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'validateur_id'
        );
    }
}