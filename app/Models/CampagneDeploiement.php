<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampagneDeploiement extends Model
{
    protected $table = 'campagne_deploiements';

    protected $primaryKey = 'idDeploiement';

    protected $fillable = [
        'campagne_id',
        'prefecture_id',
        'statut',
        'dateReception',
        'recu_par',
    ];

    protected $casts = [
        'dateReception' => 'datetime',
    ];


    /*
    |--------------------------------------------------------------------------
    | CAMPAGNE
    |--------------------------------------------------------------------------
    */

    public function campagne(): BelongsTo
    {
        return $this->belongsTo(
            CampagneRecensement::class,
            'campagne_id',
            'idCampagne'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PRÉFECTURE
    |--------------------------------------------------------------------------
    */

    public function prefecture(): BelongsTo
    {
        return $this->belongsTo(
            Prefecture::class,
            'prefecture_id',
            'idPrefecture'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UTILISATEUR AYANT ACCUSÉ RÉCEPTION
    |--------------------------------------------------------------------------
    */

    public function recuPar(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'recu_par'
        );
    }

        public function planificationPrefectorale()
    {
        return $this->hasOne(
            PlanificationPrefectorale::class,
            'deploiement_id',
            'idDeploiement'
        );
    }
}