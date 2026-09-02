<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\PrefectureController;
use App\Http\Controllers\CommuneController;
use App\Http\Controllers\CantonController;
use App\Http\Controllers\VillageController;
use App\Http\Controllers\CampagneRecensementController;
use App\Http\Controllers\AffectationController;
use App\Http\Controllers\MaisonController;
use App\Http\Controllers\MenageController;
use App\Http\Controllers\DpaCampagneController;
use App\Http\Controllers\CampagnePlanificationController;
use App\Http\Controllers\Dpa\PlanificationPrefectoraleController;
use App\Http\Controllers\Dpa\EquipeController;



/*
|--------------------------------------------------------------------------
| Accueil
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Routes authentifiées
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Tableau de bord
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Profil
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    /*
    |--------------------------------------------------------------------------
    | Utilisateurs
    |--------------------------------------------------------------------------
    */

    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index');

    Route::get('/users/create', [UserController::class, 'create'])
        ->name('users.create');

    Route::post('/users', [UserController::class, 'store'])
        ->name('users.store');

    Route::get('/users/{user}', [UserController::class, 'show'])
        ->name('users.show');

    Route::get('/users/{user}/edit', [UserController::class, 'edit'])
        ->name('users.edit');

    Route::put('/users/{user}', [UserController::class, 'update'])
        ->name('users.update');

    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->name('users.destroy');

    Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
        ->name('users.toggle-status');

    Route::get('/agents-recenseurs', [UserController::class, 'agents'])
        ->name('agents.index');

    Route::post(
            '/users/{user}/rattacher-prefecture',
            [UserController::class, 'rattacherPrefecture']
        )->name('users.rattacher-prefecture');


    /*
    |--------------------------------------------------------------------------
    | Régions
    |--------------------------------------------------------------------------
    */

    Route::get('/regions', [RegionController::class, 'index'])
        ->name('regions.index');

    Route::get('/regions/create', [RegionController::class, 'create'])
        ->name('regions.create');

    Route::post('/regions', [RegionController::class, 'store'])
        ->name('regions.store');

    Route::get('/regions/{region}', [RegionController::class, 'show'])
        ->name('regions.show');

    Route::get('/regions/{region}/edit', [RegionController::class, 'edit'])
        ->name('regions.edit');

    Route::put('/regions/{region}', [RegionController::class, 'update'])
        ->name('regions.update');

    Route::delete('/regions/{region}', [RegionController::class, 'destroy'])
        ->name('regions.destroy');


    /*
    |--------------------------------------------------------------------------
    | Préfectures
    |--------------------------------------------------------------------------
    */

    Route::get('/prefectures', [PrefectureController::class, 'index'])
        ->name('prefectures.index');

    Route::get('/prefectures/create', [PrefectureController::class, 'create'])
        ->name('prefectures.create');

    Route::post('/prefectures', [PrefectureController::class, 'store'])
        ->name('prefectures.store');

    Route::get('/prefectures/{prefecture}', [PrefectureController::class, 'show'])
        ->name('prefectures.show');

    Route::get('/prefectures/{prefecture}/edit', [PrefectureController::class, 'edit'])
        ->name('prefectures.edit');

    Route::put('/prefectures/{prefecture}', [PrefectureController::class, 'update'])
        ->name('prefectures.update');

    Route::delete('/prefectures/{prefecture}', [PrefectureController::class, 'destroy'])
        ->name('prefectures.destroy');


    /*
    |--------------------------------------------------------------------------
    | Communes
    |--------------------------------------------------------------------------
    */

    Route::get('/communes', [CommuneController::class, 'index'])
        ->name('communes.index');

    Route::get('/communes/create', [CommuneController::class, 'create'])
        ->name('communes.create');

    Route::post('/communes', [CommuneController::class, 'store'])
        ->name('communes.store');

    Route::get('/communes/{commune}', [CommuneController::class, 'show'])
        ->name('communes.show');

    Route::get('/communes/{commune}/edit', [CommuneController::class, 'edit'])
        ->name('communes.edit');

    Route::put('/communes/{commune}', [CommuneController::class, 'update'])
        ->name('communes.update');

    Route::delete('/communes/{commune}', [CommuneController::class, 'destroy'])
        ->name('communes.destroy');


    /*
    |--------------------------------------------------------------------------
    | Cantons
    |--------------------------------------------------------------------------
    */

    Route::get('/cantons', [CantonController::class, 'index'])
        ->name('cantons.index');

    Route::get('/cantons/create', [CantonController::class, 'create'])
        ->name('cantons.create');

    Route::post('/cantons', [CantonController::class, 'store'])
        ->name('cantons.store');

    Route::get('/cantons/{canton}', [CantonController::class, 'show'])
        ->name('cantons.show');

    Route::get('/cantons/{canton}/edit', [CantonController::class, 'edit'])
        ->name('cantons.edit');

    Route::put('/cantons/{canton}', [CantonController::class, 'update'])
        ->name('cantons.update');

    Route::delete('/cantons/{canton}', [CantonController::class, 'destroy'])
        ->name('cantons.destroy');


    /*
    |--------------------------------------------------------------------------
    | Villages
    |--------------------------------------------------------------------------
    */

    Route::get('/villages', [VillageController::class, 'index'])
        ->name('villages.index');

    Route::get('/villages/create', [VillageController::class, 'create'])
        ->name('villages.create');

    Route::post('/villages', [VillageController::class, 'store'])
        ->name('villages.store');

    Route::get('/villages/{village}', [VillageController::class, 'show'])
        ->name('villages.show');

    Route::get('/villages/{village}/edit', [VillageController::class, 'edit'])
        ->name('villages.edit');

    Route::put('/villages/{village}', [VillageController::class, 'update'])
        ->name('villages.update');

    Route::delete('/villages/{village}', [VillageController::class, 'destroy'])
        ->name('villages.destroy');


    /*
    |--------------------------------------------------------------------------
    | Campagnes de recensement
    |--------------------------------------------------------------------------
    */

    Route::get('/campagnes', [CampagneRecensementController::class, 'index'])
        ->name('campagnes.index');

    Route::get('/campagnes/create', [CampagneRecensementController::class, 'create'])
        ->name('campagnes.create');

    Route::post('/campagnes', [CampagneRecensementController::class, 'store'])
        ->name('campagnes.store');

    Route::get('/campagnes/{campagne}', [CampagneRecensementController::class, 'show'])
        ->name('campagnes.show');

    Route::get('/campagnes/{campagne}/edit', [CampagneRecensementController::class, 'edit'])
        ->name('campagnes.edit');

    Route::put('/campagnes/{campagne}', [CampagneRecensementController::class, 'update'])
        ->name('campagnes.update');

    Route::delete('/campagnes/{campagne}', [CampagneRecensementController::class, 'destroy'])
        ->name('campagnes.destroy');

    Route::patch('/campagnes/{campagne}/activate', [CampagneRecensementController::class, 'activate'])
        ->name('campagnes.activate');

    Route::patch('/campagnes/{campagne}/close', [CampagneRecensementController::class, 'close'])
        ->name('campagnes.close');

    Route::patch('/campagnes/{campagne}/archive', [CampagneRecensementController::class, 'archive'])
        ->name('campagnes.archive');

    Route::post(
        '/campagnes/{campagne}/deploy',
        [CampagneRecensementController::class, 'deploy']
    )->name('campagnes.deploy');

    /*
    |--------------------------------------------------------------------------
    | Affectations
    |--------------------------------------------------------------------------
    */

    Route::get('/affectations', [AffectationController::class, 'index'])
        ->name('affectations.index');

    Route::get('/affectations/create', [AffectationController::class, 'create'])
        ->name('affectations.create');

    Route::post('/affectations', [AffectationController::class, 'store'])
        ->name('affectations.store');

    Route::get('/affectations/{affectation}', [AffectationController::class, 'show'])
        ->name('affectations.show');

    Route::get('/affectations/{affectation}/edit', [AffectationController::class, 'edit'])
        ->name('affectations.edit');

    Route::put('/affectations/{affectation}', [AffectationController::class, 'update'])
        ->name('affectations.update');

    Route::delete('/affectations/{affectation}', [AffectationController::class, 'destroy'])
        ->name('affectations.destroy');


    /*
    |--------------------------------------------------------------------------
    | Affectations de l'agent recenseur
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
    | MAISONS
    |--------------------------------------------------------------------------
    */

    // Liste des maisons d'un village
    Route::get(
        '/villages/{village}/maisons',
        [MaisonController::class, 'index']
    )->name('villages.maisons.index');

    // Formulaire d'ajout d'une maison
    Route::get(
        '/villages/{village}/maisons/create',
        [MaisonController::class, 'create']
    )->name('villages.maisons.create');

    // Enregistrer une maison
    Route::post(
        '/villages/{village}/maisons',
        [MaisonController::class, 'store']
    )->name('villages.maisons.store');

    // Voir une maison
    Route::get(
        '/maisons/{maison}',
        [MaisonController::class, 'show']
    )->name('maisons.show');

    // Formulaire de modification
    Route::get(
        '/maisons/{maison}/edit',
        [MaisonController::class, 'edit']
    )->name('maisons.edit');

    // Modifier une maison
    Route::put(
        '/maisons/{maison}',
        [MaisonController::class, 'update']
    )->name('maisons.update');

    // Supprimer une maison
    Route::delete(
        '/maisons/{maison}',
        [MaisonController::class, 'destroy']
    )->name('maisons.destroy');


    /*
    |--------------------------------------------------------------------------
    | MENAGES
    |--------------------------------------------------------------------------
    */

    // Liste des ménages d'une maison
    Route::get(
        '/maisons/{maison}/menages',
        [MenageController::class, 'index']
    )->name('maisons.menages.index');

    // Formulaire d'ajout d'un ménage
    Route::get(
        '/maisons/{maison}/menages/create',
        [MenageController::class, 'create']
    )->name('maisons.menages.create');

    // Enregistrer un ménage
    Route::post(
        '/maisons/{maison}/menages',
        [MenageController::class, 'store']
    )->name('maisons.menages.store');

    // Voir un ménage
    Route::get(
        '/menages/{menage}',
        [MenageController::class, 'show']
    )->name('menages.show');

    // Modifier un ménage
    Route::get(
        '/menages/{menage}/edit',
        [MenageController::class, 'edit']
    )->name('menages.edit');

    // Mise à jour
    Route::put(
        '/menages/{menage}',
        [MenageController::class, 'update']
    )->name('menages.update');

    // Suppression
    Route::delete(
        '/menages/{menage}',
        [MenageController::class, 'destroy']
    )->name('menages.destroy');

});


