<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanificationActiviteAdaptation extends Model
{
    protected $table = 'planification_activite_adaptations';

    protected $primaryKey = 'idAdaptation';

    protected $fillable = [
        'planification_prefectorale_id',
        'activite_id',
        'dateDebutLocale',
        'dateFinLocale',
        'statut',
        'observations',
    ];

    protected $casts = [
        'dateDebutLocale' => 'datetime',
        'dateFinLocale' => 'datetime',
    ];

    public function planificationPrefectorale(): BelongsTo
    {
        return $this->belongsTo(
            PlanificationPrefectorale::class,
            'planification_prefectorale_id',
            'idPlanificationPrefectorale'
        );
    }

    public function activite(): BelongsTo
    {
        return $this->belongsTo(
            PlanificationActivite::class,
            'activite_id',
            'idActivite'
        );
    }
}