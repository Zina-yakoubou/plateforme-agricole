<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Allocation extends Model
{
    protected $primaryKey = 'idAllocation';
    protected $fillable = ['quantiteAllouee', 'statut', 'dateAllocation', 'exploitant_id', 'intrant_id', 'saison_id'];

    public function exploitant(): BelongsTo
    {
        return $this->belongsTo(Exploitant::class, 'exploitant_id', 'idExploitant');
    }

    public function intrant(): BelongsTo
    {
        return $this->belongsTo(Intrant::class, 'intrant_id', 'idIntrant');
    }

    public function saison(): BelongsTo
    {
        return $this->belongsTo(SaisonDistribution::class, 'saison_id', 'idSaison');
    }

    public function distributions(): HasMany
    {
        return $this->hasMany(Distribution::class, 'allocation_id', 'idAllocation');
    }
}