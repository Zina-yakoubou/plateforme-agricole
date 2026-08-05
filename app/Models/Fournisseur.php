<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fournisseur extends Model
{
    protected $primaryKey = 'idFournisseur';
    protected $fillable = ['nom', 'contact', 'adresse', 'specialiteIntrants'];

    public function mouvementsStock(): HasMany
    {
        return $this->hasMany(MouvementStock::class, 'fournisseur_id', 'idFournisseur');
    }
}