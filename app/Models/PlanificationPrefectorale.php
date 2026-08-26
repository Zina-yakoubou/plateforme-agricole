<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlanificationPrefectorale extends Model
{
    protected $table = 'planifications_prefectorales';

    protected $primaryKey = 'idPlanificationPrefectorale';

    protected $fillable = [
        'deploiement_id',
        'planifie_par',
        'planTravail',
        'observations',
        'statut',
    ];

    /*
    |--------------------------------------------------------------------------
    | DÉPLOIEMENT
    |--------------------------------------------------------------------------
    */

    public function deploiement(): BelongsTo
    {
        return $this->belongsTo(
            CampagneDeploiement::class,
            'deploiement_id',
            'idDeploiement'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RESPONSABLE
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
    | ACTIVITÉS ADAPTÉES
    |--------------------------------------------------------------------------
    */

    public function adaptationsActivites(): HasMany
    {
        return $this->hasMany(
            PlanificationActiviteAdaptation::class,
            'planification_prefectorale_id',
            'idPlanificationPrefectorale'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TERRITOIRES
    |--------------------------------------------------------------------------
    */

    public function territoires(): HasMany
    {
        return $this->hasMany(
            PlanificationTerritoire::class,
            'planification_prefectorale_id',
            'idPlanificationPrefectorale'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | BESOINS
    |--------------------------------------------------------------------------
    */

    public function besoins(): HasMany
    {
        return $this->hasMany(
            BesoinPlanification::class,
            'planification_prefectorale_id',
            'idPlanificationPrefectorale'
        );
    }
}