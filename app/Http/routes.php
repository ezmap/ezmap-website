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

Route::get('/', function ()
{
    $themes = App\Theme::orderBy('name')->paginate(24);

    return view('index', compact('themes'));
})->middleware(['guest', 'pjax']);


Route:: get('test', function ()
{
    return view('test');
})->middleware('admin');

Route::auth();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('admin', 'AdminController@index');
Route::post('admin', 'SnazzyMapsController@populateThemes')->name('populateThemes')->middleware('admin');

Route::post('addNewIcon', 'HomeController@addNewIcon')->name('addNewIcon');
Route::post('deleteIcon', 'HomeController@deleteIcon')->name('deleteIcon');

Route::post('addMarkerIcon', 'AdminController@addMarkerIcon')->name('addMarkerIcon');
Route::get('AZPopulate', 'AdminController@AZPopulate')->name('AZPopulate');

Route::resource('map', 'MapController');

Route::get('help', function ()
{
    return view('help.index');
})->name('help');