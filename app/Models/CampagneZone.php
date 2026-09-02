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
    |
    | Retourne le niveau le plus précis défini pour cette zone.
    |
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



    /*
    |--------------------------------------------------------------------------
    | TYPE DE ZONE
    |--------------------------------------------------------------------------
    |
    | Retourne le niveau le plus précis défini pour cette zone :
    | region, prefecture, commune, canton ou village.
    |
    */

    public function getZoneTypeAttribute(): ?string
    {
        if ($this->village_id) {
            return 'village';
        }

        if ($this->canton_id) {
            return 'canton';
        }

        if ($this->commune_id) {
            return 'commune';
        }

        if ($this->prefecture_id) {
            return 'prefecture';
        }

        if ($this->region_id) {
            return 'region';
        }

        return null;
    }


    /*
    |--------------------------------------------------------------------------
    | PRÉFECTURE RATTACHÉE (remonte la hiérarchie si besoin)
    |--------------------------------------------------------------------------
    |
    | Retourne la préfecture concernée par cette zone, même si la zone
    | a été enregistrée à un niveau inférieur (commune, canton, village).
    |
    */

    public function getPrefectureRattacheeAttribute(): ?Prefecture
    {
        if ($this->prefecture) {
            return $this->prefecture;
        }

        if ($this->commune) {
            return $this->commune->prefecture;
        }

        if ($this->canton) {
            return $this->canton->commune?->prefecture;
        }

        if ($this->village) {
            return $this->village->canton?->commune?->prefecture;
        }

        return null;
    }
}