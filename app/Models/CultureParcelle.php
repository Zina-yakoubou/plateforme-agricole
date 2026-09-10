<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CultureParcelle extends Model
{
    protected $table = 'cultures_parcelles';

    protected $primaryKey = 'idCultureParcelle';

    protected $fillable = [
        'uid',
        'parcelle_id',
        'culture_id',
        'campagne_id',
        'modeCulture',
        'superficieCultivee',
        'dateSemis',
        'dateRecoltePrevue',
        'dateRecolteEffective',
        'irriguee',
        'etatCulture',
        'observations',
    ];

    protected $casts = [
        'superficieCultivee' => 'decimal:2',
        'dateSemis' => 'date',
        'dateRecoltePrevue' => 'date',
        'dateRecolteEffective' => 'date',
        'irriguee' => 'boolean',
    ];

    /* ============================
     | RELATIONS
     * ============================ */

    public function parcelle(): BelongsTo
    {
        return $this->belongsTo(Parcelle::class, 'parcelle_id', 'idParcelle');
    }

    public function culture(): BelongsTo
    {
        return $this->belongsTo(Culture::class, 'culture_id', 'idCulture');
    }

    public function campagne(): BelongsTo
    {
        return $this->belongsTo(CampagneRecensement::class, 'campagne_id', 'idCampagne');
    }

    public function production(): HasMany
    {
        return $this->hasMany(Production::class, 'culture_parcelle_id', 'idCultureParcelle');
    }

    public function besoinsIntrants(): HasMany
    {
        return $this->hasMany(BesoinIntrant::class, 'culture_parcelle_id', 'idCultureParcelle');
    }
}