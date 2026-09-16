<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Menage extends Model
{
    use HasFactory;

    protected $table = 'menages';

    protected $primaryKey = 'idMenage';

    protected $fillable = [
        'uid',
        'recensement_id',
        'numeroMenage',
        'nomChef',
        'prenomChef',
        'sexeChef',
        'nombreHommes',
        'nombreFemmes',
        'nombreGarcons',
        'nombreFilles',
        'possedeExploitation',
        'observations',
        'statut',
    ];

    protected $casts = [
        'possedeExploitation' => 'boolean',
        'nombreHommes' => 'integer',
        'nombreFemmes' => 'integer',
        'nombreGarcons' => 'integer',
        'nombreFilles' => 'integer',
    ];

    /**
     * Générer automatiquement l'UID du ménage.
     */
    protected static function booted(): void
    {
        static::creating(function (Menage $menage) {
            if (empty($menage->uid)) {
                $menage->uid = (string) Str::uuid();
            }
        });
    }

    /**
     * Recensement auquel appartient le ménage.
     */
    public function recensement(): BelongsTo
    {
        return $this->belongsTo(
            Recensement::class,
            'recensement_id',
            'idRecensement'
        );
    }
}