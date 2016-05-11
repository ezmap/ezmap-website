<?php

namespace App\Providers;

use App\Theme;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $themes = Theme::orderBy('name')->paginate(24);
        view()->share('themes', $themes);
        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
