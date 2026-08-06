<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prefecture extends Model
{
    protected $primaryKey = 'idPrefecture';

    protected $fillable = [
        'nom',
        'code',
        'region_id',
    ];

    /**
     * Région d'appartenance.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(
            Region::class,
            'region_id',
            'idRegion'
        );
    }

    /**
     * Communes de la préfecture.
     */
    public function communes(): HasMany
    {
        return $this->hasMany(
            Commune::class,
            'prefecture_id',
            'idPrefecture'
        );
    }

    /**
     * Affectations des utilisateurs dans cette préfecture.
     */
    public function affectations(): HasMany
    {
        return $this->hasMany(
            Affectation::class,
            'prefecture_id',
            'idPrefecture'
        );
    }
}