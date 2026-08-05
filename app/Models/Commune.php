<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commune extends Model
{
    protected $primaryKey = 'idCommune';
    protected $fillable = ['nom', 'code', 'prefecture_id'];

    public function prefecture(): BelongsTo
    {
        return $this->belongsTo(Prefecture::class, 'prefecture_id', 'idPrefecture');
    }

    public function cantons(): HasMany
    {
        return $this->hasMany(Canton::class, 'commune_id', 'idCommune');
    }
}