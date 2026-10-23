<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Affectation extends Model
{
    protected $table = 'affectations';

    protected $primaryKey = 'idAffectation';

    protected $fillable = [
        'reference',
        'campagne_id',
        'equipe_id',
        'village_id',
        'dateDebut',
        'dateFin',
        'statut',
        'observations',
    ];

    protected $casts = [
        'dateDebut' => 'date',
        'dateFin'   => 'date',
    ];

    /* ---------------- RELATIONS ---------------- */

    public function campagne(): BelongsTo
    {
        return $this->belongsTo(CampagneRecensement::class, 'campagne_id', 'idCampagne');
    }

    public function equipe(): BelongsTo
    {
        return $this->belongsTo(Equipe::class, 'equipe_id', 'idEquipe');
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class, 'village_id', 'idVillage');
    }

    /* ---------------- STATUT ---------------- */

    public function estActive(): bool
    {
        return $this->statut === 'active';
    }

    public function estTerminee(): bool
    {
        return $this->statut === 'terminee';
    }

    public function estAnnulee(): bool
    {
        return $this->statut === 'annulee';
    }

    // Modifiable seulement si l'affectation est active
    public function estModifiable(): bool
    {
        return $this->statut === 'active';
    }

    /* ---------------- SCOPES ---------------- */

    public function scopeEnCours(Builder $query)
    {
        return $query->where('statut', 'active');
    }

    public function scopeAnciennes(Builder $query)
    {
        return $query->whereIn('statut', ['terminee', 'annulee']);
    }
}