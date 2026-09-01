<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Equipe extends Model
{
    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    protected $table = 'equipes';

    /*
    |--------------------------------------------------------------------------
    | CLÉ PRIMAIRE
    |--------------------------------------------------------------------------
    */

    protected $primaryKey = 'idEquipe';

    public $incrementing = true;

    protected $keyType = 'int';

    /*
    |--------------------------------------------------------------------------
    | CHAMPS AUTORISÉS
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'reference',
        'nom',
        'superviseur_id',
        'statut',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Superviseur responsable de l'équipe.
     */
    public function superviseur(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'superviseur_id',
            'id'
        );
    }

    /**
     * Agents recenseurs membres de l'équipe.
     */
    public function membres(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'equipe_membres',
            'equipe_id',
            'user_id',
            'idEquipe',
            'id'
        )->withTimestamps();
    }


        public function affectations()
    {
        return $this->hasMany(
            Affectation::class,
            'equipe_id',
            'idEquipe'
        );
    }
}