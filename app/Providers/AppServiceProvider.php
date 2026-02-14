<?php

namespace App\Providers;

use App\Models\Map;
use App\Policies\MapPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    $this->app->booting(function () {
      $loader = \Illuminate\Foundation\AliasLoader::getInstance();
      $loader->alias('EzTrans', \App\Facades\EzTranslator::class);
    });
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    Gate::policy(Map::class, MapPolicy::class);
  }

}
