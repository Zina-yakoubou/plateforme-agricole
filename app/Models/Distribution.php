<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Distribution extends Model
{
    protected $primaryKey = 'idDistribution';

    protected $fillable = [
        'allocation_id',
        'agentDistributeur_id',
        'quantiteDistribuee',
        'dateDistribution',
        'lieuDistribution',
        'numeroBon',
        'signatureExploitant',
        'observations',
    ];

    protected $casts = [
        'quantiteDistribuee' => 'decimal:2',
        'dateDistribution' => 'datetime',
        'signatureExploitant' => 'boolean',
    ];

    /* ============================
     | RELATIONS
     * ============================ */

    public function allocation(): BelongsTo
    {
        return $this->belongsTo(Allocation::class, 'allocation_id', 'idAllocation');
    }

    public function agentDistributeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agentDistributeur_id');
    }

    public function mouvementsStock(): HasMany
    {
        return $this->hasMany(MouvementStock::class, 'distribution_id', 'idDistribution');
    }
}