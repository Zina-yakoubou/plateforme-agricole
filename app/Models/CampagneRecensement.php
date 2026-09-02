<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
class CampagneRecensement extends Model
{
    /*
    |--------------------------------------------------------------------------
    | CONFIGURATION
    |--------------------------------------------------------------------------
    */

    protected $table = 'campagne_recensements';

    protected $primaryKey = 'idCampagne';


    /*
    |--------------------------------------------------------------------------
    | ATTRIBUTS AUTORISÉS
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'codeCampagne',
        'libelle',
        'description',

        'objectifs',
        'resultatsAttendus',

        'methodologie',
        'instructions',

        'portee',

        'dateDebut',
        'dateFin',

        'statut',

        //'estOfficielle',

        'created_by',
    ];


    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'dateDebut'     => 'datetime',
        'dateFin'       => 'datetime',
        'estOfficielle' => 'boolean',
    ];


    /*
    |--------------------------------------------------------------------------
    | CRÉATEUR
    |--------------------------------------------------------------------------
    |
    | Utilisateur désigné pour enregistrer la campagne officielle
    | dans SIRA-Mô.
    |
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
    | AFFECTATIONS
    |--------------------------------------------------------------------------
    */

    public function affectations(): HasMany
    {
        return $this->hasMany(
            Affectation::class,
            'campagne_id',
            'idCampagne'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RECENSEMENTS
    |--------------------------------------------------------------------------
    */

    public function recensements(): HasMany
    {
        return $this->hasMany(
            Recensement::class,
            'campagne_id',
            'idCampagne'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SAISONS DE DISTRIBUTION
    |--------------------------------------------------------------------------
    */

    public function saisonsDistribution(): HasMany
    {
        return $this->hasMany(
            SaisonDistribution::class,
            'campagneBase_id',
            'idCampagne'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ZONES DE LA CAMPAGNE
    |--------------------------------------------------------------------------
    |
    | Les zones permettent de déterminer précisément les territoires
    | concernés par la campagne.
    |
    | Selon la portée :
    |
    | nationale
    |     → régions concernées
    |
    | regionale
    |     → préfectures concernées
    |
    | prefectorale
    |     → préfectures concernées
    |
    | Les niveaux territoriaux sont gérés dans campagne_zones.
    |
    */

    public function zones(): HasMany
    {
        return $this->hasMany(
            CampagneZone::class,
            'campagne_id',
            'idCampagne'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DÉPLOIEMENTS
    |--------------------------------------------------------------------------
    |
    | Permet de suivre la transmission de la campagne aux territoires
    | concernés.
    |
    */

    public function deploiements(): HasMany
    {
        return $this->hasMany(
            CampagneDeploiement::class,
            'campagne_id',
            'idCampagne'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | QUESTIONNAIRES
    |--------------------------------------------------------------------------
    |
    | Une campagne peut utiliser un ou plusieurs questionnaires.
    |
    */

    public function questionnaires(): BelongsToMany
    {
        return $this->belongsToMany(
            Questionnaire::class,
            'campagne_questionnaires',
            'campagne_id',
            'questionnaire_id',
            'idCampagne',
            'idQuestionnaire'
        )->withTimestamps();
    }


    /*
    |--------------------------------------------------------------------------
    | LIGNES DU PIVOT DES QUESTIONNAIRES
    |--------------------------------------------------------------------------
    */

    public function campagneQuestionnaires(): HasMany
    {
        return $this->hasMany(
            CampagneQuestionnaire::class,
            'campagne_id',
            'idCampagne'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PLANIFICATIONS
    |--------------------------------------------------------------------------
    |
    | Une campagne officielle peut faire l'objet d'une ou plusieurs
    | planifications opérationnelles.
    |
    */

        public function planification(): HasOne
    {
        return $this->hasOne(
            CampagnePlanification::class,
            'campagne_id',
            'idCampagne'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SYNCHRONISATION DU STATUT
    |--------------------------------------------------------------------------
    */

    public function synchroniserStatut(): void
    {
        /*
        |----------------------------------------------------------------------
        | UNE CAMPAGNE ARCHIVÉE NE REVIENT JAMAIS EN ARRIÈRE
        |----------------------------------------------------------------------
        */

        if ($this->statut === 'archivee') {
            return;
        }

        $maintenant = now();


        /*
        |----------------------------------------------------------------------
        | AVANT LE DÉBUT
        |----------------------------------------------------------------------
        */

        if ($maintenant->lt($this->dateDebut)) {

            $nouveauStatut = 'planifiee';
        }


        /*
        |----------------------------------------------------------------------
        | APRÈS LA FIN
        |----------------------------------------------------------------------
        */

        elseif (
            $this->dateFin !== null &&
            $maintenant->gte($this->dateFin)
        ) {

            $nouveauStatut = 'cloturee';
        }


        /*
        |----------------------------------------------------------------------
        | PÉRIODE ACTIVE
        |----------------------------------------------------------------------
        */

        else {

            $nouveauStatut = 'active';
        }


        /*
        |----------------------------------------------------------------------
        | MISE À JOUR
        |----------------------------------------------------------------------
        */

        if ($this->statut !== $nouveauStatut) {

            $this->updateQuietly([
                'statut' => $nouveauStatut,
            ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | HELPERS DE STATUT
    |--------------------------------------------------------------------------
    */

    public function estPlanifiee(): bool
    {
        return $this->statut === 'planifiee';
    }


    public function estActive(): bool
    {
        return $this->statut === 'active';
    }


    public function estCloturee(): bool
    {
        return $this->statut === 'cloturee';
    }


    public function estArchivee(): bool
    {
        return $this->statut === 'archivee';
    }


    /*
    |--------------------------------------------------------------------------
    | SYNCHRONISER TOUTES LES CAMPAGNES
    |--------------------------------------------------------------------------
    |
    | Utilisée par le Scheduler.
    |
    */

    public static function synchroniserToutes(): void
    {
        static::query()
            ->where('statut', '!=', 'archivee')
            ->get()
            ->each(function ($campagne) {

                $campagne->synchroniserStatut();

            });
    }


    /*
    |--------------------------------------------------------------------------
    | PRÉFECTURES CONCERNÉES PAR LA CAMPAGNE
    |--------------------------------------------------------------------------
    |
    | Calcule la liste unique des préfectures réellement concernées,
    | en remontant la hiérarchie territoriale pour chaque zone
    | (région exclue : une campagne nationale/régionale sans précision
    | n'implique pas de préfecture déterminée).
    |
    */

    public function prefecturesConcernees()
    {
        return $this->zones
            ->map(fn ($zone) => $zone->prefecture_rattachee)
            ->filter()
            ->unique('idPrefecture')
            ->values();
    }
}