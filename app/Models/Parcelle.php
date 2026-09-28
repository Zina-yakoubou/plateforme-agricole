<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Parcelle extends Model
{
    protected $table = 'parcelles';

    protected $primaryKey = 'idParcelle';

    protected $fillable = [
        'uid',
        'exploitation_id',
        'numeroParcelle',
        'superficie',
        'statutParcelle',
        'typeSol',
        'modeFaireValoir',
        'modeIrrigation',
        'presenceArbres',
        'observations',
    ];

    protected $casts = [
        'superficie' => 'decimal:2',
        'presenceArbres' => 'boolean',
    ];

    public function exploitation(): BelongsTo
    {
        return $this->belongsTo(
            Exploitation::class,
            'exploitation_id',
            'idExploitation'
        );
    }

    public function cultures(): HasMany
    {
        return $this->hasMany(
            CultureParcelle::class,
            'parcelle_id',
            'idParcelle'
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
}