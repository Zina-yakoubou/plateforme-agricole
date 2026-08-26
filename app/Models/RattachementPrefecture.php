<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RattachementPrefecture extends Model
{
    protected $table = 'rattachements_prefecture';

    protected $fillable = [
        'user_id',
        'prefecture_id',
        'dateDebut',
        'dateFin',
        'statut',
    ];

    protected $casts = [
        'dateDebut' => 'date',
        'dateFin' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    /**
     * Utilisateur concerné par le rattachement.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id'
        );
    }

    /**
     * Préfecture du rattachement.
     */
    public function prefecture(): BelongsTo
    {
        return $this->belongsTo(
            Prefecture::class,
            'prefecture_id',
            'idPrefecture'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Méthodes métier
    |--------------------------------------------------------------------------
    */

    /**
     * Vérifie si le rattachement est actuellement actif.
     */
    public function estActif(): bool
    {
        return $this->statut === 'actif'
            && $this->dateFin === null;
    }

    /**
     * Termine le rattachement.
     */
    public function terminer(?\DateTimeInterface $date = null): void
    {
        $this->update([
            'dateFin' => $date ?? now()->toDateString(),
            'statut' => 'termine',
        ]);
    }
}