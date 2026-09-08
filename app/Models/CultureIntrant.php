<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CultureIntrant extends Model
{
    /**
     * Nom de la table.
     */
    protected $table = 'culture_intrants';

    /**
     * Clé primaire.
     */
    protected $primaryKey = 'id';

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
        'culture_id',
        'intrant_id',
        'quantite',
        'nombreApplications',
        'dateApplication',
        'observations',
    ];

    /**
     * Conversion des attributs.
     */
    protected $casts = [
        'quantite'            => 'decimal:2',
        'nombreApplications'  => 'integer',
        'dateApplication'     => 'date',
    ];

    /**
     * Culture concernée.
     */
    public function culture(): BelongsTo
    {
        return $this->belongsTo(
            Culture::class,
            'culture_id',
            'idCulture'
        );
    }

    /**
     * Intrant utilisé.
     */
    public function intrant(): BelongsTo
    {
        return $this->belongsTo(
            Intrant::class,
            'intrant_id',
            'idIntrant'
        );
    }
}