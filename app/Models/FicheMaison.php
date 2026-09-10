<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FicheMaison extends Model
{
    /**
     * Nom de la table.
     */
    protected $table = 'fiche_maisons';

    /**
     * Clé primaire.
     */
    protected $primaryKey = 'idFicheMaison';

    /**
     * Type de la clé primaire.
     */
    protected $keyType = 'int';

    /**
     * La clé primaire est auto-incrémentée.
     */
    public $incrementing = true;

    /**
     * Attributs pouvant être assignés en masse.
     */
    protected $fillable = [
        'maison_id',
        'affectation_id',
        'agent_id',
        'chefMaison',
        'adresse',
        'nombreMenages',
        'latitude',
        'longitude',
        'precisionGPS',
        'photoMaison',
        'statut',
        'dateIdentification',
        'observations',
    ];

    /**
     * Conversion des attributs.
     */
    protected $casts = [
        'nombreMenages' => 'integer',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'precisionGPS' => 'decimal:2',
        'dateIdentification' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    /**
     * Maison concernée par la fiche.
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
     * Affectation dans laquelle la fiche a été réalisée.
     *
     * L'affectation permet de retrouver :
     * - la campagne ;
     * - l'équipe ;
     * - le village.
     */
    public function affectation(): BelongsTo
    {
        return $this->belongsTo(
            Affectation::class,
            'affectation_id',
            'idAffectation'
        );
    }

    /**
     * Agent ayant réalisé la collecte.
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'agent_id',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Méthodes métier
    |--------------------------------------------------------------------------
    */

    /**
     * Vérifie si la fiche est en brouillon.
     */
    public function estBrouillon(): bool
    {
        return $this->statut === 'brouillon';
    }

    /**
     * Vérifie si la fiche est en cours.
     */
    public function estEnCours(): bool
    {
        return $this->statut === 'en_cours';
    }

    /**
     * Vérifie si la fiche est terminée.
     */
    public function estTerminee(): bool
    {
        return $this->statut === 'terminee';
    }

    /**
     * Vérifie si la fiche est vérifiée.
     */
    public function estVerifiee(): bool
    {
        return $this->statut === 'verifiee';
    }

    /**
     * Vérifie si la fiche possède une localisation GPS.
     */
    public function estLocalisee(): bool
    {
        return $this->latitude !== null
            && $this->longitude !== null;
    }
}

