<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Intrant extends Model
{
    /**
     * Nom de la table.
     */
    protected $table = 'intrants';

    /**
     * Clé primaire.
     */
    protected $primaryKey = 'idIntrant';

    /**
     * Type de la clé primaire.
     */
    protected $keyType = 'int';

    /**
     * Clé primaire auto-incrémentée.
     */
    public $incrementing = true;

    /**
     * Champs pouvant être remplis en masse.
     */
    protected $fillable = [
        'nom',
        'type',
        'unite',
        'actif',
    ];

    /**
     * Conversion des attributs.
     */
    protected $casts = [
        'actif' => 'boolean',
    ];

    /**
     * Cultures utilisant cet intrant.
     */
    public function cultures(): BelongsToMany
    {
        return $this->belongsToMany(
            Culture::class,
            'culture_intrants',
            'intrant_id',
            'culture_id',
            'idIntrant',
            'idCulture'
        )->withPivot([
            'quantite',
            'nombreApplications',
            'dateApplication',
            'observations',
        ])->withTimestamps();
    }

    /**
     * Vérifie si l'intrant est actif.
     */
    public function estActif(): bool
    {
        return $this->actif === true;
    }
}