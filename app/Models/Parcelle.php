<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Parcelle extends Model
{
    protected $primaryKey = 'idParcelle';

    protected $fillable = [
        'uid',
        'exploitant_id',
        'numeroParcelle',
        'superficie',
        'typeSol',
        'modeFaireValoir',
        'modeIrrigation',
        'estCultivee',
        'estJachere',
        'presenceArbres',
        'observations',
    ];

    protected $casts = [
        'superficie' => 'decimal:2',
        'estCultivee' => 'boolean',
        'estJachere' => 'boolean',
        'presenceArbres' => 'boolean',
    ];

    public function exploitant(): BelongsTo
    {
        return $this->belongsTo(
            Exploitant::class,
            'exploitant_id',
            'idExploitant'
        );
    }

    public function pointsGPS(): HasMany
    {
        return $this->hasMany(
            PointGPS::class,
            'parcelle_id',
            'idParcelle'
        );
    }

    public function culturesParcelles(): HasMany
    {
        return $this->hasMany(
            CultureParcelle::class,
            'parcelle_id',
            'idParcelle'
        );
    }
}