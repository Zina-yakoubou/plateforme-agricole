<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Affectation extends Model
{
    protected $primaryKey = 'idAffectation';

    protected $fillable = [
        'reference',
        'dateDebut',
        'dateFin',
        'statut',
        'user_id',
        'campagne_id',
        'prefecture_id',
        'canton_id',
        'village_id',
    ];

    /**
     * Utilisateur concerné
     */
    // public function user(): BelongsTo
    // {
    //     return $this->belongsTo(User::class);
    // }


        public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Campagne de recensement
     */
    public function campagne(): BelongsTo
    {
        return $this->belongsTo(
            CampagneRecensement::class,
            'campagne_id',
            'idCampagne'
        );
    }

    /**
     * Préfecture de rattachement
     */
    public function prefecture(): BelongsTo
    {
        return $this->belongsTo(
            Prefecture::class,
            'prefecture_id',
            'idPrefecture'
        );
    }

    /**
     * Canton d'affectation
     */
    public function canton(): BelongsTo
    {
        return $this->belongsTo(
            Canton::class,
            'canton_id',
            'idCanton'
        );
    }

    /**
     * Village d'affectation
     */
    public function village(): BelongsTo
    {
        return $this->belongsTo(
            Village::class,
            'village_id',
            'idVillage'
        );
    }
}