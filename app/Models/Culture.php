<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Culture extends Model
{
    protected $primaryKey = 'idCulture';
    protected $fillable = ['typeCulture', 'dateDebut', 'dateFin', 'superficieCultivee', 'campagne', 'parcelle_id'];

    public function parcelle(): BelongsTo
    {
        return $this->belongsTo(Parcelle::class, 'parcelle_id', 'idParcelle');
    }

    public function besoins(): HasMany
    {
        return $this->hasMany(BesoinIntrant::class, 'culture_id', 'idCulture');
    }

    public function productions(): HasMany
    {
        return $this->hasMany(Production::class, 'culture_id', 'idCulture');
    }
}