<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Controllers
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\RegionController;
use App\Http\Controllers\PrefectureController;
use App\Http\Controllers\CommuneController;
use App\Http\Controllers\CantonController;
use App\Http\Controllers\VillageController;

use App\Http\Controllers\CampagneRecensementController;
use App\Http\Controllers\CampagnePlanificationController;
use App\Http\Controllers\DpaCampagneController;

use App\Http\Controllers\AffectationController;

use App\Http\Controllers\MaisonController;
use App\Http\Controllers\MenageController;

use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\SuperviseurController;
use App\Http\Controllers\RecensementController;

use App\Http\Controllers\Dpa\PlanificationPrefectoraleController;
use App\Http\Controllers\Dpa\EquipeController;
use App\Http\Controllers\Dpa\DpaAgentController;


/*
|--------------------------------------------------------------------------
| ACCUEIL
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| ROUTES AUTHENTIFIÉES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | TABLEAU DE BORD
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | PROFIL
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');


    /*
    |--------------------------------------------------------------------------
    | STATISTIQUES
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/statistiques',
        [StatistiqueController::class, 'index']
    )->name('statistiques.index');


    /*
    |--------------------------------------------------------------------------
    | UTILISATEURS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/users',
        [UserController::class, 'index']
    )->name('users.index');

    Route::get(
        '/users/create',
        [UserController::class, 'create']
    )->name('users.create');

    Route::post(
        '/users',
        [UserController::class, 'store']
    )->name('users.store');

    Route::get(
        '/users/{user}',
        [UserController::class, 'show']
    )->name('users.show');

    Route::get(
        '/users/{user}/edit',
        [UserController::class, 'edit']
    )->name('users.edit');

    Route::put(
        '/users/{user}',
        [UserController::class, 'update']
    )->name('users.update');

    Route::delete(
        '/users/{user}',
        [UserController::class, 'destroy']
    )->name('users.destroy');

    Route::patch(
        '/users/{user}/toggle-status',
        [UserController::class, 'toggleStatus']
    )->name('users.toggle-status');

    Route::get(
        '/agents-recenseurs',
        [UserController::class, 'agents']
    )->name('agents.index');

    Route::post(
        '/users/{user}/rattacher-prefecture',
        [UserController::class, 'rattacherPrefecture']
    )->name('users.rattacher-prefecture');


    /*
    |--------------------------------------------------------------------------
    | RÉGIONS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/regions',
        [RegionController::class, 'index']
    )->name('regions.index');

    Route::get(
        '/regions/create',
        [RegionController::class, 'create']
    )->name('regions.create');

    Route::post(
        '/regions',
        [RegionController::class, 'store']
    )->name('regions.store');

    Route::get(
        '/regions/{region}',
        [RegionController::class, 'show']
    )->name('regions.show');

    Route::get(
        '/regions/{region}/edit',
        [RegionController::class, 'edit']
    )->name('regions.edit');

    Route::put(
        '/regions/{region}',
        [RegionController::class, 'update']
    )->name('regions.update');

    Route::delete(
        '/regions/{region}',
        [RegionController::class, 'destroy']
    )->name('regions.destroy');


    /*
    |--------------------------------------------------------------------------
    | PRÉFECTURES
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/prefectures',
        [PrefectureController::class, 'index']
    )->name('prefectures.index');

    Route::get(
        '/prefectures/create',
        [PrefectureController::class, 'create']
    )->name('prefectures.create');

    Route::post(
        '/prefectures',
        [PrefectureController::class, 'store']
    )->name('prefectures.store');

    Route::get(
        '/prefectures/{prefecture}',
        [PrefectureController::class, 'show']
    )->name('prefectures.show');

    Route::get(
        '/prefectures/{prefecture}/edit',
        [PrefectureController::class, 'edit']
    )->name('prefectures.edit');

    Route::put(
        '/prefectures/{prefecture}',
        [PrefectureController::class, 'update']
    )->name('prefectures.update');

    Route::delete(
        '/prefectures/{prefecture}',
        [PrefectureController::class, 'destroy']
    )->name('prefectures.destroy');


    /*
    |--------------------------------------------------------------------------
    | COMMUNES
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/communes',
        [CommuneController::class, 'index']
    )->name('communes.index');

    Route::get(
        '/communes/create',
        [CommuneController::class, 'create']
    )->name('communes.create');

    Route::post(
        '/communes',
        [CommuneController::class, 'store']
    )->name('communes.store');

    Route::get(
        '/communes/{commune}',
        [CommuneController::class, 'show']
    )->name('communes.show');

    Route::get(
        '/communes/{commune}/edit',
        [CommuneController::class, 'edit']
    )->name('communes.edit');

    Route::put(
        '/communes/{commune}',
        [CommuneController::class, 'update']
    )->name('communes.update');

    Route::delete(
        '/communes/{commune}',
        [CommuneController::class, 'destroy']
    )->name('communes.destroy');


    /*
    |--------------------------------------------------------------------------
    | CANTONS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/cantons',
        [CantonController::class, 'index']
    )->name('cantons.index');

    Route::get(
        '/cantons/create',
        [CantonController::class, 'create']
    )->name('cantons.create');

    Route::post(
        '/cantons',
        [CantonController::class, 'store']
    )->name('cantons.store');

    Route::get(
        '/cantons/{canton}',
        [CantonController::class, 'show']
    )->name('cantons.show');

    Route::get(
        '/cantons/{canton}/edit',
        [CantonController::class, 'edit']
    )->name('cantons.edit');

    Route::put(
        '/cantons/{canton}',
        [CantonController::class, 'update']
    )->name('cantons.update');

    Route::delete(
        '/cantons/{canton}',
        [CantonController::class, 'destroy']
    )->name('cantons.destroy');


    /*
    |--------------------------------------------------------------------------
    | VILLAGES
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/villages',
        [VillageController::class, 'index']
    )->name('villages.index');

    Route::get(
        '/villages/create',
        [VillageController::class, 'create']
    )->name('villages.create');

    Route::post(
        '/villages',
        [VillageController::class, 'store']
    )->name('villages.store');

    Route::get(
        '/villages/{village}',
        [VillageController::class, 'show']
    )->name('villages.show');

    Route::get(
        '/villages/{village}/edit',
        [VillageController::class, 'edit']
    )->name('villages.edit');

    Route::put(
        '/villages/{village}',
        [VillageController::class, 'update']
    )->name('villages.update');

    Route::delete(
        '/villages/{village}',
        [VillageController::class, 'destroy']
    )->name('villages.destroy');


    /*
    |--------------------------------------------------------------------------
    | CAMPAGNES DE RECENSEMENT — ADMINISTRATION
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/campagnes',
        [CampagneRecensementController::class, 'index']
    )->name('campagnes.index');

    Route::get(
        '/campagnes/create',
        [CampagneRecensementController::class, 'create']
    )->name('campagnes.create');

    Route::post(
        '/campagnes',
        [CampagneRecensementController::class, 'store']
    )->name('campagnes.store');

    Route::get(
        '/campagnes/{campagne}',
        [CampagneRecensementController::class, 'show']
    )->name('campagnes.show');

    Route::get(
        '/campagnes/{campagne}/edit',
        [CampagneRecensementController::class, 'edit']
    )->name('campagnes.edit');

    Route::put(
        '/campagnes/{campagne}',
        [CampagneRecensementController::class, 'update']
    )->name('campagnes.update');

    Route::delete(
        '/campagnes/{campagne}',
        [CampagneRecensementController::class, 'destroy']
    )->name('campagnes.destroy');

    Route::patch(
        '/campagnes/{campagne}/activate',
        [CampagneRecensementController::class, 'activate']
    )->name('campagnes.activate');

    Route::patch(
        '/campagnes/{campagne}/close',
        [CampagneRecensementController::class, 'close']
    )->name('campagnes.close');

    Route::patch(
        '/campagnes/{campagne}/archive',
        [CampagneRecensementController::class, 'archive']
    )->name('campagnes.archive');

    Route::post(
        '/campagnes/{campagne}/deploy',
        [CampagneRecensementController::class, 'deploy']
    )->name('campagnes.deploy');


    /*
    |--------------------------------------------------------------------------
    | AFFECTATIONS — ADMINISTRATION
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/affectations',
        [AffectationController::class, 'index']
    )->name('affectations.index');

    Route::get(
        '/affectations/create',
        [AffectationController::class, 'create']
    )->name('affectations.create');

    Route::post(
        '/affectations',
        [AffectationController::class, 'store']
    )->name('affectations.store');

    Route::get(
        '/affectations/{affectation}',
        [AffectationController::class, 'show']
    )->name('affectations.show');

    Route::get(
        '/affectations/{affectation}/edit',
        [AffectationController::class, 'edit']
    )->name('affectations.edit');

    Route::put(
        '/affectations/{affectation}',
        [AffectationController::class, 'update']
    )->name('affectations.update');

    Route::delete(
        '/affectations/{affectation}',
        [AffectationController::class, 'destroy']
    )->name('affectations.destroy');


    /*
    |--------------------------------------------------------------------------
    | MAISONS — RÉFÉRENTIEL PERMANENT
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/villages/{village}/maisons',
        [MaisonController::class, 'index']
    )->name('villages.maisons.index');

    Route::get(
        '/villages/{village}/maisons/create',
        [MaisonController::class, 'create']
    )->name('villages.maisons.create');

    Route::post(
        '/villages/{village}/maisons',
        [MaisonController::class, 'store']
    )->name('villages.maisons.store');

    Route::get(
        '/maisons/{maison}',
        [MaisonController::class, 'show']
    )->name('maisons.show');

    Route::get(
        '/maisons/{maison}/edit',
        [MaisonController::class, 'edit']
    )->name('maisons.edit');

    Route::put(
        '/maisons/{maison}',
        [MaisonController::class, 'update']
    )->name('maisons.update');

    Route::delete(
        '/maisons/{maison}',
        [MaisonController::class, 'destroy']
    )->name('maisons.destroy');


    /*
    |--------------------------------------------------------------------------
    | MENAGES
    |--------------------------------------------------------------------------
    |
    | Ces routes sont conservées pour ne pas casser le module actuel.
    | L'évolution Menage → Recensement sera faite séparément.
    |
    */

    Route::get(
        '/maisons/{maison}/menages',
        [MenageController::class, 'index']
    )->name('maisons.menages.index');

    Route::get(
        '/maisons/{maison}/menages/create',
        [MenageController::class, 'create']
    )->name('maisons.menages.create');

    Route::post(
        '/maisons/{maison}/menages',
        [MenageController::class, 'store']
    )->name('maisons.menages.store');

    Route::get(
        '/menages/{menage}',
        [MenageController::class, 'show']
    )->name('menages.show');

    Route::get(
        '/menages/{menage}/edit',
        [MenageController::class, 'edit']
    )->name('menages.edit');

    Route::put(
        '/menages/{menage}',
        [MenageController::class, 'update']
    )->name('menages.update');

    Route::delete(
        '/menages/{menage}',
        [MenageController::class, 'destroy']
    )->name('menages.destroy');

});


