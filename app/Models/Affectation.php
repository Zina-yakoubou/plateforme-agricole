<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Affectation extends Model
{
    protected $primaryKey = 'idAffectation';
    protected $fillable = ['reference', 'dateDebut', 'dateFin', 'statut', 'user_id', 'campagne_id', 'canton_id', 'village_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'idUser');
    }

    public function campagne(): BelongsTo
    {
        return $this->belongsTo(CampagneRecensement::class, 'campagne_id', 'idCampagne');
    }

    public function canton(): BelongsTo
    {
        return $this->belongsTo(Canton::class, 'canton_id', 'idCanton');
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class, 'village_id', 'idVillage');
    }
}