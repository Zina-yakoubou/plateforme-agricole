<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Allocation extends Model
{
    protected $primaryKey = 'idAllocation';

    protected $fillable = [
        'besoin_id',
        'saison_id',
        'quantiteAllouee',
        'dateAllocation',
        'agentValidateur_id',
        'statut',
        'motifRejet',
    ];

    protected $casts = [
        'quantiteAllouee' => 'decimal:2',
        'dateAllocation' => 'date',
    ];

    /* ============================
     | RELATIONS
     * ============================ */

    public function besoin(): BelongsTo
    {
        return $this->belongsTo(BesoinIntrant::class, 'besoin_id', 'idBesoin');
    }

    public function saison(): BelongsTo
    {
        return $this->belongsTo(SaisonDistribution::class, 'saison_id', 'idSaison');
    }

    public function agentValidateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agentValidateur_id');
    }

    public function distributions(): HasMany
    {
        return $this->hasMany(Distribution::class, 'allocation_id', 'idAllocation');
    }
}