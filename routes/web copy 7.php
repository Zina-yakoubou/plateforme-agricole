<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| GÉNÉRAL
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StatistiqueController;

/*
|--------------------------------------------------------------------------
| RÉFÉRENTIEL GÉOGRAPHIQUE
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\RegionController;
use App\Http\Controllers\PrefectureController;
use App\Http\Controllers\CommuneController;
use App\Http\Controllers\CantonController;
use App\Http\Controllers\VillageController;

/*
|--------------------------------------------------------------------------
| CAMPAGNES
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\CampagneRecensementController;
use App\Http\Controllers\CampagnePlanificationController;
use App\Http\Controllers\DpaCampagneController;

/*
|--------------------------------------------------------------------------
| AFFECTATIONS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\AffectationController;

/*
|--------------------------------------------------------------------------
| RÉFÉRENTIEL PERMANENT
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\MaisonController;
use App\Http\Controllers\MenageController;
use App\Http\Controllers\ExploitationController;
use App\Http\Controllers\ExploitantController;
use App\Http\Controllers\ParcelleController;

/*
|--------------------------------------------------------------------------
| SUPERVISION / RECENSEMENT
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\SuperviseurController;
use App\Http\Controllers\RecensementController;

/*
|--------------------------------------------------------------------------
| DPA
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Dpa\PlanificationPrefectoraleController;
use App\Http\Controllers\Dpa\EquipeController;
use App\Http\Controllers\Dpa\DpaAgentController;


/*
|--------------------------------------------------------------------------
| ACCUEIL PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| ROUTES AUTHENTIFIÉES COMMUNES
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
});


/*
|--------------------------------------------------------------------------
| ADMINISTRATEUR
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

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


    /*
    |--------------------------------------------------------------------------
    | AGENTS RECENSEURS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/agents-recenseurs',
        [UserController::class, 'agents']
    )->name('agents-recenseurs.index');


    /*
    |--------------------------------------------------------------------------
    | RATTACHEMENT À UNE PRÉFECTURE
    |--------------------------------------------------------------------------
    */

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
    | CAMPAGNES DE RECENSEMENT
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
    | AFFECTATIONS
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
});


/*
|--------------------------------------------------------------------------
| DPA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('dpa')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | AGENTS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/agents',
            [DpaAgentController::class, 'index']
        )->name('agents.index');

        Route::get(
            '/agents/create',
            [DpaAgentController::class, 'create']
        )->name('agents.create');

        Route::post(
            '/agents',
            [DpaAgentController::class, 'store']
        )->name('agents.store');

        Route::get(
            '/agents/{user}',
            [DpaAgentController::class, 'show']
        )->name('agents.show');

        Route::get(
            '/agents/{user}/edit',
            [DpaAgentController::class, 'edit']
        )->name('agents.edit');

        Route::put(
            '/agents/{user}',
            [DpaAgentController::class, 'update']
        )->name('agents.update');

        Route::delete(
            '/agents/{user}',
            [DpaAgentController::class, 'destroy']
        )->name('agents.destroy');

        Route::patch(
            '/agents/{user}/toggle-status',
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
        )->name('affectations-dpa.index');

        Route::get(
            '/equipes/{equipe}/affectations',
            [AffectationController::class, 'index']
        )->name('equipe-affectations.index');

        Route::get(
            '/equipes/{equipe}/affectations/create',
            [AffectationController::class, 'create']
        )->name('equipe-affectations.create');

        Route::post(
            '/equipes/{equipe}/affectations',
            [AffectationController::class, 'store']
        )->name('equipe-affectations.store');

        Route::get(
            '/equipes/{equipe}/affectations/{affectation}/edit',
            [AffectationController::class, 'edit']
        )->name('equipe-affectations.edit');

        Route::put(
            '/equipes/{equipe}/affectations/{affectation}',
            [AffectationController::class, 'update']
        )->name('equipe-affectations.update');

        Route::patch(
            '/equipes/{equipe}/affectations/{affectation}/desactiver',
            [AffectationController::class, 'desactiver']
        )->name('equipe-affectations.desactiver');

        Route::get(
            '/affectations/{affectation}',
            [AffectationController::class, 'show']
        )->name('affectations-dpa.show');

        Route::delete(
            '/affectations/{affectation}',
            [AffectationController::class, 'destroy']
        )->name('affectations-dpa.destroy');

        Route::patch(
            '/affectations/{affectation}/reactiver',
            [AffectationController::class, 'reactiver']
        )->name('affectations-dpa.reactiver');


        /*
        |--------------------------------------------------------------------------
        | CAMPAGNES DÉPLOYÉES
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/campagnes',
            [DpaCampagneController::class, 'index']
        )->name('campagnes-deployees.index');

        Route::get(
            '/campagnes/planifications',
            [PlanificationPrefectoraleController::class, 'index']
        )->name('planifications-prefectorales.index');

        Route::get(
            '/campagnes/{deploiement}',
            [DpaCampagneController::class, 'show']
        )->name('campagnes-deployees.show');

        Route::patch(
            '/campagnes/{deploiement}/receive',
            [DpaCampagneController::class, 'receive']
        )->name('campagnes-deployees.receive');


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
    ->group(function () {

        Route::get(
            '/create',
            [CampagnePlanificationController::class, 'create']
        )->name('planification.create');

        Route::post(
            '/',
            [CampagnePlanificationController::class, 'store']
        )->name('planification.store');

        Route::get(
            '/',
            [CampagnePlanificationController::class, 'show']
        )->name('planification.show');

        Route::get(
            '/edit',
            [CampagnePlanificationController::class, 'edit']
        )->name('planification.edit');

        Route::put(
            '/',
            [CampagnePlanificationController::class, 'update']
        )->name('planification.update');
    });


/*
|--------------------------------------------------------------------------
| SUPERVISEUR
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('superviseur')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | MES ÉQUIPES
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/mes-equipes',
            [EquipeController::class, 'mesEquipes']
        )->name('mes-equipes.index');

        Route::get(
            '/mes-equipes/{equipe:reference}',
            [EquipeController::class, 'showSuperviseur']
        )->name('mes-equipes.show');


        /*
        |--------------------------------------------------------------------------
        | MES ZONES
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/mes-zones',
            [AffectationController::class, 'mesZonesSupervision']
        )->name('mes-zones.index');

        Route::get(
            '/mes-zones/{affectation:reference}',
            [AffectationController::class, 'zoneSupervision']
        )->name('mes-zones.show');


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
| Parcours :
|
| Mes affectations
|       ↓
| Détail de l'affectation
|       ↓
| Maisons
|       ↓
| Recensement
|       ↓
| Ménages
|       ↓
| Exploitants
|       ↓
| Exploitations
|       ↓
| Parcelles
|
*/

