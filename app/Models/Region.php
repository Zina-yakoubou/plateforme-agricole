<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    protected $primaryKey = 'idRegion';
    protected $fillable = ['nom', 'code'];

    public function prefectures(): HasMany
    {
        return $this->hasMany(Prefecture::class, 'region_id', 'idRegion');
    }
}