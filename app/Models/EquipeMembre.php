<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipeMembre extends Model
{
    protected $table = 'equipe_membres';

    protected $primaryKey = 'idEquipeMembre';

    protected $fillable = [
        'equipe_id',
        'user_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Équipe
    |--------------------------------------------------------------------------
    */

    public function equipe(): BelongsTo
    {
        return $this->belongsTo(
            Equipe::class,
            'equipe_id',
            'idEquipe'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Utilisateur
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id'
        );
    }
}