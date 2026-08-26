<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $table = 'roles';

    protected $primaryKey = 'idRole';

    /**
     * idRole contient des valeurs comme R01, R02, R03...
     */
    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'idRole',
        'nom',
        'description',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(
            User::class,
            'role_id',
            'idRole'
        );
    }
}