<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Canton extends Model
{
    protected $primaryKey = 'idCanton';
    protected $fillable = ['nom', 'code', 'commune_id'];

    public function commune(): BelongsTo
    {
        return $this->belongsTo(Commune::class, 'commune_id', 'idCommune');
    }

    public function villages(): HasMany
    {
        return $this->hasMany(Village::class, 'canton_id', 'idCanton');
    }

    public function affectations(): HasMany
    {
        return $this->hasMany(Affectation::class, 'canton_id', 'idCanton');
    }
}