<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PointGPS extends Model
{
    /**
     * Nom de la table.
     */
    protected $table = 'point_gps';

    /**
     * Clé primaire.
     */
    protected $primaryKey = 'idPointGPS';

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
        'parcelle_id',

        // Coordonnées GPS
        'latitude',
        'longitude',
        'altitude',
        'precisionGPS',

        // Ordre du sommet
        'ordre',
    ];

    /**
     * Conversion des attributs.
     */
    protected $casts = [
        'latitude'    => 'decimal:7',
        'longitude'   => 'decimal:7',
        'altitude'    => 'decimal:2',
        'precisionGPS' => 'decimal:2',
        'ordre'       => 'integer',
    ];

    /**
     * Parcelle à laquelle appartient le point GPS.
     */
    public function parcelle(): BelongsTo
    {
        return $this->belongsTo(
            Parcelle::class,
            'parcelle_id',
            'idParcelle'
        );
    }
}