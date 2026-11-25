<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BesoinIntrant extends Model
{
    use HasFactory;

    /**
     * Nom de la table associée au modèle.
     *
     * @var string
     */
    protected $table = 'besoin_intrants';

    /**
     * Clé primaire personnalisée de la table.
     *
     * @var string
     */
    protected $primaryKey = 'idBesoin';

    /**
     * Attributs pouvant être assignés en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'culture_parcelle_id',
        'intrant_id',
        'quantitePrevue',
        'unite',
        'dateEvaluation',
        'agentEvaluateur_id',
        'observations',
    ];

    /**
     * Conversion automatique des types d'attributs.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantitePrevue' => 'decimal:2',
        'dateEvaluation' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS ELOQUENT
    |--------------------------------------------------------------------------
    */

    /**
     * Obtenir la culture de la parcelle associée à ce besoin.
     */
    public function cultureParcelle(): BelongsTo
    {
        return $this->belongsTo(CultureParcelle::class, 'culture_parcelle_id', 'idCultureParcelle');
    }

    /**
     * Obtenir l'intrant demandé.
     */
    public function intrant(): BelongsTo
    {
        return $this->belongsTo(Intrant::class, 'intrant_id', 'idIntrant');
    }

    /**
     * Obtenir l'agent (User) qui a évalué le besoin.
     */
    public function agentEvaluateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agentEvaluateur_id', 'id');
    }
}