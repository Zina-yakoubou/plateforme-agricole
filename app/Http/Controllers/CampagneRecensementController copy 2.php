<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCampagneRecensementRequest;
use App\Http\Requests\UpdateCampagneRecensementRequest;
use App\Models\CampagneDeploiement;
use App\Models\CampagneRecensement;
use App\Models\Prefecture;
use App\Models\Region;
use App\Models\Structure;
use Illuminate\Http\Request;
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
        $user = auth()->user();

        $search = $request->search;

        $query = CampagneRecensement::with([
            'structure',
            'createur',
            'zones.region',
            'zones.prefecture',
            'deploiements.prefecture',
        ]);


        /*
        |--------------------------------------------------------------------------
        | FILTRAGE TERRITORIAL DU DPA
        |--------------------------------------------------------------------------
        */

        if ($user->isDpa()) {

            $prefecture = $user->prefectureActuelle;

            /*
             * Si le DPA n'a aucun rattachement actif,
             * il ne doit voir aucune campagne territoriale.
             */

            if (!$prefecture) {

                $query->whereRaw('1 = 0');

            } else {

                $regionId = $prefecture->region_id;

                $query->where(function ($q) use (
                    $prefecture,
                    $regionId
                ) {

                    /*
                     * Campagne nationale
                     */

                    $q->where(
                        'portee',
                        'nationale'
                    );


                    /*
                     * Campagne régionale
                     */

                    $q->orWhere(function ($q) use ($regionId) {

                        $q->where(
                            'portee',
                            'regionale'
                        )
                        ->whereHas('zones', function ($zone) use (
                            $regionId
                        ) {

                            $zone->where(
                                'zone_type',
                                'region'
                            )
                            ->where(
                                'zone_id',
                                $regionId
                            );
                        });

                    });


                    /*
                     * Campagne préfectorale
                     */

                    $q->orWhere(function ($q) use (
                        $prefecture
                    ) {

                        $q->where(
                            'portee',
                            'prefectorale'
                        )
                        ->whereHas('zones', function ($zone) use (
                            $prefecture
                        ) {

                            $zone->where(
                                'zone_type',
                                'prefecture'
                            )
                            ->where(
                                'zone_id',
                                $prefecture->idPrefecture
                            );
                        });

                    });

                });
            }
        }


        /*
        |--------------------------------------------------------------------------
        | RECHERCHE
        |--------------------------------------------------------------------------
        */

        $query->when($search, function ($query) use ($search) {

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

        });


        /*
        |--------------------------------------------------------------------------
        | RÉSULTAT
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

    public function create()
    {
        $regions = Region::with('prefectures')
            ->orderBy('nom')
            ->get();

        $structures = Structure::orderBy('nom')
            ->get();

        return view(
            'campagnes.create',
            compact(
                'regions',
                'structures'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GÉNÉRATION DU CODE CAMPAGNE
    |--------------------------------------------------------------------------
    */

    private function genererCodeCampagne(): string
    {
        $annee = now()->format('Y');

        $dernierNumero = CampagneRecensement::where(
            'codeCampagne',
            'like',
            "CAM-{$annee}-%"
        )
            ->get()
            ->map(function ($campagne) {

                return (int) str_replace(
                    "CAM-" . now()->format('Y') . "-",
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

    public function store(
        StoreCampagneRecensementRequest $request
    ) {
        $data = $request->validated();

        $zones = $data['zones'] ?? [];

        unset($data['zones']);


        /*
        |--------------------------------------------------------------------------
        | CODE
        |--------------------------------------------------------------------------
        */

        $data['codeCampagne'] =
            $this->genererCodeCampagne();


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

        $data['created_by'] = auth()->id();


        /*
        |--------------------------------------------------------------------------
        | CRÉATION TRANSACTIONNELLE
        |--------------------------------------------------------------------------
        */

        $campagne = DB::transaction(function () use (
            $data,
            $zones
        ) {

            $campagne = CampagneRecensement::create(
                $data
            );


            foreach ($zones as $zone) {

                $campagne->zones()->create([
                    'zone_type' => $zone['zone_type'],
                    'zone_id'   => $zone['zone_id'],
                    'statut'    => 'planifiee',
                ]);
            }


            return $campagne;
        });


        return redirect()
            ->route('campagnes.index')
            ->with(
                'success',
                'Campagne créée avec succès.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | VÉRIFIER L'ACCÈS DU DPA À UNE CAMPAGNE
    |--------------------------------------------------------------------------
    |
    | Cette méthode est essentielle.
    |
    | Elle protège les routes show/edit/update.
    |
    */

    private function dpaPeutVoirCampagne(
        CampagneRecensement $campagne
    ): bool {

        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        if ($user->isAdmin()) {
            return true;
        }


        /*
        |--------------------------------------------------------------------------
        | UTILISATEUR NON DPA
        |--------------------------------------------------------------------------
        |
        | On laisse les autres rôles suivre leur propre logique.
        |
        */

        if (!$user->isDpa()) {
            return true;
        }


        /*
        |--------------------------------------------------------------------------
        | RATTACHEMENT ACTIF
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
        */

        if ($campagne->portee === 'regionale') {

            return $campagne->zones()
                ->where('zone_type', 'region')
                ->where(
                    'zone_id',
                    $prefecture->region_id
                )
                ->exists();
        }


        /*
        |--------------------------------------------------------------------------
        | CAMPAGNE PRÉFECTORALE
        |--------------------------------------------------------------------------
        */

        if ($campagne->portee === 'prefectorale') {

            return $campagne->zones()
                ->where('zone_type', 'prefecture')
                ->where(
                    'zone_id',
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

        /*
        |--------------------------------------------------------------------------
        | AUTORISATION TERRITORIALE
        |--------------------------------------------------------------------------
        */

        if (!$this->dpaPeutVoirCampagne($campagne)) {

            abort(
                403,
                'Vous n\'êtes pas autorisé à consulter cette campagne.'
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
        | CHARGEMENT
        |--------------------------------------------------------------------------
        */

        $campagne->load([
            'structure',
            'createur',

            'zones.region',
            'zones.prefecture',

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


        $campagne->synchroniserStatut();


        /*
        |--------------------------------------------------------------------------
        | CAMPAGNE ARCHIVÉE
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


        $regions = Region::with('prefectures')
            ->orderBy('nom')
            ->get();


        $structures = Structure::orderBy('nom')
            ->get();


        $campagne->load([
            'zones.region',
            'zones.prefecture',
        ]);


        return view(
            'campagnes.edit',
            compact(
                'campagne',
                'regions',
                'structures'
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


        DB::transaction(function () use (
            $campagne,
            $data,
            $zones
        ) {

            /*
            |--------------------------------------------------------------------------
            | CHAMPS NON MODIFIABLES
            |--------------------------------------------------------------------------
            */

            unset(
                $data['codeCampagne'],
                $data['created_by'],
                $data['statut']
            );


            /*
            |--------------------------------------------------------------------------
            | CAMPAGNE
            |--------------------------------------------------------------------------
            */

            $campagne->update($data);


            /*
            |--------------------------------------------------------------------------
            | ZONES
            |--------------------------------------------------------------------------
            */

            $campagne->zones()->delete();


            foreach ($zones as $zone) {

                $campagne->zones()->create([
                    'zone_type' => $zone['zone_type'],
                    'zone_id'   => $zone['zone_id'],
                    'statut'    => 'planifiee',
                ]);
            }
        });


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

        $campagne->synchroniserStatut();


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

            $prefectureIds = Prefecture::query()
                ->pluck('idPrefecture');
        }


        /*
        |--------------------------------------------------------------------------
        | RÉGIONALE
        |--------------------------------------------------------------------------
        */

        elseif ($campagne->portee === 'regionale') {

            $regionIds = $campagne->zones()
                ->where('zone_type', 'region')
                ->pluck('zone_id');


            if ($regionIds->isEmpty()) {

                return back()->with(
                    'error',
                    'Aucune région n’est associée à cette campagne.'
                );
            }


            $prefectureIds = Prefecture::query()
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

            $prefectureIds = $campagne->zones()
                ->where('zone_type', 'prefecture')
                ->pluck('zone_id');
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


        DB::transaction(function () use (
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


                if ($deploiement->wasRecentlyCreated) {

                    $nombreNouveauxDeploiements++;
                }
            }
        });


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

        /*
        |--------------------------------------------------------------------------
        | AUTORISATION
        |--------------------------------------------------------------------------
        */

        if (!$this->dpaPeutVoirCampagne($campagne)) {

            abort(
                403,
                'Vous n\'êtes pas autorisé à supprimer cette campagne.'
            );
        }


        $campagne->synchroniserStatut();


        if (in_array($campagne->statut, [
            'active',
            'cloturee',
            'archivee',
        ])) {

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