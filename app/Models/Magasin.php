<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Magasin extends Model
{
    protected $primaryKey = 'idMagasin';
    protected $fillable = ['nom', 'localisation', 'capacite', 'responsable_id'];

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_id', 'idUser');
    }

    public function mouvementsStock(): HasMany
    {
        return $this->hasMany(MouvementStock::class, 'magasin_id', 'idMagasin');
    }
}