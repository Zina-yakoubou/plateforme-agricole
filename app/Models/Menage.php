<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Menage extends Model
{
    protected $primaryKey = 'idMenage';

    protected $fillable = [
        'numeroMenage',
        'nomChef',
        'nombrePersonnes',
        'aChamp',
        'maison_id',
    ];

    protected $casts = [
        'aChamp' => 'boolean',
        'nombrePersonnes' => 'integer',
    ];


    /**
     * Maison du ménage.
     */
    public function maison(): BelongsTo
    {
        return $this->belongsTo(
            Maison::class,
            'maison_id',
            'idMaison'
        );
    }
}