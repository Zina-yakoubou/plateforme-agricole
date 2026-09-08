<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Maison extends Model
{
    protected $table = 'maisons';

    protected $primaryKey = 'idMaison';

    protected $keyType = 'int';

    public $incrementing = true;

    protected $fillable = [
        'uid',
        'numeroMaison',
        'village_id',
        'chefMaison',
        'adresse',
        'nombreMenages',
        'latitude',
        'longitude',
        'precisionGPS',
        'photoMaison',
        'statut',
        'agent_id',
        'dateIdentification',
    ];

    protected $casts = [
        'nombreMenages' => 'integer',

        'latitude' => 'decimal:7',

        'longitude' => 'decimal:7',

        'precisionGPS' => 'decimal:2',

        'dateIdentification' => 'datetime',
    ];

    /**
     * Village auquel appartient la maison.
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
     * Agent ayant identifié la maison.
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'agent_id',
            'id'
        );
    }

    /**
     * Ménages vivant dans la maison.
     */
    public function menages(): HasMany
    {
        return $this->hasMany(
            Menage::class,
            'maison_id',
            'idMaison'
        );
    }

    /**
     * Vérifie si la maison possède une localisation GPS.
     */
    public function estLocalisee(): bool
    {
        return $this->latitude !== null
            && $this->longitude !== null;
    }

    /**
     * Vérifie si la maison est terminée.
     */
    public function estTerminee(): bool
    {
        return $this->statut === 'terminee';
    }

    /**
     * Vérifie si la maison est vérifiée.
     */
    public function estVerifiee(): bool
    {
        return $this->statut === 'verifiee';
    }
}