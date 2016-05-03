<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => [GrahamCampbell\HTMLMin\Http\Middleware\MinifyMiddleware::class]], function() {
    Route::get('/', function () {
        $themes = App\Theme::orderBy('name')->paginate(24);

        return view('index', compact('themes'));
    })->middleware(['guest']);


    Route:: get('test', function () {
        return view('test');
    });

    Route::auth();

    Route::get('/home', 'HomeController@index');

    Route::get('admin', 'AdminController@index');
    Route::post('admin', 'SnazzyMapsController@populateThemes')->name('populateThemes');
    Route::get('snazzymaps', 'SnazzyMapsController@index')->name('snazzymaps');
});