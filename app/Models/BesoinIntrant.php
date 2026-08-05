<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Culture;

class BesoinIntrant extends Model
{
    protected $primaryKey = 'idBesoin';
    protected $fillable = ['quantitePrevue', 'unite', 'dateEvaluation', 'agentEvaluateur_id', 'culture_id', 'intrant_id'];

    public function agentEvaluateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agentEvaluateur_id', 'idUser');
    }

    public function culture(): BelongsTo
    {
        return $this->belongsTo(Culture::class, 'culture_id', 'idCulture');
    }

    public function intrant(): BelongsTo
    {
        return $this->belongsTo('App\Models\Intrant', 'intrant_id', 'idIntrant');
    }
}