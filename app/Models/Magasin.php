<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Magasin extends Model
{
    protected $primaryKey = 'idMagasin';

    protected $fillable = [
        'nom',
        'code',
        'localisation',
        'capacite',
        'uniteCapacite',
        'prefecture_id',
        'responsable_id',
        'actif',
    ];

    protected $casts = [
        'capacite' => 'decimal:2',
        'actif' => 'boolean',
    ];

    /* ============================
     | RELATIONS
     * ============================ */

    public function prefecture(): BelongsTo
    {
        return $this->belongsTo(Prefecture::class, 'prefecture_id', 'idPrefecture');
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function mouvementsStock(): HasMany
    {
        return $this->hasMany(MouvementStock::class, 'magasin_id', 'idMagasin');
    }
}