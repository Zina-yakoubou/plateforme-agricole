<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BesoinIntrant extends Model
{
    protected $table = 'besoin_intrants';

    protected $primaryKey = 'idBesoin';

    protected $fillable = [
        'culture_parcelle_id',
        'intrant_id',
        'quantitePrevue',
        'unite',
        'dateEvaluation',
        'agentEvaluateur_id',
        'observations',
    ];

    protected $casts = [
        'quantitePrevue' => 'decimal:2',
        'dateEvaluation' => 'date',
    ];

    /* ============================
     | RELATIONS
     * ============================ */

    public function cultureParcelle(): BelongsTo
    {
        return $this->belongsTo(CultureParcelle::class, 'culture_parcelle_id', 'idCultureParcelle');
    }

    public function intrant(): BelongsTo
    {
        return $this->belongsTo(Intrant::class, 'intrant_id', 'idIntrant');
    }

    public function agentEvaluateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agentEvaluateur_id');
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(Allocation::class, 'besoin_id', 'idBesoin');
    }
}