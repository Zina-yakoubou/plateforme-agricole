<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferentielCultureIntrant extends Model
{
    protected $table = 'referentiel_culture_intrants';
    protected $primaryKey = 'idReferentielCultureIntrant';

    protected $fillable = ['culture_id', 'intrant_id'];

    public function culture(): BelongsTo
    {
        return $this->belongsTo(Culture::class, 'culture_id', 'idCulture');
    }

    public function intrant(): BelongsTo
    {
        return $this->belongsTo(Intrant::class, 'intrant_id', 'idIntrant');
    }
}