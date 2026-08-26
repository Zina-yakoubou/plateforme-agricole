<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Maison extends Model
{
    protected $primaryKey = 'idMaison';

    protected $fillable = [
        'uid',
        'numeroMaison',
        'adresse',
        'village_id',
    ];

    /**
     * Village auquel appartient la maison.
     */
    public function village(): BelongsTo
    {
        return $this->belongsTo(
            Village::class,
            'village_id',
            'idVillage'
        );
    }

    /**
     * Ménages de la maison.
     */
    public function menages(): HasMany
    {
        return $this->hasMany(
            Menage::class,
            'maison_id',
            'idMaison'
        );
    }
}

