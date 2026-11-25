<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Culture extends Model
{
    protected $primaryKey = 'idCulture';

    protected $fillable = [
        'nomCulture',
        'categorie',
        'description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /* ============================
     | RELATIONS
     * ============================ */

    public function culturesParcelles(): HasMany
    {
        return $this->hasMany(CultureParcelle::class, 'culture_id', 'idCulture');
    }


        public function intrants()
    {
        return $this->belongsToMany(
            Intrant::class, 
            'culture_intrants',        // Nom de ta table pivot
            'culture_parcelle_id',     // Clé étrangère pour la culture dans la pivot
            'intrant_id',              // Clé étrangère pour l'intrant
            'idCulture',               // Clé primaire de la table cultures
            'idIntrant'                // Clé primaire de la table intrants
        );
    }


    
    public function intrantsRecommandes(): BelongsToMany
    {
        return $this->belongsToMany(
            Intrant::class,
            'referentiel_culture_intrants',
            'culture_id',
            'intrant_id',
            'idCulture',
            'idIntrant'
        );
    }
}