<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menage extends Model
{
    protected $primaryKey = 'idMenage';
    protected $fillable = ['nomChef', 'nombrePersonnes', 'aChamp', 'maison_id'];

    public function maison(): BelongsTo
    {
        return $this->belongsTo(Maison::class, 'maison_id', 'idMaison');
    }

    public function exploitants(): HasMany
    {
        return $this->hasMany(Exploitant::class, 'menage_id', 'idMenage');
    }
}