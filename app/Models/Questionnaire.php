<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Questionnaire extends Model
{
    /*
    |--------------------------------------------------------------------------
    | CONFIGURATION
    |--------------------------------------------------------------------------
    */

    protected $table = 'questionnaires';

    protected $primaryKey = 'idQuestionnaire';


    /*
    |--------------------------------------------------------------------------
    | ATTRIBUTS AUTORISÉS
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'codeQuestionnaire',
        'titre',
        'version',
        'description',
        'type',
        'fichier',
        'format',
        'actif',
        'created_by',
    ];


    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'actif' => 'boolean',
    ];


    /*
    |--------------------------------------------------------------------------
    | CRÉATEUR
    |--------------------------------------------------------------------------
    */

    public function createur(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CAMPAGNES
    |--------------------------------------------------------------------------
    |
    | Un questionnaire peut être utilisé par plusieurs campagnes.
    |
    */

    public function campagnes(): BelongsToMany
    {
        return $this->belongsToMany(
            CampagneRecensement::class,
            'campagne_questionnaires',
            'questionnaire_id',
            'campagne_id',
            'idQuestionnaire',
            'idCampagne'
        )->withTimestamps();
    }


    /*
    |--------------------------------------------------------------------------
    | LIGNES DU PIVOT
    |--------------------------------------------------------------------------
    */

    public function campagneQuestionnaires(): HasMany
    {
        return $this->hasMany(
            CampagneQuestionnaire::class,
            'questionnaire_id',
            'idQuestionnaire'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function estActif(): bool
    {
        return $this->actif === true;
    }


    public function estInactif(): bool
    {
        return $this->actif === false;
    }
}