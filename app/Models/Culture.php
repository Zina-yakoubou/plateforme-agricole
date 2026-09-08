<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Culture extends Model
{
    /**
     * Nom de la table.
     */
    protected $table = 'cultures';

    /**
     * Clé primaire.
     */
    protected $primaryKey = 'idCulture';

    /**
     * Type de la clé primaire.
     */
    protected $keyType = 'int';

    /**
     * Clé primaire auto-incrémentée.
     */
    public $incrementing = true;

    /**
     * Champs pouvant être remplis en masse.
     */
    protected $fillable = [
        'parcelle_id',

        // Culture
        'nomCulture',
        'categorie',
        'modeCulture',

        // Campagne agricole
        'campagneAgricole',

        // Superficie
        'superficieCultivee',

        // Calendrier agricole
        'dateSemis',
        'dateRecoltePrevue',
        'dateRecolteEffective',

        // Production
        'productionEstimee',
        'productionRecoltee',
        'uniteProduction',

        // Irrigation
        'irriguee',

        // État
        'etatCulture',

        // Observations
        'observations',
    ];

    /**
     * Conversion des attributs.
     */
    protected $casts = [
        'superficieCultivee'   => 'decimal:2',
        'dateSemis'            => 'date',
        'dateRecoltePrevue'    => 'date',
        'dateRecolteEffective' => 'date',
        'productionEstimee'    => 'decimal:2',
        'productionRecoltee'   => 'decimal:2',
        'irriguee'             => 'boolean',
    ];

    /**
     * Parcelle sur laquelle se trouve la culture.
     */
    public function parcelle(): BelongsTo
    {
        return $this->belongsTo(
            Parcelle::class,
            'parcelle_id',
            'idParcelle'
        );
    }

    /**
     * Intrants utilisés pour cette culture.
     *
     * Les informations supplémentaires
     * (quantité, nombre d'applications, date, observations)
     * sont stockées dans la table pivot culture_intrants.
     */
    public function intrants(): BelongsToMany
    {
        return $this->belongsToMany(
            Intrant::class,
            'culture_intrants',
            'culture_id',
            'intrant_id',
            'idCulture',
            'idIntrant'
        )->withPivot([
            'quantite',
            'nombreApplications',
            'dateApplication',
            'observations',
        ])->withTimestamps();
    }

    /**
     * Vérifie si la culture est irriguée.
     */
    public function estIrriguee(): bool
    {
        return $this->irriguee === true;
    }

    /**
     * Vérifie si la culture est terminée.
     */
    public function estTerminee(): bool
    {
        return $this->etatCulture === 'terminee';
    }

    /**
     * Vérifie si la culture est actuellement en récolte.
     */
    public function estEnRecolte(): bool
    {
        return $this->etatCulture === 'recolte';
    }
}