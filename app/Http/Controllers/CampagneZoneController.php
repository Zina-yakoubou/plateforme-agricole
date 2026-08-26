<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampagneZone extends Model
{
    protected $table = 'campagne_zones';

    protected $fillable = [
        'campagne_id',
        'zone_type',
        'zone_id',
        'dateDebut',
        'dateFin',
        'statut',
    ];

    protected $casts = [
        'dateDebut' => 'datetime',
        'dateFin' => 'datetime',
    ];

    /**
     * Campagne concernée.
     */
    public function campagne(): BelongsTo
    {
        return $this->belongsTo(
            CampagneRecensement::class,
            'campagne_id',
            'idCampagne'
        );
    }
}