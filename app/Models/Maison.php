<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Maison extends Model
{
    protected $table = 'maisons';

    protected $primaryKey = 'idMaison';

    protected $fillable = [
        'uid',
        'numeroMaison',
        'village_id',
        'latitude',
        'longitude',
        'precisionGPS',
        'adresse',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'precisionGPS' => 'decimal:2',
    ];

    /**
     * Une maison appartient à un village.
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
     * Une maison peut contenir plusieurs ménages.
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
     * Une maison possède plusieurs fiches de recensement
     * au cours des différentes campagnes.
     */
    public function recensements(): HasMany
    {
        return $this->hasMany(
            Recensement::class,
            'maison_id',
            'idMaison'
        );
    }

    /**
     * Récupère le recensement de cette maison
     * pour une campagne donnée.
     */
    public function recensementPourCampagne(int $campagneId): ?Recensement
    {
        return $this->recensements()
            ->where('campagne_id', $campagneId)
            ->first();
    }

    /**
     * Vérifie si cette maison possède déjà
     * une fiche pour une campagne donnée.
     */
    public function aRecensementPourCampagne(int $campagneId): bool
    {
        return $this->recensements()
            ->where('campagne_id', $campagneId)
            ->exists();
    }
}