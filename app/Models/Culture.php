<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Culture extends Model
{
    protected $primaryKey = 'idCulture';

    protected $fillable = [
        'nomCulture',
        'categorie',
        'description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /* ============================
     | RELATIONS
     * ============================ */

    public function culturesParcelles(): HasMany
    {
        return $this->hasMany(CultureParcelle::class, 'culture_id', 'idCulture');
    }
}