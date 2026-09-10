<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MouvementStock extends Model
{
    protected $table = 'mouvement_stocks';

    protected $primaryKey = 'idMouvement';

    protected $fillable = [
        'magasin_id',
        'intrant_id',
        'agentResponsable_id',
        'fournisseur_id',
        'distribution_id',
        'type',
        'quantite',
        'unite',
        'dateMouvement',
        'reference',
        'motif',
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
        'dateMouvement' => 'datetime',
    ];

    /* ============================
     | RELATIONS
     * ============================ */

    public function magasin(): BelongsTo
    {
        return $this->belongsTo(Magasin::class, 'magasin_id', 'idMagasin');
    }

    public function intrant(): BelongsTo
    {
        return $this->belongsTo(Intrant::class, 'intrant_id', 'idIntrant');
    }

    public function agentResponsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agentResponsable_id');
    }

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class, 'fournisseur_id', 'idFournisseur');
    }

    public function distribution(): BelongsTo
    {
        return $this->belongsTo(Distribution::class, 'distribution_id', 'idDistribution');
    }
}