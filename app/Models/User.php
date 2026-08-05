<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [

        'name',
        'email',
        'telephone',
        'login',
        'password',
        'statut',
        'role_id',

    ];

    protected $hidden = [

        'password',
        'remember_token',

    ];

    protected function casts(): array
    {
        return [

            'email_verified_at' => 'datetime',
            'password' => 'hashed',

        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'idRole');
    }



        public function isAdmin(): bool
    {
        return $this->role()->where('nom', 'Administrateur')->exists();
    }

    public function isDirecteur(): bool
    {
        return $this->role()->where('nom', 'Directeur préfectoral')->exists();
    }

    public function isAgent(): bool
    {
        return $this->role()->where('nom', 'Agent recenseur')->exists();
    }
}