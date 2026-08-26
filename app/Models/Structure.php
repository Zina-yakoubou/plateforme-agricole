<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Structure extends Model
{
    protected $table = 'structures';

    protected $primaryKey = 'idStructure';

    protected $fillable = [
        'codeStructure',
        'nom',
        'niveau',
        'type',
        'structure_parent_id',
        'statut',
    ];

    protected $casts = [
        'statut' => 'boolean',
    ];


    /*
    |--------------------------------------------------------------------------
    | STRUCTURE PARENTE
    |--------------------------------------------------------------------------
    */

    public function parent(): BelongsTo
    {
        return $this->belongsTo(
            Structure::class,
            'structure_parent_id',
            'idStructure'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STRUCTURES ENFANTS
    |--------------------------------------------------------------------------
    */

    public function enfants(): HasMany
    {
        return $this->hasMany(
            Structure::class,
            'structure_parent_id',
            'idStructure'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CAMPAGNES PORTÉES
    |--------------------------------------------------------------------------
    */

    public function campagnes(): HasMany
    {
        return $this->hasMany(
            CampagneRecensement::class,
            'structure_id',
            'idStructure'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UTILISATEURS
    |--------------------------------------------------------------------------
    */

    public function utilisateurs(): HasMany
    {
        return $this->hasMany(
            User::class,
            'structure_id',
            'idStructure'
        );
    }
}