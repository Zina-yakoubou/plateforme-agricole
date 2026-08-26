<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BesoinPlanification extends Model
{
    protected $table = 'besoins_planification';

    protected $primaryKey = 'idBesoin';

    protected $fillable = [
        'planification_prefectorale_id',
        'categorie',
        'designation',
        'quantite',
        'unite',
        'observations',
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
    ];

    public function planificationPrefectorale(): BelongsTo
    {
        return $this->belongsTo(
            PlanificationPrefectorale::class,
            'planification_prefectorale_id',
            'idPlanificationPrefectorale'
        );
    }
}