<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

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
    | GESTION DU TEMPS ET STATUTS EN TEMPS RÉEL
    |--------------------------------------------------------------------------
    */

    /**
     * Calcule dynamiquement le statut réel en fonction de la date et de l'heure courante.
     */
    // public function getStatutReelAttribute(): string
    // {
    //     if (in_array($this->statut, ['archivee', 'annulee'])) {
    //         return $this->statut;
    //     }

    //     $maintenant = now();

    //     if ($this->dateDebut && $maintenant->lt($this->dateDebut)) {
    //         return 'planifiee';
    //     }

    //     if ($this->dateDebut && $maintenant->gte($this->dateDebut) && ($this->dateFin === null || $maintenant->lte($this->dateFin))) {
    //         return 'active';
    //     }

    //     if ($this->dateFin && $maintenant->gt($this->dateFin)) {
    //         return 'cloturee';
    //     }

    //     return $this->statut;
    // }

    /**
     * Synchronise et met à jour en BDD si le statut a basculé.
     */
    // public function synchroniserStatut(): bool
    // {
    //     $nouveauStatut = $this->statut_reel;

    //     if ($this->statut !== $nouveauStatut) {
    //         $this->statut = $nouveauStatut;
    //         return $this->save();
    //     }

    //     return false;
    // }


    /**
     * Synchronise et met à jour en BDD si le statut a basculé.
     * Désactive automatiquement toutes les affectations si la campagne est clôturée.
     */
   /**
     * Calcule dynamiquement le statut réel en fonction de la date et de l'heure courante.
     */
    public function getStatutReelAttribute(): string
    {
        if (in_array($this->statut, ['archivee', 'annulee'])) {
            return $this->statut;
        }

        $maintenant = now();

        if ($this->dateDebut && $maintenant->lt($this->dateDebut)) {
            return 'planifiee';
        }

        if ($this->dateDebut && $maintenant->gte($this->dateDebut) && ($this->dateFin === null || $maintenant->lte($this->dateFin))) {
            return 'active';
        }

        if ($this->dateFin && $maintenant->gt($this->dateFin)) {
            return 'cloturee';
        }

        return $this->statut;
    }

    /**
     * Synchronise le statut de la campagne ET désactive ses affectations si elle est clôturée.
     */
    public function synchroniserStatut(): bool
    {
        $nouveauStatut = $this->statut_reel;
        $estModifie = false;

        // 1. Mettre à jour le statut de la campagne
        if ($this->statut !== $nouveauStatut) {
            $this->statut = $nouveauStatut;
            $this->save();
            $estModifie = true;
        }

        // 2. Si la campagne est clôturée ou archivée, basculer ses affectations actives en "annulee"
        if (in_array($this->statut, ['cloturee', 'archivee'])) {
            $nbModifies = $this->affectations()
                ->where('statut', 'active')
                ->update([
                    'statut'  => 'inactive',
                    'dateFin' => now(),
                ]);

            if ($nbModifies > 0) {
                $estModifie = true;
            }
        }

        return $estModifie;
    }

    /**
     * Synchronise TOUTES les campagnes de la base de données.
     */
    public static function synchroniserToutes(): void
    {
        $campagnes = self::all();
        foreach ($campagnes as $campagne) {
            $campagne->synchroniserStatut();
        }
    }

    /**
     * Synchronise toutes les campagnes de la base de données.
     */
    // public static function synchroniserToutes(): void
    // {
    //     $campagnes = self::whereNotIn('statut', ['archivee', 'annulee'])->get();
    //     foreach ($campagnes as $campagne) {
    //         $campagne->synchroniserStatut();
    //     }
    // }

    public function estPlanifiee(): bool
    {
        $this->synchroniserStatut();
        return $this->statut === 'planifiee';
    }

    public function estActive(): bool
    {
        $this->synchroniserStatut();
        return $this->statut === 'active';
    }

    public function estCloturee(): bool
    {
        $this->synchroniserStatut();
        return $this->statut === 'cloturee';
    }

    public function estArchivee(): bool
    {
        $this->synchroniserStatut();
        return $this->statut === 'archivee';
    }

    public function estModifiable(): bool
    {
        $this->synchroniserStatut();
        return !in_array($this->statut, ['cloturee', 'archivee']);
    }


    /*
    |--------------------------------------------------------------------------
    | PRÉFECTURES CONCERNÉES PAR LA CAMPAGNE
    |--------------------------------------------------------------------------
    */

    public function prefecturesConcernees()
    {
        return $this->zones
            ->map(fn ($zone) => $zone->prefecture_rattachee)
            ->filter()
            ->unique('idPrefecture')
            ->values();
    }


        public function periodeTexte(): string
    {
        return 'du ' . \Illuminate\Support\Carbon::parse($this->dateDebut)->format('d/m/Y')
            . ' au ' . \Illuminate\Support\Carbon::parse($this->dateFin)->format('d/m/Y');
    }

    /** Vrai si la période donnée est entièrement comprise dans celle de la campagne (au jour près). */
    public function contientPeriode($debut, $fin = null): bool
    {
        $d = \Illuminate\Support\Carbon::parse($debut)->toDateString();
        $f = \Illuminate\Support\Carbon::parse($fin ?? $debut)->toDateString();

        $cd = \Illuminate\Support\Carbon::parse($this->dateDebut)->toDateString();
        $cf = \Illuminate\Support\Carbon::parse($this->dateFin)->toDateString();

        return $d >= $cd && $f <= $cf;
    }
}