/*
|--------------------------------------------------------------------------
| DPA
|--------------------------------------------------------------------------
|
| Tout ce qui concerne le travail du DPA est regroupé ici.
| Aucun parcours Agent n'est déclaré dans ce groupe.
|
*/

Route::middleware(['auth'])
    ->prefix('dpa')
    ->name('dpa.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | AGENTS ET SUPERVISEURS
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'agents',
            DpaAgentController::class
        );

        Route::patch(
            'agents/{user}/toggle-status',
            [DpaAgentController::class, 'toggleStatus']
        )->name('agents.toggle-status');


        /*
        |--------------------------------------------------------------------------
        | ÉQUIPES
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/equipes',
            [EquipeController::class, 'index']
        )->name('equipes.index');

        Route::get(
            '/equipes/create',
            [EquipeController::class, 'create']
        )->name('equipes.create');

        Route::post(
            '/equipes',
            [EquipeController::class, 'store']
        )->name('equipes.store');

        Route::get(
            '/equipes/{equipe}',
            [EquipeController::class, 'show']
        )->name('equipes.show');

        Route::get(
            '/equipes/{equipe}/edit',
            [EquipeController::class, 'edit']
        )->name('equipes.edit');

        Route::put(
            '/equipes/{equipe}',
            [EquipeController::class, 'update']
        )->name('equipes.update');

        Route::delete(
            '/equipes/{equipe}',
            [EquipeController::class, 'destroy']
        )->name('equipes.destroy');

        Route::patch(
            '/equipes/{equipe}/reactiver',
            [EquipeController::class, 'reactiver']
        )->name('equipes.reactiver');


        /*
        |--------------------------------------------------------------------------
        | AFFECTATIONS DES ÉQUIPES
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/affectations',
            [AffectationController::class, 'index']
        )->name('affectations.index');

        Route::get(
            '/equipes/{equipe}/affectations',
            [AffectationController::class, 'index']
        )->name('equipes.affectations.index');

        Route::get(
            '/equipes/{equipe}/affectations/create',
            [AffectationController::class, 'create']
        )->name('equipes.affectations.create');

        Route::post(
            '/equipes/{equipe}/affectations',
            [AffectationController::class, 'store']
        )->name('equipes.affectations.store');

        Route::get(
            '/equipes/{equipe}/affectations/{affectation}/edit',
            [AffectationController::class, 'edit']
        )->name('equipes.affectations.edit');

        Route::put(
            '/equipes/{equipe}/affectations/{affectation}',
            [AffectationController::class, 'update']
        )->name('equipes.affectations.update');

        Route::patch(
            '/equipes/{equipe}/affectations/{affectation}/desactiver',
            [AffectationController::class, 'desactiver']
        )->name('equipes.affectations.desactiver');

        Route::get(
            '/affectations/{affectation}',
            [AffectationController::class, 'show']
        )->name('affectations.show');

        Route::delete(
            '/affectations/{affectation}',
            [AffectationController::class, 'destroy']
        )->name('affectations.destroy');

        Route::patch(
            '/affectations/{affectation}/reactiver',
            [AffectationController::class, 'reactiver']
        )->name('affectations.reactiver');


        /*
        |--------------------------------------------------------------------------
        | CAMPAGNES DPA
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/campagnes',
            [DpaCampagneController::class, 'index']
        )->name('campagnes.index');


        /*
        |--------------------------------------------------------------------------
        | PLANIFICATIONS PRÉFECTORALES
        |--------------------------------------------------------------------------
        |
        | Cette route doit rester AVANT /campagnes/{deploiement}.
        |
        */

        Route::get(
            '/campagnes/planifications',
            [PlanificationPrefectoraleController::class, 'index']
        )->name('planifications-prefectorales.index');


        /*
        |--------------------------------------------------------------------------
        | DÉTAIL D'UN DÉPLOIEMENT
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/campagnes/{deploiement}',
            [DpaCampagneController::class, 'show']
        )->name('campagnes.show');


        /*
        |--------------------------------------------------------------------------
        | RÉCEPTION D'UN DÉPLOIEMENT
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/campagnes/{deploiement}/receive',
            [DpaCampagneController::class, 'receive']
        )->name('campagnes.receive');


        /*
        |--------------------------------------------------------------------------
        | PLANIFICATION PRÉFECTORALE
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/deploiements/{deploiement}/planification/create',
            [PlanificationPrefectoraleController::class, 'create']
        )->name('planifications-prefectorales.create');

        Route::post(
            '/deploiements/{deploiement}/planification',
            [PlanificationPrefectoraleController::class, 'store']
        )->name('planifications-prefectorales.store');

        Route::get(
            '/planifications-prefectorales/{planificationPrefectorale}',
            [PlanificationPrefectoraleController::class, 'show']
        )->name('planifications-prefectorales.show');

        Route::get(
            '/planifications-prefectorales/{planificationPrefectorale}/edit',
            [PlanificationPrefectoraleController::class, 'edit']
        )->name('planifications-prefectorales.edit');

        Route::put(
            '/planifications-prefectorales/{planificationPrefectorale}',
            [PlanificationPrefectoraleController::class, 'update']
        )->name('planifications-prefectorales.update');

        Route::delete(
            '/planifications-prefectorales/{planificationPrefectorale}',
            [PlanificationPrefectoraleController::class, 'destroy']
        )->name('planifications-prefectorales.destroy');
    });


/*
|--------------------------------------------------------------------------
| PLANIFICATION GÉNÉRALE DES CAMPAGNES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('campagnes/{campagne}/planification')
    ->name('campagnes.planification.')
    ->group(function () {

        Route::get(
            '/create',
            [CampagnePlanificationController::class, 'create']
        )->name('create');

        Route::post(
            '/',
            [CampagnePlanificationController::class, 'store']
        )->name('store');

        Route::get(
            '/',
            [CampagnePlanificationController::class, 'show']
        )->name('show');

        Route::get(
            '/edit',
            [CampagnePlanificationController::class, 'edit']
        )->name('edit');

        Route::put(
            '/',
            [CampagnePlanificationController::class, 'update']
        )->name('update');
    });


/*
|--------------------------------------------------------------------------
| SUPERVISEUR
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('superviseur')
    ->name('superviseur.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | MES ÉQUIPES
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/mes-equipes',
            [EquipeController::class, 'mesEquipes']
        )->name('equipes.index');

        Route::get(
            '/mes-equipes/{equipe:reference}',
            [EquipeController::class, 'showSuperviseur']
        )->name('equipes.show');


        /*
        |--------------------------------------------------------------------------
        | MES ZONES
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/mes-zones',
            [AffectationController::class, 'mesZonesSupervision']
        )->name('zones.index');

        Route::get(
            '/mes-zones/{affectation:reference}',
            [AffectationController::class, 'zoneSupervision']
        )->name('zones.show');


        /*
        |--------------------------------------------------------------------------
        | SUIVI
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/suivi',
            [SuperviseurController::class, 'suivi']
        )->name('suivi.index');


        /*
        |--------------------------------------------------------------------------
        | CONTRÔLE QUALITÉ
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/controle-qualite',
            [SuperviseurController::class, 'controleQualite']
        )->name('controle-qualite.index');
    });


/*
|--------------------------------------------------------------------------
| AGENT RECENSEUR
|--------------------------------------------------------------------------
|
| Parcours opérationnel :
|
| Mes affectations
|       ↓
| Zone de collecte
|       ↓
| Maisons du village
|       ↓
| Recensement
|
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | MES AFFECTATIONS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/mes-affectations',
        [AffectationController::class, 'mesAffectations']
    )->name('agent.affectations');

    Route::get(
        '/mes-affectations/{affectation}',
        [AffectationController::class, 'detailAgent']
    )->name('agent.affectations.show');


    /*
    |--------------------------------------------------------------------------
    | ZONE DE COLLECTE
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/mes-affectations/{affectation}/zone-collecte',
        [RecensementController::class, 'zoneCollecte']
    )->name('agent.zone.collecte.show');


    /*
    |--------------------------------------------------------------------------
    | MES RECENSEMENTS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/mes-recensements',
        [RecensementController::class, 'index']
    )->name('agent.recensements.index');

    Route::get(
        '/mes-recensements/{recensement}',
        [RecensementController::class, 'show']
    )->name('agent.recensements.show');


    /*
    |--------------------------------------------------------------------------
    | COMMENCER UN RECENSEMENT
    |--------------------------------------------------------------------------
    |
    | Cas : la Maison existe déjà dans le référentiel permanent.
    |
    */

    Route::post(
        '/mes-affectations/{affectation}/maisons/{maison}/recensement',
        [RecensementController::class, 'commencer']
    )->name('agent.recensements.commencer');


    /*
    |--------------------------------------------------------------------------
    | NOUVELLE MAISON + PREMIER RECENSEMENT
    |--------------------------------------------------------------------------
    |
    | Cas : la Maison n'existe pas encore.
    |
    */

   
});


/*
|--------------------------------------------------------------------------
| AUTHENTIFICATION BREEZE
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';