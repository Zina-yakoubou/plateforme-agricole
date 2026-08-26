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


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    // public function create()
    // {
    //     $regions = Region::with([
    //         'prefectures.communes.cantons.villages',
    //     ])
    //         ->orderBy('nom')
    //         ->get();


    //     $structures = Structure::orderBy('nom')
    //         ->get();


    //     /*
    //     |----------------------------------------------------------------------
    //     | QUESTIONNAIRES DISPONIBLES
    //     |----------------------------------------------------------------------
    //     */

    //     $questionnaires = Questionnaire::query()
    //         ->where('etat', true)
    //         ->orderBy('nom')
    //         ->get();


    //     return view(
    //         'campagnes.create',
    //         compact(
    //             'regions',
    //             'structures',
    //             'questionnaires'
    //         )
    //     );
    // }


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


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    // public function store(
    //     StoreCampagneRecensementRequest $request
    // ) {
    //     $data = $request->validated();


    //     /*
    //     |--------------------------------------------------------------------------
    //     | ZONES
    //     |--------------------------------------------------------------------------
    //     */

    //     $zones = $data['zones'] ?? [];

    //     unset($data['zones']);


    //     /*
    //     |--------------------------------------------------------------------------
    //     | QUESTIONNAIRES
    //     |--------------------------------------------------------------------------
    //     */

    //     $questionnaireIds =
    //         $data['questionnaire_ids'] ?? [];

    //     unset($data['questionnaire_ids']);


    //     /*
    //     |--------------------------------------------------------------------------
    //     | CODE
    //     |--------------------------------------------------------------------------
    //     */

    //     $data['codeCampagne'] =
    //         $this->genererCodeCampagne();


    //     /*
    //     |--------------------------------------------------------------------------
    //     | STATUT INITIAL
    //     |--------------------------------------------------------------------------
    //     |
    //     | Toujours planifiée lors de la création.
    //     |
    //     | La synchronisation déterminera ensuite si la campagne doit
    //     | immédiatement devenir active.
    //     |
    //     */

    //     $data['statut'] = 'planifiee';


    //     /*
    //     |--------------------------------------------------------------------------
    //     | CRÉATEUR
    //     |--------------------------------------------------------------------------
    //     */

    //     $data['created_by'] = auth()->id();


    //     /*
    //     |--------------------------------------------------------------------------
    //     | TRANSACTION
    //     |--------------------------------------------------------------------------
    //     */

    //     $campagne = DB::transaction(
    //         function () use (
    //             $data,
    //             $zones,
    //             $questionnaireIds
    //         ) {

    //             /*
    //             |------------------------------------------------------------------
    //             | CAMPAGNE
    //             |------------------------------------------------------------------
    //             */

    //             $campagne =
    //                 CampagneRecensement::create($data);


    //             /*
    //             |------------------------------------------------------------------
    //             | ZONES
    //             |------------------------------------------------------------------
    //             */

    //             foreach ($zones as $zone) {

    //                 $campagne->zones()->create([

    //                     'region_id' =>
    //                         $zone['region_id'] ?? null,

    //                     'prefecture_id' =>
    //                         $zone['prefecture_id'],

    //                     'commune_id' =>
    //                         $zone['commune_id'] ?? null,

    //                     'canton_id' =>
    //                         $zone['canton_id'] ?? null,

    //                     'village_id' =>
    //                         $zone['village_id'] ?? null,

    //                     'dateDebut' =>
    //                         $zone['dateDebut'] ?? null,

    //                     'dateFin' =>
    //                         $zone['dateFin'] ?? null,

    //                     'statut' =>
    //                         'planifiee',
    //                 ]);
    //             }


    //             /*
    //             |------------------------------------------------------------------
    //             | QUESTIONNAIRES
    //             |------------------------------------------------------------------
    //             */

    //             if (!empty($questionnaireIds)) {

    //                 $campagne->questionnaires()->sync(
    //                     $questionnaireIds
    //                 );
    //             }


    //             return $campagne;
    //         }
    //     );


    //     /*
    //     |--------------------------------------------------------------------------
    //     | SYNCHRONISATION IMMÉDIATE
    //     |--------------------------------------------------------------------------
    //     |
    //     | Exemple :
    //     |
    //     | dateDebut = aujourd'hui 08:00
    //     | création = aujourd'hui 10:00
    //     |
    //     | La campagne ne doit pas rester planifiée.
    //     |
    //     */

    //     $campagne->synchroniserStatut();


    //     return redirect()
    //         ->route('campagnes.index')
    //         ->with(
    //             'success',
    //             'Campagne créée avec succès.'
    //         );
    // }



    //   
    
        public function store(StoreCampagneRecensementRequest $request)
    {
        $data = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | PORTÉE / ZONES
        |--------------------------------------------------------------------------
        */

        $portee = $data['portee'];

        $regionIds = $data['region_ids'] ?? [];

        $prefectureIds = $data['prefecture_ids'] ?? [];

        unset(
            $data['region_ids'],
            $data['prefecture_ids']
        );


        /*
        |--------------------------------------------------------------------------
        | QUESTIONNAIRES
        |--------------------------------------------------------------------------
        */

        $questionnaireIds = $data['questionnaire_ids'] ?? [];

        unset($data['questionnaire_ids']);


        /*
        |--------------------------------------------------------------------------
        | CODE CAMPAGNE
        |--------------------------------------------------------------------------
        */

        $data['codeCampagne'] = $this->genererCodeCampagne();


        /*
        |--------------------------------------------------------------------------
        | STATUT INITIAL
        |--------------------------------------------------------------------------
        */

        $data['statut'] = 'planifiee';


        /*
        |--------------------------------------------------------------------------
        | CRÉATEUR
        |--------------------------------------------------------------------------
        */

        $data['created_by'] = Auth::user()->getAuthIdentifier();


        /*
        |--------------------------------------------------------------------------
        | CRÉATION
        |--------------------------------------------------------------------------
        */

        $campagne = DB::transaction(function () use (
            $data,
            $questionnaireIds,
            $portee,
            $regionIds,
            $prefectureIds
        ) {

            /*
            |--------------------------------------------------------------------------
            | CAMPAGNE
            |--------------------------------------------------------------------------
            */

            $campagne = CampagneRecensement::create($data);


            /*
            |--------------------------------------------------------------------------
            | QUESTIONNAIRES
            |--------------------------------------------------------------------------
            */

            if (!empty($questionnaireIds)) {

                $campagne->questionnaires()->sync(
                    $questionnaireIds
                );
            }


            /*
            |--------------------------------------------------------------------------
            | ZONES — CAMPAGNE RÉGIONALE
            |--------------------------------------------------------------------------
            |
            | On enregistre uniquement la région.
            |
            | Lors du déploiement, deploy() retrouvera automatiquement
            | toutes les préfectures appartenant à cette région.
            |
            */

            if ($portee === 'regionale') {

                foreach ($regionIds as $regionId) {

                    $campagne->zones()->create([
                        'region_id'     => $regionId,
                        'prefecture_id' => null,
                        'commune_id'    => null,
                        'canton_id'     => null,
                        'village_id'    => null,
                    ]);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | ZONES — CAMPAGNE PRÉFECTORALE
            |--------------------------------------------------------------------------
            |
            | L'utilisateur choisit directement les préfectures.
            |
            | La région est récupérée automatiquement depuis la préfecture.
            |
            */

            if ($portee === 'prefectorale') {

                foreach ($prefectureIds as $prefectureId) {

                    $regionId = Prefecture::query()
                        ->where(
                            'idPrefecture',
                            $prefectureId
                        )
                        ->value('region_id');


                    $campagne->zones()->create([
                        'region_id'     => $regionId,
                        'prefecture_id' => $prefectureId,
                        'commune_id'    => null,
                        'canton_id'     => null,
                        'village_id'    => null,
                    ]);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | CAMPAGNE NATIONALE
            |--------------------------------------------------------------------------
            |
            | Aucune zone spécifique n'est enregistrée.
            | deploy() récupérera directement toutes les préfectures.
            |
            */

            return $campagne;
        });


        /*
        |--------------------------------------------------------------------------
        | SYNCHRONISATION DU STATUT
        |--------------------------------------------------------------------------
        */

        $campagne->synchroniserStatut();


        /*
        |--------------------------------------------------------------------------
        | REDIRECTION
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('campagnes.index')
            ->with(
                'success',
                'Campagne créée avec succès.'
            );
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

        if (method_exists($user, 'isAdmin') && call_user_func([$user, 'isAdmin'])) {

            return true;
        }


        /*
        |--------------------------------------------------------------------------
        | AUTRES RÔLES
        |--------------------------------------------------------------------------
        */

        if (!method_exists($user, 'isDpa') || !call_user_func([$user, 'isDpa'])) {

            return true;
        }


        /*
        |--------------------------------------------------------------------------
        | RATTACHEMENT ACTIF
        |--------------------------------------------------------------------------
        */

        $prefecture =
            $user->prefectureActuelle;


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
        | CAMPAGNE TERRITORIALE
        |--------------------------------------------------------------------------
        |
        | Une campagne régionale ou préfectorale devient visible
        | si la préfecture du DPA est dans son périmètre.
        |
        */

        return $campagne->zones()
            ->where(
                'prefecture_id',
                $prefecture->idPrefecture
            )
            ->exists();
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

        if (!$this->dpaPeutVoirCampagne($campagne)) {

            abort(
                403,
                'Vous n\'êtes pas autorisé à modifier cette campagne.'
            );
        }


        $campagne->synchroniserStatut();


        /*
        |--------------------------------------------------------------------------
        | ARCHIVÉE
        |--------------------------------------------------------------------------
        */

        if ($campagne->statut === 'archivee') {

            return back()
                ->with(
                    'error',
                    'Une campagne archivée ne peut plus être modifiée.'
                );
        }


        $data = $request->validated();


        /*
        |--------------------------------------------------------------------------
        | ZONES
        |--------------------------------------------------------------------------
        */

        $zones = $data['zones'] ?? [];

        unset($data['zones']);


        /*
        |--------------------------------------------------------------------------
        | QUESTIONNAIRES
        |--------------------------------------------------------------------------
        */

        $questionnaireIds =
            $data['questionnaire_ids'] ?? [];

        unset($data['questionnaire_ids']);


        DB::transaction(
            function () use (
                $campagne,
                $data,
                $zones,
                $questionnaireIds
            ) {

                /*
                |------------------------------------------------------------------
                | CHAMPS NON MODIFIABLES
                |------------------------------------------------------------------
                */

                unset(
                    $data['codeCampagne'],
                    $data['created_by'],
                    $data['statut']
                );


                /*
                |------------------------------------------------------------------
                | CAMPAGNE
                |------------------------------------------------------------------
                */

                $campagne->update($data);


                /*
                |------------------------------------------------------------------
                | ZONES
                |------------------------------------------------------------------
                */

                $campagne->zones()->delete();


                foreach ($zones as $zone) {

                    $campagne->zones()->create([

                        'region_id' =>
                            $zone['region_id'] ?? null,

                        'prefecture_id' =>
                            $zone['prefecture_id'],

                        'commune_id' =>
                            $zone['commune_id'] ?? null,

                        'canton_id' =>
                            $zone['canton_id'] ?? null,

                        'village_id' =>
                            $zone['village_id'] ?? null,

                        'dateDebut' =>
                            $zone['dateDebut'] ?? null,

                        'dateFin' =>
                            $zone['dateFin'] ?? null,

                        'statut' =>
                            'planifiee',
                    ]);
                }


                /*
                |------------------------------------------------------------------
                | QUESTIONNAIRES
                |------------------------------------------------------------------
                */

                $campagne->questionnaires()->sync(
                    $questionnaireIds
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | SYNCHRONISATION APRÈS MODIFICATION
        |--------------------------------------------------------------------------
        */

        $campagne->refresh();

        $campagne->synchroniserStatut();


        return redirect()
            ->route('campagnes.index')
            ->with(
                'success',
                'Campagne modifiée avec succès.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DÉPLOYER
    |--------------------------------------------------------------------------
    */

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
        */

        elseif ($campagne->portee === 'regionale') {

            $regionIds = $campagne->zones()
                ->whereNotNull('region_id')
                ->pluck('region_id')
                ->unique();


            if ($regionIds->isEmpty()) {

                return back()->with(
                    'error',
                    'Aucune région n’est associée à cette campagne.'
                );
            }


            $prefectureIds =
                Prefecture::query()
                    ->whereIn(
                        'region_id',
                        $regionIds
                    )
                    ->pluck('idPrefecture');
        }


        /*
        |--------------------------------------------------------------------------
        | PRÉFECTORALE
        |--------------------------------------------------------------------------
        */

        elseif ($campagne->portee === 'prefectorale') {

            $prefectureIds =
                $campagne->zones()
                    ->whereNotNull('prefecture_id')
                    ->pluck('prefecture_id')
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