Route::middleware(['auth'])
    ->prefix('agent')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | MES AFFECTATIONS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/mes-affectations',
            [AffectationController::class, 'mesAffectations']
        )->name('mes-affectations.index');

        Route::get(
            '/mes-affectations/{affectation}',
            [AffectationController::class, 'detailAgent']
        )->name('mes-affectations.show');


        /*
        |--------------------------------------------------------------------------
        | MES RECENSEMENTS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/mes-recensements',
            [RecensementController::class, 'index']
        )->name('recensements.index');

        Route::get(
            '/mes-recensements/{recensement}',
            [RecensementController::class, 'show']
        )->name('recensements.show');


        /*
        |--------------------------------------------------------------------------
        | COMMENCER UN RECENSEMENT SUR UNE MAISON
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/mes-affectations/{affectation}/maisons/{maison}/recensement',
            [RecensementController::class, 'commencer']
        )->name('recensements.commencer');


        /*
        |--------------------------------------------------------------------------
        | SUIVI DU RECENSEMENT
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/mes-recensements/{recensement}/mettre-en-cours',
            [RecensementController::class, 'mettreEnCours']
        )->name('recensements.mettre-en-cours');

        Route::patch(
            '/mes-recensements/{recensement}/terminer',
            [RecensementController::class, 'terminer']
        )->name('recensements.terminer');


        /*
        |--------------------------------------------------------------------------
        | MÉNAGES — RECENSEMENT
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/mes-recensements/{recensement}/menages',
            [MenageController::class, 'index']
        )->name('menages.index');

        Route::get(
            '/mes-recensements/{recensement}/menages/create',
            [MenageController::class, 'create']
        )->name('menages.create');

        Route::post(
            '/mes-recensements/{recensement}/menages',
            [MenageController::class, 'store']
        )->name('menages.store');

        Route::get(
            '/mes-recensements/{recensement}/menages/{menage}/edit',
            [MenageController::class, 'edit']
        )->name('menages.edit');

        Route::put(
            '/mes-recensements/{recensement}/menages/{menage}',
            [MenageController::class, 'update']
        )->name('menages.update');

        Route::delete(
            '/mes-recensements/{recensement}/menages/{menage}',
            [MenageController::class, 'destroy']
        )->name('menages.destroy');


        /*
        |--------------------------------------------------------------------------
        | EXPLOITANTS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/mes-recensements/{recensement}/menages/{menage}/exploitants',
            [ExploitantController::class, 'index']
        )->name('exploitants.index');

        Route::get(
            '/mes-recensements/{recensement}/menages/{menage}/exploitants/create',
            [ExploitantController::class, 'create']
        )->name('exploitants.create');

        Route::post(
            '/mes-recensements/{recensement}/menages/{menage}/exploitants',
            [ExploitantController::class, 'store']
        )->name('exploitants.store');

        Route::get(
            '/mes-recensements/{recensement}/menages/{menage}/exploitants/{exploitant}/edit',
            [ExploitantController::class, 'edit']
        )->name('exploitants.edit');

        Route::put(
            '/mes-recensements/{recensement}/menages/{menage}/exploitants/{exploitant}',
            [ExploitantController::class, 'update']
        )->name('exploitants.update');

        Route::delete(
            '/mes-recensements/{recensement}/menages/{menage}/exploitants/{exploitant}',
            [ExploitantController::class, 'destroy']
        )->name('exploitants.destroy');


        /*
        |--------------------------------------------------------------------------
        | EXPLOITATIONS
        |--------------------------------------------------------------------------
        |
        | Une exploitation appartient à un exploitant.
        | Les paramètres recensement + menage + exploitant
        | restent dans l'URL pour respecter le parcours actuel.
        |
        */

        Route::get(
            '/mes-recensements/{recensement}/menages/{menage}/exploitants/{exploitant}/exploitation',
            [ExploitationController::class, 'index']
        )->name('exploitations.index');

        Route::get(
            '/mes-recensements/{recensement}/menages/{menage}/exploitants/{exploitant}/exploitation/create',
            [ExploitationController::class, 'create']
        )->name('exploitations.create');

        Route::post(
            '/mes-recensements/{recensement}/menages/{menage}/exploitants/{exploitant}/exploitation',
            [ExploitationController::class, 'store']
        )->name('exploitations.store');

        Route::get(
            '/mes-recensements/{recensement}/menages/{menage}/exploitants/{exploitant}/exploitation/edit',
            [ExploitationController::class, 'edit']
        )->name('exploitations.edit');

        Route::put(
            '/mes-recensements/{recensement}/menages/{menage}/exploitants/{exploitant}/exploitation',
            [ExploitationController::class, 'update']
        )->name('exploitations.update');

        Route::delete(
            '/mes-recensements/{recensement}/menages/{menage}/exploitants/{exploitant}/exploitation',
            [ExploitationController::class, 'destroy']
        )->name('exploitations.destroy');


        /*
        |--------------------------------------------------------------------------
        | PARCELLES
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/mes-recensements/{recensement}/menages/{menage}/exploitants/{exploitant}/parcelles',
            [ParcelleController::class, 'index']
        )->name('parcelles.index');

        Route::get(
            '/mes-recensements/{recensement}/menages/{menage}/exploitants/{exploitant}/parcelles/create',
            [ParcelleController::class, 'create']
        )->name('parcelles.create');

        Route::post(
            '/mes-recensements/{recensement}/menages/{menage}/exploitants/{exploitant}/parcelles',
            [ParcelleController::class, 'store']
        )->name('parcelles.store');

        Route::get(
            '/mes-recensements/{recensement}/menages/{menage}/exploitants/{exploitant}/parcelles/{parcelle}/edit',
            [ParcelleController::class, 'edit']
        )->name('parcelles.edit');

        Route::put(
            '/mes-recensements/{recensement}/menages/{menage}/exploitants/{exploitant}/parcelles/{parcelle}',
            [ParcelleController::class, 'update']
        )->name('parcelles.update');

        Route::delete(
            '/mes-recensements/{recensement}/menages/{menage}/exploitants/{exploitant}/parcelles/{parcelle}',
            [ParcelleController::class, 'destroy']
        )->name('parcelles.destroy');
    });


/*
|--------------------------------------------------------------------------
| MAISONS — RÉFÉRENTIEL PERMANENT
|--------------------------------------------------------------------------
|
| Une Maison est permanente.
| Le Recensement est lié à une campagne.
|
*/

Route::middleware(['auth'])->group(function () {

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
});


/*
|--------------------------------------------------------------------------
| MÉNAGES — RÉFÉRENTIEL EXISTANT
|--------------------------------------------------------------------------
|
| Ces routes sont conservées pour ne pas casser
| le module permanent/existant.
|
*/

Route::middleware(['auth'])->group(function () {

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
| AUTHENTIFICATION BREEZE
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';

