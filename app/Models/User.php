<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
    | Relations
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
     *
     * Un utilisateur peut avoir plusieurs rattachements
     * au cours de sa carrière.
     */
    public function rattachementsPrefecture(): HasMany
    {
        return $this->hasMany(
            RattachementPrefecture::class,
            'user_id',
            'id'
        );
    }


    /**
     * Rattachement préfectoral actuellement actif.
     *
     * Un utilisateur ne doit avoir qu'un seul
     * rattachement actif à la fois.
     */
    public function rattachementPrefectureActif(): HasOne
    {
        return $this->hasOne(
            RattachementPrefecture::class,
            'user_id',
            'id'
        )->where('statut', 'actif')
         ->whereNull('dateFin');
    }


    /**
     * Préfecture actuellement associée à l'utilisateur.
     *
     * Cette relation passe désormais par le rattachement actif.
     */
    public function getPrefectureActuelleAttribute(): ?Prefecture
    {
        return $this->rattachementPrefectureActif?->prefecture;
    }


    /**
     * Affectations de l'utilisateur dans les campagnes.
     *
     * Une affectation correspond à une mission
     * de terrain dans une campagne.
     */
    public function affectations(): HasMany
    {
        return $this->hasMany(
            Affectation::class,
            'user_id',
            'id'
        );
    }


    /**
     * Affectation active de l'utilisateur.
     */
    public function affectationActive(): HasOne
    {
        return $this->hasOne(
            Affectation::class,
            'user_id',
            'id'
        )->where('statut', 'ACTIVE');
    }


    /*
    |--------------------------------------------------------------------------
    | Vérification des rôles
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->role_id === 'R01';
    }


    public function isDpa(): bool
    {
        return $this->role_id === 'R02';
    }


    public function isSuperviseur(): bool
    {
        return $this->role_id === 'R03';
    }


    public function isTechnicien(): bool
    {
        return $this->role_id === 'R04';
    }


    public function isCach(): bool
    {
        return $this->role_id === 'R05';
    }


    public function isAgent(): bool
    {
        return $this->role_id === 'R06';
    }




    /*
|--------------------------------------------------------------------------
| ÉQUIPES SUPERVISÉES
|--------------------------------------------------------------------------
*/

public function equipesSupervisees(): HasMany
{
    return $this->hasMany(
        Equipe::class,
        'superviseur_id'
    );
}

/*
|--------------------------------------------------------------------------
| ÉQUIPES DONT L'UTILISATEUR EST MEMBRE
|--------------------------------------------------------------------------
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
}