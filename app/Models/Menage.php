<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menage extends Model
{
    protected $primaryKey = 'idMenage';

    protected $fillable = [
        'uid',
        'maison_id',
        'numeroMenage',
        'nomChef',
        'prenomChef',
        'sexeChef',
        'nombreHommes',
        'nombreFemmes',
        'nombreGarcons',
        'nombreFilles',
        'possedeExploitation',
        'observations',
    ];

    protected $casts = [
        'possedeExploitation' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Maison
    |--------------------------------------------------------------------------
    */

    public function maison(): BelongsTo
    {
        return $this->belongsTo(
            Maison::class,
            'maison_id',
            'idMaison'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Exploitants
    |--------------------------------------------------------------------------
    */

    public function exploitants(): HasMany
    {
        return $this->hasMany(
            Exploitant::class,
            'menage_id',
            'idMenage'
        );
    }
}