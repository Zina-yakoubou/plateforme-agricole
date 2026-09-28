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
        'campagneAgricole',
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

    public function parcelle(): BelongsTo
    {
        return $this->belongsTo(
            Parcelle::class,
            'parcelle_id',
            'idParcelle'
        );
    }

    public function culture(): BelongsTo
    {
        return $this->belongsTo(
            Culture::class,
            'culture_id',
            'idCulture'
        );
    }

    public function intrants(): HasMany
    {
        return $this->hasMany(
            CultureIntrant::class,
            'culture_parcelle_id',
            'idCultureParcelle'
        );
    }
}