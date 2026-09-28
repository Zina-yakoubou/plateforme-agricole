<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cheptel extends Model
{
    protected $table = 'cheptels';

    protected $primaryKey = 'idCheptel';

    protected $fillable = [
        'uid',
        'exploitation_id',
        'typeAnimal',
        'effectif',
        'modeElevage',
        'observations',
    ];

    protected $casts = [
        'effectif' => 'integer',
    ];

    public function exploitation(): BelongsTo
    {
        return $this->belongsTo(
            Exploitation::class,
            'exploitation_id',
            'idExploitation'
        );
    }
}