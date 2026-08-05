<?php

namespace App\Models;

use App\Models\Parcelle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PointGPS extends Model
{
    protected $table = 'point_gps';
    protected $primaryKey = 'idPointGPS';
    protected $fillable = ['latitude', 'longitude', 'altitude', 'precision', 'parcelle_id'];

    public function parcelle(): BelongsTo
    {
        return $this->belongsTo(Parcelle::class, 'parcelle_id', 'idParcelle');
    }
}