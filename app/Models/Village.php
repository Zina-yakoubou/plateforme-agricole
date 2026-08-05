<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Village extends Model
{
    protected $primaryKey = 'idVillage';
    protected $fillable = ['nom', 'code', 'canton_id'];

    public function canton(): BelongsTo
    {
        return $this->belongsTo(Canton::class, 'canton_id', 'idCanton');
    }

    public function maisons(): HasMany
    {
        return $this->hasMany(Maison::class, 'village_id', 'idVillage');
    }

    public function affectations(): HasMany
    {
        return $this->hasMany(Affectation::class, 'village_id', 'idVillage');
    }
}