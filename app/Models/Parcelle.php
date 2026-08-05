<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Parcelle extends Model
{
    protected $primaryKey = 'idParcelle';
    protected $fillable = ['superficie', 'typeSol', 'modeFaire', 'exploitant_id'];

    public function exploitant(): BelongsTo
    {
        return $this->belongsTo(Exploitant::class, 'exploitant_id', 'idExploitant');
    }

    public function pointsGPS(): HasMany
    {
        return $this->hasMany(PointGPS::class, 'parcelle_id', 'idParcelle');
    }

    public function cultures(): HasMany
    {
        return $this->hasMany(Culture::class, 'parcelle_id', 'idParcelle');
    }
}