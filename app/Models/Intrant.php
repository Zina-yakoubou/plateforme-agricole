<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Intrant extends Model
{
    protected $primaryKey = 'idIntrant';

    protected $fillable = [
        'nom',
        'type',
        'unite',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    /* ============================
     | RELATIONS
     * ============================ */

    public function besoins(): HasMany
    {
        return $this->hasMany(BesoinIntrant::class, 'intrant_id', 'idIntrant');
    }

    public function mouvementsStock(): HasMany
    {
        return $this->hasMany(MouvementStock::class, 'intrant_id', 'idIntrant');
    }
}