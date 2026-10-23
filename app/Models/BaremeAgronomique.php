<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BaremeAgronomique extends Model
{
    use HasFactory;

    protected $table = 'baremes_agronomiques';
    protected $primaryKey = 'idBareme';

    protected $fillable = [
        'prefecture_id',
        'culture_id',
        'intrant_id',
        'dose_par_hectare',
        'unite',
        'plafond_max_producteur',
        'est_actif',
    ];

    protected $casts = [
        'dose_par_hectare' => 'decimal:2',
        'plafond_max_producteur' => 'decimal:2',
        'est_actif' => 'boolean',
    ];

    public function prefecture(): BelongsTo
    {
        return $this->belongsTo(Prefecture::class, 'prefecture_id', 'idPrefecture');
    }

    public function culture(): BelongsTo
    {
        return $this->belongsTo(Culture::class, 'culture_id', 'idCulture');
    }

    public function intrant(): BelongsTo
    {
        return $this->belongsTo(Intrant::class, 'intrant_id', 'idIntrant');
    }
}