<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Production extends Model
{
    protected $primaryKey = 'idProduction';

    protected $fillable = [
        'culture_parcelle_id',
        'quantiteProduite',
        'uniteProduction',
        'rendement',
        'dateRecolte',
        'observations',
    ];

    protected $casts = [
        'quantiteProduite' => 'decimal:2',
        'rendement' => 'decimal:2',
        'dateRecolte' => 'date',
    ];

    /* ============================
     | RELATIONS
     * ============================ */

    public function cultureParcelle(): BelongsTo
    {
        return $this->belongsTo(CultureParcelle::class, 'culture_parcelle_id', 'idCultureParcelle');
    }
}