/*
|--------------------------------------------------------------------------
| Routes d'authentification Breeze
|--------------------------------------------------------------------------
*/



/*
|--------------------------------------------------------------------------
| CAMPAGNES DPA
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| PLANIFICATION DES CAMPAGNES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('dpa')
    ->name('dpa.')
    ->group(function () {


        

    // Liste des équipes
        Route::get('/equipes', [
            EquipeController::class,
            'index'
        ])->name('equipes.index');

        // Formulaire de création
        Route::get('/equipes/create', [
            EquipeController::class,
            'create'
        ])->name('equipes.create');

        // Enregistrement
        Route::post('/equipes', [
            EquipeController::class,
            'store'
        ])->name('equipes.store');

        // Détail d'une équipe
        Route::get('/equipes/{equipe}', [
            EquipeController::class,
            'show'
        ])->name('equipes.show');

        // Formulaire de modification
        Route::get('/equipes/{equipe}/edit', [
            EquipeController::class,
            'edit'
        ])->name('equipes.edit');

        // Modification
        Route::put('/equipes/{equipe}', [
            EquipeController::class,
            'update'
        ])->name('equipes.update');

        // Suppression
        Route::delete('/equipes/{equipe}', [
            EquipeController::class,
            'destroy'
        ])->name('equipes.destroy');







         // =========================================================
        // AFFECTATIONS DES ÉQUIPES
        // =========================================================

        Route::get('/affectations', [AffectationController::class, 'index'])
            ->name('affectations.index');
        // Liste des affectations d'une équipe
        Route::get(
            '/equipes/{equipe}/affectations',
            [AffectationController::class, 'index']
        )->name('equipes.affectations.index');

        // Formulaire d'affectation
        Route::get(
            '/equipes/{equipe}/affectations/create',
            [AffectationController::class, 'create']
        )->name('equipes.affectations.create');

        // Enregistrer une affectation
        Route::post(
            '/equipes/{equipe}/affectations',
            [AffectationController::class, 'store']
        )->name('equipes.affectations.store');

        // Modifier une affectation
        Route::get(
            '/equipes/{equipe}/affectations/{affectation}/edit',
            [AffectationController::class, 'edit']
        )->name('equipes.affectations.edit');

        Route::put(
            '/equipes/{equipe}/affectations/{affectation}',
            [AffectationController::class, 'update']
        )->name('equipes.affectations.update');

        // Désactiver une affectation
        Route::patch(
            '/equipes/{equipe}/affectations/{affectation}/desactiver',
            [AffectationController::class, 'desactiver']
        )->name('equipes.affectations.desactiver');

        // =========================================================
        // RECONDUIRE UNE ÉQUIPE
        // =========================================================

        // Afficher le formulaire de reconduction
        // Route::get(
        //     '/equipes/{equipe}/reconduire',
        //     [AffectationController::class, 'reconduire']
        // )->name('equipes.reconduire');

        // Enregistrer la reconduction
        // Route::post(
        //     '/equipes/{equipe}/reconduire',
        //     [AffectationController::class, 'storeReconduction']
        // )->name('equipes.reconduire.store');

        // =========================================================
        // CAMPAGNES
        // =========================================================

        // Liste des campagnes
        Route::get(
            '/campagnes',
            [DpaCampagneController::class, 'index']
        )->name('campagnes.index');

        // Liste des planifications (IMPORTANT : AVANT {deploiement})
        Route::get(
            '/campagnes/planifications',
            [PlanificationPrefectoraleController::class, 'index']
        )->name('planifications-prefectorales.index');

        // Détail d'un déploiement
        Route::get(
            '/campagnes/{deploiement}',
            [DpaCampagneController::class, 'show']
        )->name('campagnes.show');

        // Réception d'un déploiement
        Route::patch(
            '/campagnes/{deploiement}/receive',
            [DpaCampagneController::class, 'receive']
        )->name('campagnes.receive');


        // =========================================================
        // PLANIFICATION PRÉFECTORALE
        // =========================================================

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




Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PLANIFICATION DES CAMPAGNES
    |--------------------------------------------------------------------------
    */

    Route::prefix('campagnes/{campagne}/planification')
        ->name('campagnes.planification.')
        ->group(function () {

            // Formulaire de planification
            Route::get('/create', [CampagnePlanificationController::class, 'create'])
                ->name('create');

            // Enregistrer la planification
            Route::post('/', [CampagnePlanificationController::class, 'store'])
                ->name('store');

            // Voir la planification
            Route::get('/', [CampagnePlanificationController::class, 'show'])
                ->name('show');

            // Modifier (pour plus tard)
            Route::get('/edit', [CampagnePlanificationController::class, 'edit'])
                ->name('edit');

            Route::put('/', [CampagnePlanificationController::class, 'update'])
                ->name('update');
        });

});





require __DIR__.'/auth.php';