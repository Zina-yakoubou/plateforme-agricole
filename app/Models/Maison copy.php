<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Maison extends Model
{
    protected $table = 'maisons';

    protected $primaryKey = 'idMaison';

    protected $fillable = [
        'numeroMaison',
        'adresse',
        'village_id',
    ];

    /**
     * Une maison appartient à un village.
     */
    public function village(): BelongsTo
    {
        return $this->belongsTo(
            Village::class,
            'village_id',
            'idVillage'
        );
    }

    /**
     * Une maison peut contenir plusieurs ménages.
     */
    public function menages(): HasMany
    {
        return $this->hasMany(
            Menage::class,
            'maison_id',
            'idMaison'
        );
    }
}