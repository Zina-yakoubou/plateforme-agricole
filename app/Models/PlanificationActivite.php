<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanificationActivite extends Model
{
    /*
    |--------------------------------------------------------------------------
    | CONFIGURATION
    |--------------------------------------------------------------------------
    */

    protected $table = 'planification_activites';

    protected $primaryKey = 'idActivite';


    /*
    |--------------------------------------------------------------------------
    | ATTRIBUTS
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'planification_id',
        'libelle',
        'description',
        'ordre',
        'dateDebut',
        'dateFin',
        'statut',
    ];


    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'dateDebut' => 'datetime',
        'dateFin'   => 'datetime',
        'ordre'     => 'integer',
    ];


    /*
    |--------------------------------------------------------------------------
    | PLANIFICATION
    |--------------------------------------------------------------------------
    */

    public function planification(): BelongsTo
    {
        return $this->belongsTo(
            CampagnePlanification::class,
            'planification_id',
            'idPlanification'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function estPlanifiee(): bool
    {
        return $this->statut === 'planifiee';
    }


    public function estEnCours(): bool
    {
        return $this->statut === 'en_cours';
    }


    public function estTerminee(): bool
    {
        return $this->statut === 'terminee';
    }
}