<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menage extends Model
{
    /**
     * Nom de la table.
     */
    protected $table = 'menages';

    /**
     * Clé primaire.
     */
    protected $primaryKey = 'idMenage';

    /**
     * Type de la clé primaire.
     */
    protected $keyType = 'int';

    /**
     * La clé primaire est auto-incrémentée.
     */
    public $incrementing = true;

    /**
     * Champs pouvant être remplis en masse.
     */
    protected $fillable = [
        'uid',
        'maison_id',
        'numeroMenage',

        // Chef du ménage
        'nomChef',
        'prenomChef',
        'sexeChef',

        // Composition du ménage
        'nombreHommes',
        'nombreFemmes',
        'nombreGarcons',
        'nombreFilles',

        // Activité agricole
        'possedeExploitation',

        // Observation
        'observations',

        // Suivi de la collecte
        'statut',
    ];

    /**
     * Conversion des attributs.
     */
    protected $casts = [
        'nombreHommes'       => 'integer',
        'nombreFemmes'       => 'integer',
        'nombreGarcons'      => 'integer',
        'nombreFilles'       => 'integer',
        'possedeExploitation' => 'boolean',
    ];

    /**
     * Maison à laquelle appartient le ménage.
     */
    public function maison(): BelongsTo
    {
        return $this->belongsTo(
            Maison::class,
            'maison_id',
            'idMaison'
        );
    }

    /**
     * Exploitants agricoles du ménage.
     */
    public function exploitants(): HasMany
    {
        return $this->hasMany(
            Exploitant::class,
            'menage_id',
            'idMenage'
        );
    }

    /**
     * Nombre total de personnes du ménage.
     *
     * Ce nombre est calculé à partir de la composition
     * et n'est pas stocké directement en base.
     */
    public function getNombrePersonnesAttribute(): int
    {
        return $this->nombreHommes
            + $this->nombreFemmes
            + $this->nombreGarcons
            + $this->nombreFilles;
    }

    /**
     * Vérifie si le ménage possède une exploitation agricole.
     */
    public function aUneExploitation(): bool
    {
        return $this->possedeExploitation === true;
    }

    /**
     * Vérifie si la collecte du ménage est terminée.
     */
    public function estTermine(): bool
    {
        return $this->statut === 'terminee';
    }
}