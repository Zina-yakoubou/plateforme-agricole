<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Distribution extends Model
{
    protected $primaryKey = 'idDistribution';
    protected $fillable = ['quantiteDistribuee', 'dateDistribution', 'lieuDistribution', 'signatureExploitant', 'agentDistributeur_id', 'allocation_id'];

    public function agentDistributeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agentDistributeur_id', 'idUser');
    }

    public function allocation(): BelongsTo
    {
        return $this->belongsTo(Allocation::class, 'allocation_id', 'idAllocation');
    }
}