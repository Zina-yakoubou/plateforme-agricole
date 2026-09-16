<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cheptel extends Model
{
    protected $primaryKey = 'idCheptel';

    protected $fillable = [
        'exploitation_id',
        'uid',
        'typeAnimal',
        'effectif',
        'modeElevage',
        'observations',
    ];

    public function exploitation(): BelongsTo
    {
        return $this->belongsTo(Exploitation::class, 'exploitation_id', 'idExploitation');
    }
}