<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prefecture extends Model
{
    protected $primaryKey = 'idPrefecture';
    protected $fillable = ['nom', 'code', 'region_id'];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id', 'idRegion');
    }

    public function communes(): HasMany
    {
        return $this->hasMany(Commune::class, 'prefecture_id', 'idPrefecture');
    }
}