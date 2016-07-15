<?php

namespace App\Providers;

use App\Services\EzTranslatorService;
use Illuminate\Support\ServiceProvider;

class EzTranslatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('eztrans', function ($app) {
            return new EzTranslatorService();
        });
    }
}
