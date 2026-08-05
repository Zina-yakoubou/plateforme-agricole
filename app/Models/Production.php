<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Production extends Model
{
    protected $primaryKey = 'idProduction';
    protected $fillable = ['quantiteProduite', 'dateRecolte', 'rendement', 'culture_id'];

    public function culture(): BelongsTo
    {
        return $this->belongsTo(Culture::class, 'culture_id', 'idCulture');
    }
}