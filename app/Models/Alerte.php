<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Alerte extends Model
{
    protected $primaryKey = 'idAlerte';
    protected $fillable = ['type', 'dateAlerte', 'statut', 'message', 'superviseur_id'];

    public function superviseur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'superviseur_id', 'idUser');
    }

    public function recensement(): HasOne
    {
        return $this->hasOne(Recensement::class, 'alerte_id', 'idAlerte');
    }
}