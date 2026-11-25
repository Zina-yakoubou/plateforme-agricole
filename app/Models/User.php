<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use Notifiable;

    /*
    |--------------------------------------------------------------------------
    | Champs autorisés
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'name',
        'email',
        'telephone',
        'password',
        'statut',
        'role_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Champs cachés
    |--------------------------------------------------------------------------
    */

    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'telephone_verified_at' => 'datetime',
            'otp_expires_at' => 'datetime',
            'password' => 'hashed',
            'statut' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Rôle de l'utilisateur.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(
            Role::class,
            'role_id',
            'idRole'
        );
    }

    /**
     * Historique des rattachements aux préfectures.
     */
    public function rattachementsPrefecture(): HasMany
    {
        return $this->hasMany(
            RattachementPrefecture::class,
            'user_id'
        );
    }

    /**
     * Rattachement actif.
     */
    public function rattachementPrefectureActif(): HasOne
    {
        return $this->hasOne(
            RattachementPrefecture::class,
            'user_id'
        )
        ->where('statut', 'actif')
        ->whereNull('dateFin');
    }

    /**
     * Préfecture actuelle.
     */
    public function getPrefectureActuelleAttribute(): ?Prefecture
    {
        return $this->rattachementPrefectureActif?->prefecture;
    }

    /**
     * Affectations.
     */
    public function affectations(): HasMany
    {
        return $this->hasMany(
            Affectation::class,
            'user_id'
        );
    }

    /**
     * Affectation active.
     */
    public function affectationActive(): HasOne
    {
        return $this->hasOne(
            Affectation::class,
            'user_id'
        )->where('statut', 'ACTIVE');
    }

    /**
     * Équipes supervisées.
     */
    public function equipesSupervisees(): HasMany
    {
        return $this->hasMany(
            Equipe::class,
            'superviseur_id',
            'id'
        );
    }

    /**
     * Équipes dont l'utilisateur est membre.
     */
    public function equipes(): BelongsToMany
    {
        return $this->belongsToMany(
            Equipe::class,
            'equipe_membres',
            'user_id',
            'equipe_id',
            'id',
            'idEquipe'
        )->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | VÉRIFICATION DES RÔLES
    |--------------------------------------------------------------------------
    */

    /**
     * Vérifie le nom du rôle.
     */
    public function hasRole(string $role): bool
    {
        return $this->role?->nom === $role;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('Administrateur');
    }

    public function isDpa(): bool
    {
        return $this->hasRole('DPA');
    }

    public function isSuperviseur(): bool
    {
        return $this->hasRole('Superviseur');
    }

    public function isTechnicien(): bool
    {
        return $this->hasRole('Technicien');
    }

    public function isCach(): bool
    {
        return $this->hasRole('CACH');
    }

    public function isAgent(): bool
    {
        return $this->hasRole('Agent recenseur');
    }

        public function prefectureId(): ?int
    {
        $id = $this->rattachementPrefectureActif?->prefecture_id;

        return $id ? (int) $id : null;
    }
}