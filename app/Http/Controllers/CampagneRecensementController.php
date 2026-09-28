<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCampagneRecensementRequest;
use App\Http\Requests\UpdateCampagneRecensementRequest;
use App\Models\CampagneDeploiement;
use App\Models\CampagneRecensement;
use App\Models\Prefecture;
use App\Models\Questionnaire;
use App\Models\Region;
use App\Models\Structure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CampagneRecensementController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $user = Auth::user();

        /*
        | Synchronisation de sécurité.
        |
        | Le Scheduler fera normalement cette opération automatiquement.
        | Ici, on garde cette synchronisation pour qu'un affichage reste
        | toujours cohérent même si le Scheduler n'a pas encore tourné.
        */

        // Resolve the model dynamically so static analysis does not require
        // the model class to be indexed in this controller's context.
        forward_static_call([
            'App\\Models\\CampagneRecensement',
            'synchroniserToutes',
        ]);


        $search = $request->search;

        $query = app('App\\Models\\CampagneRecensement')->newQuery()->with([
            // 'structure',
            'createur',

            'zones.region',
            'zones.prefecture',
            'zones.commune',
            'zones.canton',
            'zones.village',

            'questionnaires',

            'deploiements.prefecture',
        ]);


        /*
        |--------------------------------------------------------------------------
        | FILTRAGE TERRITORIAL DU DPA
        |--------------------------------------------------------------------------
        */

        if ($user && method_exists($user, 'isDpa') && $user->isDpa()) {

            $prefecture = $user->prefectureActuelle;

            /*
            | Aucun rattachement actif
            */

            if (!$prefecture) {

                $query->whereRaw('1 = 0');

            } else {

                $query->where(function ($q) use ($prefecture) {

                    /*
                    |--------------------------------------------------------------------------
                    | CAMPAGNE NATIONALE
                    |--------------------------------------------------------------------------
                    |
                    | Toutes les préfectures sont concernées.
                    |
                    */

                    $q->where(
                        'portee',
                        'nationale'
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | CAMPAGNE TERRITORIALE
                    |--------------------------------------------------------------------------
                    |
                    | La préfecture du DPA doit apparaître dans campagne_zones.
                    |
                    */

                    $q->orWhereHas(
                        'zones',
                        function ($zone) use ($prefecture) {

                            $zone->where(
                                'prefecture_id',
                                $prefecture->idPrefecture
                            );
                        }
                    );
                });
            }
        }


        /*
        |--------------------------------------------------------------------------
        | RECHERCHE
        |--------------------------------------------------------------------------
        */

        $query->when(
            $search,
            function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where(
                        'libelle',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'codeCampagne',
                        'like',
                        "%{$search}%"
                    );
                });
            }
        );


        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $campagnes = $query
            ->orderByDesc('created_at')
            ->paginate(5)
            ->withQueryString();


        return view(
            'campagnes.index',
            compact(
                'campagnes',
                'search'
            )
        );
    }




        public function create()
    {
        $regions = Region::with([
            'prefectures.communes.cantons.villages',
        ])
            ->orderBy('nom')
            ->get();

        $questionnaires = Questionnaire::query()
            ->where('actif', true)     // ou simplement ->get() si tous sont actifs
            ->orderBy('titre')
            ->get();

        return view(
            'campagnes.create',
            compact(
                'regions',
                'questionnaires'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GÉNÉRATION DU CODE
    |--------------------------------------------------------------------------
    */

    private function genererCodeCampagne(): string
    {
        $annee = now()->format('Y');

        $campagneModel = app(\App\Models\CampagneRecensement::class);

        $dernierNumero = $campagneModel
            ->newQuery()
            ->where(
                'codeCampagne',
                'like',
                "CAM-{$annee}-%"
            )
            ->get()
            ->map(function ($campagne) use ($annee) {

                return (int) str_replace(
                    "CAM-{$annee}-",
                    '',
                    $campagne->codeCampagne
                );
            })
            ->max();


        $numero = ($dernierNumero ?? 0) + 1;


        return sprintf(
            'CAM-%s-%03d',
            $annee,
            $numero
        );
    }


   

    public function store(StoreCampagneRecensementRequest $request)
{
    $validated = $request->validated();

    DB::transaction(function () use ($validated) {

        /*
        |--------------------------------------------------------------------------
        | CRÉATION DE LA CAMPAGNE
        |--------------------------------------------------------------------------
        */

        $campagne = CampagneRecensement::create([
            'codeCampagne'       => $this->genererCodeCampagne(),
            'libelle'            => $validated['libelle'],
            'description'        => $validated['description'] ?? null,
            'objectifs'          => $validated['objectifs'],
            'resultatsAttendus'  => $validated['resultatsAttendus'] ?? null,
            'methodologie'       => $validated['methodologie'] ?? null,
            'portee'             => $validated['portee'],
            'dateDebut'          => $validated['dateDebut'],
            'dateFin'            => $validated['dateFin'] ?? null,
            'statut'             => 'planifiee',
            'created_by'         => Auth::user()->id,
        ]);

        /*
        |--------------------------------------------------------------------------
        | QUESTIONNAIRES
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['questionnaire_ids'])) {
            $campagne->questionnaires()->sync($validated['questionnaire_ids']);
        }

        /*
        |--------------------------------------------------------------------------
        | ZONES BÉNÉFICIAIRES
        |--------------------------------------------------------------------------
        */

        switch ($validated['portee']) {

            /*
            |--------------------------------------------------------------
            | CAMPAGNE NATIONALE
            |--------------------------------------------------------------
            */

            case 'nationale':

                $campagne->zones()->create([
                    'statut' => 'planifiee',
                ]);

                break;

            /*
            |--------------------------------------------------------------
            | CAMPAGNE RÉGIONALE
            |--------------------------------------------------------------
            */

            case 'regionale':

                foreach (($validated['region_ids'] ?? []) as $regionId) {

                    $campagne->zones()->create([
                        'region_id' => $regionId,
                        'statut'    => 'planifiee',
                    ]);
                }

                // Une campagne régionale peut aussi préciser des niveaux
                // plus fins (préfecture, commune, canton, village) via
                // l'accordéon, en plus de la région.

                foreach (($validated['prefecture_ids'] ?? []) as $prefectureId) {

                    $campagne->zones()->create([
                        'prefecture_id' => $prefectureId,
                        'statut'        => 'planifiee',
                    ]);
                }

                foreach (($validated['commune_ids'] ?? []) as $communeId) {

                    $campagne->zones()->create([
                        'commune_id' => $communeId,
                        'statut'     => 'planifiee',
                    ]);
                }

                foreach (($validated['canton_ids'] ?? []) as $cantonId) {

                    $campagne->zones()->create([
                        'canton_id' => $cantonId,
                        'statut'    => 'planifiee',
                    ]);
                }

                foreach (($validated['village_ids'] ?? []) as $villageId) {

                    $campagne->zones()->create([
                        'village_id' => $villageId,
                        'statut'     => 'planifiee',
                    ]);
                }

                break;

            /*
            |--------------------------------------------------------------
            | CAMPAGNE PRÉFECTORALE
            |--------------------------------------------------------------
            */

            case 'prefectorale':

                foreach (($validated['prefecture_ids'] ?? []) as $prefectureId) {

                    $campagne->zones()->create([
                        'prefecture_id' => $prefectureId,
                        'statut'        => 'planifiee',
                    ]);
                }

                foreach (($validated['commune_ids'] ?? []) as $communeId) {

                    $campagne->zones()->create([
                        'commune_id' => $communeId,
                        'statut'     => 'planifiee',
                    ]);
                }

                foreach (($validated['canton_ids'] ?? []) as $cantonId) {

                    $campagne->zones()->create([
                        'canton_id' => $cantonId,
                        'statut'    => 'planifiee',
                    ]);
                }

                foreach (($validated['village_ids'] ?? []) as $villageId) {

                    $campagne->zones()->create([
                        'village_id' => $villageId,
                        'statut'     => 'planifiee',
                    ]);
                }

                break;
        }

    });

    return redirect()
        ->route('campagnes.index')
        ->with('success', 'Campagne créée avec succès.');
}

    


    /*

    |--------------------------------------------------------------------------
    | AUTORISATION DPA
    |--------------------------------------------------------------------------
    */

    private function dpaPeutVoirCampagne(
        CampagneRecensement $campagne
    ): bool {

        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        if (
            method_exists($user, 'isAdmin') &&
            call_user_func([$user, 'isAdmin'])
        ) {
            return true;
        }


        /*
        |--------------------------------------------------------------------------
        | AUTRES RÔLES
        |--------------------------------------------------------------------------
        */

        if (
            !method_exists($user, 'isDpa') ||
            !call_user_func([$user, 'isDpa'])
        ) {
            return true;
        }


        /*
        |--------------------------------------------------------------------------
        | RATTACHEMENT ACTIF DU DPA
        |--------------------------------------------------------------------------
        */

        $prefecture = $user->prefectureActuelle;


        if (!$prefecture) {
            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | CAMPAGNE NATIONALE
        |--------------------------------------------------------------------------
        */

        if ($campagne->portee === 'nationale') {
            return true;
        }


        /*
        |--------------------------------------------------------------------------
        | CAMPAGNE RÉGIONALE
        |--------------------------------------------------------------------------
        |
        | Une campagne régionale est visible par toutes les préfectures
        | appartenant aux régions sélectionnées.
        |
        */

        if ($campagne->portee === 'regionale') {

            return $campagne->zones()
                ->where(
                    'region_id',
                    $prefecture->region_id
                )
                ->exists();
        }


        /*
        |--------------------------------------------------------------------------
        | CAMPAGNE PRÉFECTORALE
        |--------------------------------------------------------------------------
        |
        | Une campagne préfectorale est visible uniquement par la
        | préfecture explicitement concernée.
        |
        */

        if ($campagne->portee === 'prefectorale') {

            return $campagne->zones()
                ->where(
                    'prefecture_id',
                    $prefecture->idPrefecture
                )
                ->exists();
        }


        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        CampagneRecensement $campagne
    ) {

        if (!$this->dpaPeutVoirCampagne($campagne)) {

            abort(
                403,
                'Vous n\'êtes pas autorisé à consulter cette campagne.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SYNCHRONISATION DE SÉCURITÉ
        |--------------------------------------------------------------------------
        */

        $campagne->synchroniserStatut();


        /*
        |--------------------------------------------------------------------------
        | CHARGEMENT
        |--------------------------------------------------------------------------
        */

        $campagne->load([


            'createur',

            'questionnaires',

            'zones.region',
            'zones.prefecture',
            'zones.commune',
            'zones.canton',
            'zones.village',

            'affectations.user',

            'deploiements.prefecture',

            'deploiements.recuPar',
        ]);


        return view(
            'campagnes.show',
            compact('campagne')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        CampagneRecensement $campagne
    ) {

        if (!$this->dpaPeutVoirCampagne($campagne)) {

            abort(
                403,
                'Vous n\'êtes pas autorisé à modifier cette campagne.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SYNCHRONISATION
        |--------------------------------------------------------------------------
        */

        $campagne->synchroniserStatut();


        /*
        |--------------------------------------------------------------------------
        | ARCHIVÉE
        |--------------------------------------------------------------------------
        */

        if ($campagne->statut === 'archivee') {

            return redirect()
                ->route('campagnes.index')
                ->with(
                    'error',
                    'Une campagne archivée ne peut plus être modifiée.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | TERRITOIRES
        |--------------------------------------------------------------------------
        */

        $regions = Region::with([
            'prefectures.communes.cantons.villages',
        ])
            ->orderBy('nom')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | STRUCTURES
        |--------------------------------------------------------------------------
        */

        $structures = Structure::orderBy('nom')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | QUESTIONNAIRES
        |--------------------------------------------------------------------------
        */

        $questionnaires = Questionnaire::query()
            ->where('etat', true)
            ->orderBy('nom')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | CAMPAGNE
        |--------------------------------------------------------------------------
        */

        $campagne->load([

            'zones.region',
            'zones.prefecture',
            'zones.commune',
            'zones.canton',
            'zones.village',

            'questionnaires',
        ]);


        return view(
            'campagnes.edit',
            compact(
                'campagne',
                'regions',
                'questionnaires'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        UpdateCampagneRecensementRequest $request,
        CampagneRecensement $campagne
    ) {

        /*
        |--------------------------------------------------------------------------
        | AUTORISATION
        |--------------------------------------------------------------------------
        */

        if (!$this->dpaPeutVoirCampagne($campagne)) {

            abort(
                403,
                'Vous n\'êtes pas autorisé à modifier cette campagne.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SYNCHRONISATION DU STATUT
        |--------------------------------------------------------------------------
        */

        $campagne->synchroniserStatut();


        /*
        |--------------------------------------------------------------------------
        | CAMPAGNE ARCHIVÉE
        |--------------------------------------------------------------------------
        */

        if ($campagne->statut === 'archivee') {

            return back()
                ->with(
                    'error',
                    'Une campagne archivée ne peut plus être modifiée.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | DONNÉES VALIDÉES
        |--------------------------------------------------------------------------
        */

        $validated = $request->validated();


        /*
        |--------------------------------------------------------------------------
        | TERRITOIRES
        |--------------------------------------------------------------------------
        */

        $regionIds =
            $validated['region_ids'] ?? [];

        $prefectureIds =
            $validated['prefecture_ids'] ?? [];

        $communeIds =
            $validated['commune_ids'] ?? [];

        $cantonIds =
            $validated['canton_ids'] ?? [];

        $villageIds =
            $validated['village_ids'] ?? [];


        /*
        |--------------------------------------------------------------------------
        | QUESTIONNAIRES
        |--------------------------------------------------------------------------
        */

        $questionnaireIds =
            $validated['questionnaire_ids'] ?? [];


        /*
        |--------------------------------------------------------------------------
        | TRANSACTION
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $campagne,
            $validated,
            $regionIds,
            $prefectureIds,
            $communeIds,
            $cantonIds,
            $villageIds,
            $questionnaireIds
        ) {

            /*
            |--------------------------------------------------------------------------
            | DONNÉES DE LA CAMPAGNE
            |--------------------------------------------------------------------------
            */

            $data = [

                'libelle' =>
                    $validated['libelle'],

                'description' =>
                    $validated['description'] ?? null,

                'objectifs' =>
                    $validated['objectifs'],

                'resultatsAttendus' =>
                    $validated['resultatsAttendus'] ?? null,

                'methodologie' =>
                    $validated['methodologie'] ?? null,

                'instructions' =>
                    $validated['instructions'] ?? null,

                'portee' =>
                    $validated['portee'],

                'dateDebut' =>
                    $validated['dateDebut'],

                'dateFin' =>
                    $validated['dateFin'] ?? null,
            ];


            /*
            |--------------------------------------------------------------------------
            | MISE À JOUR DE LA CAMPAGNE
            |--------------------------------------------------------------------------
            */

            $campagne->update($data);


            /*
            |--------------------------------------------------------------------------
            | SUPPRESSION DES ANCIENNES ZONES
            |--------------------------------------------------------------------------
            |
            | Le périmètre territorial est reconstruit à partir
            | de la nouvelle sélection.
            |
            */

            $campagne->zones()->delete();


            /*
            |--------------------------------------------------------------------------
            | CAMPAGNE NATIONALE
            |--------------------------------------------------------------------------
            |
            | Aucune zone territoriale particulière.
            |
            */

            if ($validated['portee'] === 'nationale') {

                // Rien à enregistrer.
            }


            /*
            |--------------------------------------------------------------------------
            | CAMPAGNE RÉGIONALE
            |--------------------------------------------------------------------------
            |
            | On enregistre les régions sélectionnées.
            |
            */

            elseif ($validated['portee'] === 'regionale') {

                /*
                |----------------------------------------------------------------------
                | RÉGIONS
                |----------------------------------------------------------------------
                */

                foreach ($regionIds as $regionId) {

                    $campagne->zones()->create([

                        'region_id' =>
                            $regionId,

                        'prefecture_id' =>
                            null,

                        'commune_id' =>
                            null,

                        'canton_id' =>
                            null,

                        'village_id' =>
                            null,

                        'statut' =>
                            'planifiee',
                    ]);
                }


                /*
                |----------------------------------------------------------------------
                | PRÉFECTURES DIRECTES
                |----------------------------------------------------------------------
                */

                foreach ($prefectureIds as $prefectureId) {

                    $prefecture = Prefecture::find(
                        $prefectureId
                    );

                    $campagne->zones()->create([

                        'region_id' =>
                            $prefecture?->region_id,

                        'prefecture_id' =>
                            $prefectureId,

                        'commune_id' =>
                            null,

                        'canton_id' =>
                            null,

                        'village_id' =>
                            null,

                        'statut' =>
                            'planifiee',
                    ]);
                }


                /*
                |----------------------------------------------------------------------
                | COMMUNES DIRECTES
                |----------------------------------------------------------------------
                */

                foreach ($communeIds as $communeId) {

                    $commune =
                        \App\Models\Commune::find(
                            $communeId
                        );

                    $campagne->zones()->create([

                        'region_id' =>
                            $commune?->prefecture?->region_id,

                        'prefecture_id' =>
                            $commune?->prefecture_id,

                        'commune_id' =>
                            $communeId,

                        'canton_id' =>
                            null,

                        'village_id' =>
                            null,

                        'statut' =>
                            'planifiee',
                    ]);
                }


                /*
                |----------------------------------------------------------------------
                | CANTONS DIRECTS
                |----------------------------------------------------------------------
                */

                foreach ($cantonIds as $cantonId) {

                    $canton =
                        \App\Models\Canton::with(
                            'commune.prefecture'
                        )->find($cantonId);

                    $campagne->zones()->create([

                        'region_id' =>
                            $canton?->commune?->prefecture?->region_id,

                        'prefecture_id' =>
                            $canton?->commune?->prefecture_id,

                        'commune_id' =>
                            $canton?->commune_id,

                        'canton_id' =>
                            $cantonId,

                        'village_id' =>
                            null,

                        'statut' =>
                            'planifiee',
                    ]);
                }


                /*
                |----------------------------------------------------------------------
                | VILLAGES DIRECTS
                |----------------------------------------------------------------------
                */

                foreach ($villageIds as $villageId) {

                    $village =
                        \App\Models\Village::with(
                            'canton.commune.prefecture'
                        )->find($villageId);

                    $campagne->zones()->create([

                        'region_id' =>
                            $village?->canton?->commune?->prefecture?->region_id,

                        'prefecture_id' =>
                            $village?->canton?->commune?->prefecture_id,

                        'commune_id' =>
                            $village?->canton?->commune_id,

                        'canton_id' =>
                            $village?->canton_id,

                        'village_id' =>
                            $villageId,

                        'statut' =>
                            'planifiee',
                    ]);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | CAMPAGNE PRÉFECTORALE
            |--------------------------------------------------------------------------
            */

            elseif ($validated['portee'] === 'prefectorale') {

                /*
                |----------------------------------------------------------------------
                | PRÉFECTURES
                |----------------------------------------------------------------------
                */

                foreach ($prefectureIds as $prefectureId) {

                    $prefecture =
                        Prefecture::find(
                            $prefectureId
                        );

                    $campagne->zones()->create([

                        'region_id' =>
                            $prefecture?->region_id,

                        'prefecture_id' =>
                            $prefectureId,

                        'commune_id' =>
                            null,

                        'canton_id' =>
                            null,

                        'village_id' =>
                            null,

                        'statut' =>
                            'planifiee',
                    ]);
                }


                /*
                |----------------------------------------------------------------------
                | COMMUNES
                |----------------------------------------------------------------------
                */

                foreach ($communeIds as $communeId) {

                    $commune =
                        \App\Models\Commune::find(
                            $communeId
                        );

                    $campagne->zones()->create([

                        'region_id' =>
                            $commune?->prefecture?->region_id,

                        'prefecture_id' =>
                            $commune?->prefecture_id,

                        'commune_id' =>
                            $communeId,

                        'canton_id' =>
                            null,

                        'village_id' =>
                            null,

                        'statut' =>
                            'planifiee',
                    ]);
                }


                /*
                |----------------------------------------------------------------------
                | CANTONS
                |----------------------------------------------------------------------
                */

                foreach ($cantonIds as $cantonId) {

                    $canton =
                        \App\Models\Canton::with(
                            'commune.prefecture'
                        )->find($cantonId);

                    $campagne->zones()->create([

                        'region_id' =>
                            $canton?->commune?->prefecture?->region_id,

                        'prefecture_id' =>
                            $canton?->commune?->prefecture_id,

                        'commune_id' =>
                            $canton?->commune_id,

                        'canton_id' =>
                            $cantonId,

                        'village_id' =>
                            null,

                        'statut' =>
                            'planifiee',
                    ]);
                }


                /*
                |----------------------------------------------------------------------
                | VILLAGES
                |----------------------------------------------------------------------
                */

                foreach ($villageIds as $villageId) {

                    $village =
                        \App\Models\Village::with(
                            'canton.commune.prefecture'
                        )->find($villageId);

                    $campagne->zones()->create([

                        'region_id' =>
                            $village?->canton?->commune?->prefecture?->region_id,

                        'prefecture_id' =>
                            $village?->canton?->commune?->prefecture_id,

                        'commune_id' =>
                            $village?->canton?->commune_id,

                        'canton_id' =>
                            $village?->canton_id,

                        'village_id' =>
                            $villageId,

                        'statut' =>
                            'planifiee',
                    ]);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | QUESTIONNAIRES
            |--------------------------------------------------------------------------
            */

            $campagne->questionnaires()->sync(
                $questionnaireIds
            );
        });


        /*
        |--------------------------------------------------------------------------
        | RAFRAÎCHISSEMENT
        |--------------------------------------------------------------------------
        */

        $campagne->refresh();


        /*
        |--------------------------------------------------------------------------
        | SYNCHRONISATION DU STATUT
        |--------------------------------------------------------------------------
        */

        $campagne->synchroniserStatut();


        /*
        |--------------------------------------------------------------------------
        | SYNCHRONISATION DES DÉPLOIEMENTS
        |--------------------------------------------------------------------------
        |
        | Important :
        | Si Centrale vient d'être ajoutée, les préfectures de Centrale
        | sont maintenant ajoutées aux déploiements existants.
        |
        */

        $this->synchroniserDeploiements($campagne);


        /*
        |--------------------------------------------------------------------------
        | REDIRECTION
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('campagnes.index')
            ->with(
                'success',
                'Campagne modifiée avec succès.'
            );
    }
    


        public function deploy(
            CampagneRecensement $campagne
    ) {

        /*
        |--------------------------------------------------------------------------
        | SYNCHRONISATION
        |--------------------------------------------------------------------------
        */

        $campagne->synchroniserStatut();


        /*
        |--------------------------------------------------------------------------
        | CONTRÔLES
        |--------------------------------------------------------------------------
        */

        if ($campagne->statut === 'archivee') {

            return back()->with(
                'error',
                'Une campagne archivée ne peut pas être déployée.'
            );
        }


        if ($campagne->statut === 'cloturee') {

            return back()->with(
                'error',
                'Une campagne clôturée ne peut pas être déployée.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PRÉFECTURES CONCERNÉES
        |--------------------------------------------------------------------------
        */

        $prefectureIds = collect();


        /*
        |--------------------------------------------------------------------------
        | NATIONALE
        |--------------------------------------------------------------------------
        */

        if ($campagne->portee === 'nationale') {

            $prefectureIds =
                Prefecture::query()
                    ->pluck('idPrefecture');
        }


        /*
        |--------------------------------------------------------------------------
        | RÉGIONALE
        |--------------------------------------------------------------------------
        |
        | On prend en compte les régions sélectionnées ET les niveaux plus
        | fins (préfecture, commune, canton, village) que l'accordéon peut
        | avoir enregistrés directement, sans passer par une région.
        |
        */

        elseif ($campagne->portee === 'regionale') {

            $regionIds = $campagne->zones()
                ->whereNotNull('region_id')
                ->pluck('region_id')
                ->unique();

            $zonesChargees = $campagne->zones()
                ->with([
                    'prefecture',
                    'commune.prefecture',
                    'canton.commune.prefecture',
                    'village.canton.commune.prefecture',
                ])
                ->get();

            $prefectureIdsDirectes = $zonesChargees
                ->map(fn ($zone) => $zone->prefecture_rattachee?->idPrefecture)
                ->filter()
                ->unique();

            if ($regionIds->isEmpty() && $prefectureIdsDirectes->isEmpty()) {

                return back()->with(
                    'error',
                    'Aucune région ni préfecture n’est associée à cette campagne.'
                );
            }

            $prefectureIdsDepuisRegions = $regionIds->isNotEmpty()
                ? Prefecture::query()
                    ->whereIn('region_id', $regionIds)
                    ->pluck('idPrefecture')
                : collect();

            $prefectureIds = $prefectureIdsDepuisRegions
                ->merge($prefectureIdsDirectes)
                ->unique();
        }


        /*
        |--------------------------------------------------------------------------
        | PRÉFECTORALE
        |--------------------------------------------------------------------------
        |
        | On remonte la hiérarchie territoriale pour chaque zone : une zone
        | enregistrée au niveau commune, canton ou village doit tout de même
        | notifier sa préfecture parente.
        |
        */

        elseif ($campagne->portee === 'prefectorale') {

            $prefectureIds = $campagne->zones()
                ->with([
                    'prefecture',
                    'commune.prefecture',
                    'canton.commune.prefecture',
                    'village.canton.commune.prefecture',
                ])
                ->get()
                ->map(fn ($zone) => $zone->prefecture_rattachee?->idPrefecture)
                ->filter()
                ->unique();
        }


        /*
        |--------------------------------------------------------------------------
        | PORTÉE INVALIDE
        |--------------------------------------------------------------------------
        */

        else {

            return back()->with(
                'error',
                'La portée de cette campagne est invalide.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | NETTOYAGE
        |--------------------------------------------------------------------------
        */

        $prefectureIds = $prefectureIds
            ->filter()
            ->unique()
            ->values();


        if ($prefectureIds->isEmpty()) {

            return back()->with(
                'error',
                'Aucune préfecture n’est concernée par cette campagne.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CRÉATION DES DÉPLOIEMENTS
        |--------------------------------------------------------------------------
        */

        $nombreNouveauxDeploiements = 0;


        DB::transaction(
            function () use (
                $campagne,
                $prefectureIds,
                &$nombreNouveauxDeploiements
            ) {

                foreach ($prefectureIds as $prefectureId) {

                    $deploiement =
                        CampagneDeploiement::firstOrCreate(

                            [
                                'campagne_id' =>
                                    $campagne->idCampagne,

                                'prefecture_id' =>
                                    $prefectureId,
                            ],

                            [
                                'statut' =>
                                    'notifiee',
                            ]
                        );


                    if (
                        $deploiement->wasRecentlyCreated
                    ) {

                        $nombreNouveauxDeploiements++;
                    }
                }
            }
        );


        if ($nombreNouveauxDeploiements === 0) {

            return back()->with(
                'info',
                'Cette campagne a déjà été déployée auprès des préfectures concernées.'
            );
        }


        return back()->with(
            'success',
            $nombreNouveauxDeploiements .
            ' préfecture(s) ont été notifiées du déploiement de la campagne.'
        );
    }



    private function synchroniserDeploiements(
        CampagneRecensement $campagne
        ): void {

        /*
        |--------------------------------------------------------------------------
        | PRÉFECTURES CONCERNÉES
        |--------------------------------------------------------------------------
        */

        $prefectureIds = collect();


        /*
        |--------------------------------------------------------------------------
        | CAMPAGNE NATIONALE
        |--------------------------------------------------------------------------
        */

        if ($campagne->portee === 'nationale') {

            $prefectureIds =
                Prefecture::query()
                    ->pluck('idPrefecture');
        }


        /*
        |--------------------------------------------------------------------------
        | CAMPAGNE RÉGIONALE
        |--------------------------------------------------------------------------
        */

        elseif ($campagne->portee === 'regionale') {

            /*
            |----------------------------------------------------------------------
            | RÉGIONS
            |----------------------------------------------------------------------
            */

            $regionIds = $campagne->zones()
                ->whereNotNull('region_id')
                ->pluck('region_id')
                ->unique();


            /*
            |----------------------------------------------------------------------
            | PRÉFECTURES DES RÉGIONS
            |----------------------------------------------------------------------
            |
            | Exemple :
            |
            | Centrale → Mô
            | Savanes → Cinkassé, Tône, Kpendjal...
            |
            */

            $prefectureIdsDepuisRegions =
                Prefecture::query()
                    ->whereIn(
                        'region_id',
                        $regionIds
                    )
                    ->pluck('idPrefecture');


            /*
            |----------------------------------------------------------------------
            | PRÉFECTURES SÉLECTIONNÉES DIRECTEMENT
            |----------------------------------------------------------------------
            */

            $prefectureIdsDirectes =
                $campagne->zones()
                    ->whereNotNull('prefecture_id')
                    ->pluck('prefecture_id');


            /*
            |----------------------------------------------------------------------
            | FUSION
            |----------------------------------------------------------------------
            */

            $prefectureIds =
                $prefectureIdsDepuisRegions
                    ->merge($prefectureIdsDirectes)
                    ->filter()
                    ->unique()
                    ->values();
        }


        /*
        |--------------------------------------------------------------------------
        | CAMPAGNE PRÉFECTORALE
        |--------------------------------------------------------------------------
        */

        elseif ($campagne->portee === 'prefectorale') {

            $prefectureIds =
                $campagne->zones()
                    ->with([
                        'prefecture',
                        'commune.prefecture',
                        'canton.commune.prefecture',
                        'village.canton.commune.prefecture',
                    ])
                    ->get()
                    ->map(
                        fn ($zone) =>
                            $zone->prefecture_rattachee?->idPrefecture
                    )
                    ->filter()
                    ->unique()
                    ->values();
        }


        /*
        |--------------------------------------------------------------------------
        | PORTÉE INVALIDE
        |--------------------------------------------------------------------------
        */

        else {

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | AUCUNE PRÉFECTURE
        |--------------------------------------------------------------------------
        */

        if ($prefectureIds->isEmpty()) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | CRÉATION DES DÉPLOIEMENTS MANQUANTS
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $campagne,
            $prefectureIds
        ) {

            foreach ($prefectureIds as $prefectureId) {

                CampagneDeploiement::firstOrCreate(

                    [
                        'campagne_id' =>
                            $campagne->idCampagne,

                        'prefecture_id' =>
                            $prefectureId,
                    ],

                    [
                        'statut' =>
                            'notifiee',
                    ]
                );
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | ARCHIVER
    |--------------------------------------------------------------------------
    */




    public function archive(
        CampagneRecensement $campagne
    ) {

        $campagne->synchroniserStatut();


        /*
        | Une campagne doit être clôturée automatiquement
        | lorsque sa date de fin est atteinte.
        |
        | L'archivage reste une décision administrative.
        */

        if ($campagne->statut === 'archivee') {

            return back()
                ->with(
                    'info',
                    'Cette campagne est déjà archivée.'
                );
        }


        if ($campagne->statut !== 'cloturee') {

            return back()
                ->with(
                    'error',
                    'Seule une campagne clôturée peut être archivée.'
                );
        }


        $campagne->update([
            'statut' => 'archivee',
        ]);


        return back()
            ->with(
                'success',
                'Campagne archivée avec succès.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        CampagneRecensement $campagne
    ) {

        if (!$this->dpaPeutVoirCampagne($campagne)) {

            abort(
                403,
                'Vous n\'êtes pas autorisé à supprimer cette campagne.'
            );
        }


        $campagne->synchroniserStatut();


        /*
        |--------------------------------------------------------------------------
        | UNE CAMPAGNE COMMENCÉE NE SE SUPPRIME PLUS
        |--------------------------------------------------------------------------
        */

        if (in_array(
            $campagne->statut,
            [
                'active',
                'cloturee',
                'archivee',
            ]
        )) {

            return back()
                ->with(
                    'error',
                    'Une campagne ayant commencé ne peut pas être supprimée.'
                );
        }


        $campagne->delete();


        return redirect()
            ->route('campagnes.index')
            ->with(
                'success',
                'Campagne supprimée avec succès.'
            );
    }
}