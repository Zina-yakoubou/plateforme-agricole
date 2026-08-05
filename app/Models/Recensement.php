<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recensement extends Model
{
    protected $primaryKey = 'idRecensement';
    protected $fillable = ['dateSaisie', 'estNouveauProducteur', 'donneesModifiees', 'campagne_id', 'exploitant_id', 'agent_id', 'alerte_id'];

    public function campagne(): BelongsTo
    {
        return $this->belongsTo(CampagneRecensement::class, 'campagne_id', 'idCampagne');
    }

    public function exploitant(): BelongsTo
    {
        return $this->belongsTo(Exploitant::class, 'exploitant_id', 'idExploitant');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id', 'idUser');
    }

    public function alerte(): BelongsTo
    {
        return $this->belongsTo(Alerte::class, 'alerte_id', 'idAlerte');
    }
}