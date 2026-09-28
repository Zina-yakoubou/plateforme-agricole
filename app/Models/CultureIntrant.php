<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CultureIntrant extends Model
{
    protected $table = 'culture_intrants';

    protected $primaryKey = 'id';

    protected $fillable = [
        'culture_parcelle_id',
        'intrant_id',
        'quantite',
        'nombreApplications',
        'dateApplication',
        'observations',
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
        'nombreApplications' => 'integer',
        'dateApplication' => 'date',
    ];

    public function cultureParcelle(): BelongsTo
    {
        return $this->belongsTo(
            CultureParcelle::class,
            'culture_parcelle_id',
            'idCultureParcelle'
        );
    }

    public function intrant(): BelongsTo
    {
        return $this->belongsTo(
            Intrant::class,
            'intrant_id',
            'idIntrant'
        );
    }
}