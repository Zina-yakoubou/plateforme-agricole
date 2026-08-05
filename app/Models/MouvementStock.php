<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MouvementStock extends Model
{
    protected $primaryKey = 'idMouvement';
    protected $fillable = ['type', 'quantite', 'date', 'motif', 'agentResponsable_id', 'intrant_id', 'magasin_id', 'fournisseur_id'];

    public function agentResponsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agentResponsable_id', 'idUser');
    }

    public function intrant(): BelongsTo
    {
        return $this->belongsTo(Intrant::class, 'intrant_id', 'idIntrant');
    }

    public function magasin(): BelongsTo
    {
        return $this->belongsTo(Magasin::class, 'magasin_id', 'idMagasin');
    }

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class, 'fournisseur_id', 'idFournisseur');
    }
}