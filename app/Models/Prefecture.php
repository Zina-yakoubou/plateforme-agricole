<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prefecture extends Model
{
    protected $primaryKey = 'idPrefecture';

    protected $fillable = [
        'nom',
        'code',
        'region_id',
    ];

    /**
     * Région d'appartenance.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(
            Region::class,
            'region_id',
            'idRegion'
        );
    }

    /**
     * Communes de la préfecture.
     */
    public function communes(): HasMany
    {
        return $this->hasMany(
            Commune::class,
            'prefecture_id',
            'idPrefecture'
        );
    }

    /**
     * Utilisateurs actuellement rattachés
     * à cette préfecture.
     */
    public function users(): HasMany
    {
        return $this->hasMany(
            User::class,
            'prefecture_id',
            'idPrefecture'
        );
    }

    /**
     * Affectations de campagnes dans cette préfecture.
     */
    public function affectations(): HasMany
    {
        return $this->hasMany(
            Affectation::class,
            'prefecture_id',
            'idPrefecture'
        );
    }

    /**
     * Historique des rattachements des utilisateurs.
     */
    public function rattachements(): HasMany
    {
        return $this->hasMany(
            RattachementPrefecture::class,
            'prefecture_id',
            'idPrefecture'
        );
    }

        public function deploiements(): HasMany
    {
        return $this->hasMany(
            CampagneDeploiement::class,
            'prefecture_id',
            'idPrefecture'
        );
    }
}