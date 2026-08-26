<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampagneZone extends Model
{
    protected $table = 'campagne_zones';

    protected $primaryKey = 'idCampagneZone';

    protected $fillable = [
        'campagne_id',

        'region_id',
        'prefecture_id',
        'commune_id',
        'canton_id',
        'village_id',

        'dateDebut',
        'dateFin',

        'statut',
    ];

    protected $casts = [
        'dateDebut' => 'datetime',
        'dateFin'   => 'datetime',
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
    | RÉGION
    |--------------------------------------------------------------------------
    */

    public function region(): BelongsTo
    {
        return $this->belongsTo(
            Region::class,
            'region_id',
            'idRegion'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PRÉFECTURE
    |--------------------------------------------------------------------------
    */

    public function prefecture(): BelongsTo
    {
        return $this->belongsTo(
            Prefecture::class,
            'prefecture_id',
            'idPrefecture'
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


    /*
    |--------------------------------------------------------------------------
    | NOM DE LA ZONE
    |--------------------------------------------------------------------------
    */

    public function getNomZoneAttribute(): ?string
    {
        if ($this->village) {
            return $this->village->nom;
        }

        if ($this->canton) {
            return $this->canton->nom;
        }

        if ($this->commune) {
            return $this->commune->nom;
        }

        if ($this->prefecture) {
            return $this->prefecture->nom;
        }

        if ($this->region) {
            return $this->region->nom;
        }

        return null;
    }
}