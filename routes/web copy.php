<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\PrefectureController;
use App\Http\Controllers\CommuneController;
use App\Http\Controllers\CantonController;
use App\Http\Controllers\VillageController;
use App\Http\Controllers\CampagneRecensementController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});




Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    Route::resource('users', UserController::class);
    Route::patch('/users/{user}/toggle-status',
        [UserController::class, 'toggleStatus']
    )
    ->name('users.toggle-status');

     // Localisation
    Route::resource('regions', RegionController::class);

    Route::resource('prefectures', PrefectureController::class);

    Route::resource('communes', CommuneController::class);

    Route::resource('cantons', CantonController::class);

    Route::resource('villages', VillageController::class);
    Route::resource('campagnes', CampagneRecensementController::class);
    /*
    |--------------------------------------------------------------------------
    | Actions sur les campagnes
    |--------------------------------------------------------------------------
    */


    // Activer une campagne
    Route::patch(
        '/campagnes/{campagne}/activate',
        [
            CampagneRecensementController::class,
            'activate'
        ]
    )
    ->name('campagnes.activate');



    // Clôturer une campagne
    Route::patch(
        '/campagnes/{campagne}/close',
        [
            CampagneRecensementController::class,
            'close'
        ]
    )
    ->name('campagnes.close');



    // Archiver une campagne
    Route::patch(
        '/campagnes/{campagne}/archive',
        [
            CampagneRecensementController::class,
            'archive'
        ]
    )
    ->name('campagnes.archive');

     // Affectations
    Route::resource('affectations', AffectationController::class);


});

require __DIR__.'/auth.php';
