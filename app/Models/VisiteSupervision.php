<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisiteSupervision extends Model
{
    protected $table = 'visites_supervision';

    protected $fillable = [
        'superviseur_id',
        'agent_id',
        'village_id',
        'maison_id',
        'campagne_id',
        'date_visite',
        'statut',
        'observation',
    ];

    protected $casts = [
        'date_visite' => 'datetime',
    ];
}