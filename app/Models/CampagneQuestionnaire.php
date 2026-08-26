<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampagneQuestionnaire extends Model
{
    /*
    |--------------------------------------------------------------------------
    | CONFIGURATION
    |--------------------------------------------------------------------------
    */

    protected $table = 'campagne_questionnaires';

    protected $primaryKey = 'idCampagneQuestionnaire';


    /*
    |--------------------------------------------------------------------------
    | ATTRIBUTS AUTORISÉS
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'campagne_id',
        'questionnaire_id',
    ];


    /*
    |--------------------------------------------------------------------------
    | CAMPAGNE
    |--------------------------------------------------------------------------
    */

    public function campagne(): BelongsTo
    {
        return $this->belongsTo(
            CampagneRecensement::class,
            'campagne_id',
            'idCampagne'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | QUESTIONNAIRE
    |--------------------------------------------------------------------------
    */

    public function questionnaire(): BelongsTo
    {
        return $this->belongsTo(
            Questionnaire::class,
            'questionnaire_id',
            'idQuestionnaire'
        );
    }
}