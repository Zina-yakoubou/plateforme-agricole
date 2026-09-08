<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Parcelle extends Model
{
    /**
     * Nom de la table.
     */
    protected $table = 'parcelles';

    /**
     * Clé primaire.
     */
    protected $primaryKey = 'idParcelle';

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
        'exploitant_id',

        // Identification
        'numeroParcelle',

        // Caractéristiques
        'superficie',
        'typeSol',
        'modeFaireValoir',
        'modeIrrigation',

        // Utilisation de la parcelle
        'estCultivee',
        'estJachere',
        'presenceArbres',

        // Observations
        'observations',

        // Suivi de la collecte
        'statut',
    ];

    /**
     * Conversion des attributs.
     */
    protected $casts = [
        'superficie'     => 'decimal:2',
        'estCultivee'    => 'boolean',
        'estJachere'     => 'boolean',
        'presenceArbres' => 'boolean',
    ];

    /**
     * Exploitant auquel appartient la parcelle.
     */
    public function exploitant(): BelongsTo
    {
        return $this->belongsTo(
            Exploitant::class,
            'exploitant_id',
            'idExploitant'
        );
    }

    /**
     * Points GPS qui définissent la géométrie de la parcelle.
     */
    public function pointsGPS(): HasMany
    {
        return $this->hasMany(
            PointGPS::class,
            'parcelle_id',
            'idParcelle'
        );
    }

    /**
     * Cultures pratiquées sur la parcelle.
     */
    public function cultures(): HasMany
    {
        return $this->hasMany(
            Culture::class,
            'parcelle_id',
            'idParcelle'
        );
    }

    /**
     * Vérifie si la parcelle est cultivée.
     */
    public function estCultivee(): bool
    {
        return $this->estCultivee === true;
    }

    /**
     * Vérifie si la parcelle est en jachère.
     */
    public function estEnJachere(): bool
    {
        return $this->estJachere === true;
    }

    /**
     * Vérifie si la parcelle possède des arbres.
     */
    public function aDesArbres(): bool
    {
        return $this->presenceArbres === true;
    }

    /**
     * Vérifie si la collecte de la parcelle est terminée.
     */
    public function estTerminee(): bool
    {
        return $this->statut === 'terminee';
    }
}