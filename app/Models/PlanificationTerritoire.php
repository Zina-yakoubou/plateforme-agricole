<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanificationTerritoire extends Model
{
    protected $table = 'planification_territoires';

    protected $primaryKey = 'idPlanificationTerritoire';

    protected $fillable = [
        'planification_prefectorale_id',
        'niveau',
        'commune_id',
        'canton_id',
        'village_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | PLANIFICATION PRÉFECTORALE
    |--------------------------------------------------------------------------
    */

    public function planificationPrefectorale(): BelongsTo
    {
        return $this->belongsTo(
            PlanificationPrefectorale::class,
            'planification_prefectorale_id',
            'idPlanificationPrefectorale'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | COMMUNE
    |--------------------------------------------------------------------------
    */

    public function commune(): BelongsTo
    {
        return $this->belongsTo(
            Commune::class,
            'commune_id',
            'idCommune'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CANTON
    |--------------------------------------------------------------------------
    */

    public function canton(): BelongsTo
    {
        return $this->belongsTo(
            Canton::class,
            'canton_id',
            'idCanton'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | VILLAGE
    |--------------------------------------------------------------------------
    */

    public function village(): BelongsTo
    {
        return $this->belongsTo(
            Village::class,
            'village_id',
            'idVillage'
        );
    }
}