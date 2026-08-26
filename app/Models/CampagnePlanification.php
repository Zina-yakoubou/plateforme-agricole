<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CampagnePlanification extends Model
{
    /*
    |--------------------------------------------------------------------------
    | CONFIGURATION
    |--------------------------------------------------------------------------
    */

    protected $table = 'campagne_planifications';

    protected $primaryKey = 'idPlanification';


    /*
    |--------------------------------------------------------------------------
    | ATTRIBUTS
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'campagne_id',
        'planifie_par',
        'statut',
        'observations',
    ];


    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        //
    ];


    /*
    |--------------------------------------------------------------------------
    | CAMPAGNE
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
    | UTILISATEUR AYANT PLANIFIÉ
    |--------------------------------------------------------------------------
    */

    public function planifiePar(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'planifie_par',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ACTIVITÉS
    |--------------------------------------------------------------------------
    */

    public function activites(): HasMany
    {
        return $this->hasMany(
            PlanificationActivite::class,
            'planification_id',
            'idPlanification'
        )->orderBy('ordre');
    }


    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function estBrouillon(): bool
    {
        return $this->statut === 'brouillon';
    }


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