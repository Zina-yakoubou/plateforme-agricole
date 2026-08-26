<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanificationEtape extends Model
{
    /*
    |--------------------------------------------------------------------------
    | CONFIGURATION
    |--------------------------------------------------------------------------
    */

    protected $table = 'planification_etapes';

    protected $primaryKey = 'idEtape';


    /*
    |--------------------------------------------------------------------------
    | ATTRIBUTS AUTORISÉS
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'planification_id',

        'type',
        'libelle',
        'ordre',

        'dateDebut',
        'dateFin',

        'statut',

        'observations',
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
    |
    | Une étape appartient à une seule planification.
    |
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
    | HELPERS DE STATUT
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


    public function estSuspendue(): bool
    {
        return $this->statut === 'suspendue';
    }
}