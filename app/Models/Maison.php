<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Maison extends Model
{
    protected $primaryKey = 'idMaison';
    protected $fillable = ['numeroMaison', 'adresse', 'village_id'];

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class, 'village_id', 'idVillage');
    }

    public function menages(): HasMany
    {
        return $this->hasMany(Menage::class, 'maison_id', 'idMaison');
    }
}