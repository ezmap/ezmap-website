<?php

namespace App\Providers;

use App\Models\Map;
use App\Models\Theme;
use App\Policies\MapPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
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

    if (Schema::hasTable('themes'))
    {
      $tags    = ['colorful', 'complex', 'dark', 'greyscale', 'light', 'monochrome', 'no-labels', 'simple', 'two-tone'];
      $colors  = ['black', 'blue', 'gray', 'green', 'multi', 'orange', 'purple', 'red', 'white', 'yellow'];
      $sort    = 'name';
      $order   = 'asc';
      $appends = [];
      if (request()->has('tag') || request()->has('col'))
      {
        if (request()->has('tag'))
        {
          $tag = in_array(request()->input('tag'), $tags) ? request()->input('tag') : '';
        }
        if (request()->has('col'))
        {
          $col = in_array(request()->input('col'), $colors) ? request()->input('col') : '';
        }
      }
      $themes = Theme::orderBy($sort, $order)->paginate(24);
      if (!empty($col))
      {
        $themes         = Theme::orderBy($sort, $order)->where('colors', 'LIKE', "%{$col}%")->paginate(24);
        $appends['col'] = $col;
      } elseif (!empty($tag))
      {
        $themes         = Theme::orderBy($sort, $order)->where('tags', 'LIKE', "%{$tag}%")->paginate(24);
        $appends['tag'] = $tag;
      }

      view()->share(compact('themes', 'appends'));
    }
  }

}
