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


   /**
     * Synchronise automatiquement le statut de la campagne 
     * en fonction de la date du jour (dateDebut et dateFin).
     */
    public function synchroniserStatut()
    {
        $maintenant = now();

        // 1. Si la campagne est 'planifiee' et que la date de début est atteinte ou dépassée
        if ($this->statut === 'planifiee' && $maintenant->greaterThanOrEqualTo($this->dateDebut)) {
            $this->update(['statut' => 'active']);
        }

        // 2. Si la campagne est 'active' et qu'il y a une date de fin dépassée
        if ($this->statut === 'active' && $this->dateFin && $maintenant->greaterThan($this->dateFin)) {
            $this->update(['statut' => 'cloturee']);
        }
    }

        /**
         * Synchronise l'ensemble des campagnes de la base de données (utilisé par le contrôleur ou le scheduler).
         */
        public static function synchroniserToutes()
        {
            $maintenant = now();

            // Activer les campagnes planifiées dont la date de début est atteinte
            self::where('statut', 'planifiee')
                ->where('dateDebut', '<=', $maintenant)
                ->update(['statut' => 'active']);

            // Clôturer les campagnes actives dont la date de fin est dépassée
            self::where('statut', 'active')
                ->whereNotNull('dateFin')
                ->where('dateFin', '<', $maintenant)
                ->update(['statut' => 'cloturee']);
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


    /**
     * Vérifie si la campagne est encore modifiable (ni clôturée ni archivée).
     */
    public function estModifiable(): bool
    {
        $this->synchroniserStatut();

        return !in_array($this->statut, ['cloturee', 'archivee']);
    }


        /*
    |--------------------------------------------------------------------------
    | SCOPES (ONGLETS « EN COURS » / « ANCIENNES »)
    |--------------------------------------------------------------------------
    */

    public function scopeEnCours(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->whereIn('statut', ['planifiee', 'active']);
    }

    public function scopeAnciennes(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->whereIn('statut', ['cloturee', 'archivee']);
    }
    
}