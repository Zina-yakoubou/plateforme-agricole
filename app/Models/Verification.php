<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Verification extends Model
{
    protected $table = 'verifications';

    protected $primaryKey = 'idVerification';

    protected $fillable = [
        'affectation_id',
        'recensement_id',
        'maison_id',
        'superviseur_id',
        'agent_id',
        'resultat',
        'remarque',
        'dateVisite',
    ];

    protected $casts = [
        'dateVisite' => 'datetime',
    ];

    public function affectation(): BelongsTo
    {
        return $this->belongsTo(Affectation::class, 'affectation_id', 'idAffectation');
    }

    public function recensement(): BelongsTo
    {
        return $this->belongsTo(Recensement::class, 'recensement_id', 'idRecensement');
    }

    public function maison(): BelongsTo
    {
        return $this->belongsTo(Maison::class, 'maison_id', 'idMaison');
    }

    public function superviseur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'superviseur_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
}