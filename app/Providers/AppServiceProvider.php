<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Parametre;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        config(['session.lifetime' => Parametre::valeur('securite.duree_session_minutes', 120)]);
    }
}
