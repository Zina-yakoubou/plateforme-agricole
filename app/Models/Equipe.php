<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipe extends Model
{
    protected $table = 'equipes';

    protected $primaryKey = 'idEquipe';

    protected $fillable = [
        'reference',
        'nom',
        'superviseur_id',
        'statut',
    ];

    /*
    |--------------------------------------------------------------------------
    | Superviseur
    |--------------------------------------------------------------------------
    */

    public function superviseur(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'superviseur_id',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Membres
    |--------------------------------------------------------------------------
    */

    public function membres(): HasMany
    {
        return $this->hasMany(
            EquipeMembre::class,
            'equipe_id',
            'idEquipe'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Affectations
    |--------------------------------------------------------------------------
    */

    public function affectations(): HasMany
    {
        return $this->hasMany(
            Affectation::class,
            'equipe_id',
            'idEquipe'
        );
    }
}