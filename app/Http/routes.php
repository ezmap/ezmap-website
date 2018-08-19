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


Route::get('/', "GeneralController@index")->middleware(['guest', 'pjax'])->name('index');
Route::get('feedback', function () {
  return view('feedback');
});
Route::post('feedback', 'GeneralController@feedback')->name('feedback');

Route:: get('test', function () {
  return view('test');
})->middleware('admin');

Route::auth();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('admin', 'AdminController@index');
Route::get('stealth/{userid}', "AdminController@stealth")->name('stealth');
Route::get('unstealth', "AdminController@unstealth")->name('unstealth');
Route::post('admin', 'SnazzyMapsController@populateThemes')->name('populateThemes')->middleware('admin');

Route::post('addNewIcon', 'HomeController@addNewIcon')->name('addNewIcon');
Route::post('deleteIcon', 'HomeController@deleteIcon')->name('deleteIcon');
Route::post('geocodeAddress', 'GeneralController@geocodeAddress')->name('geocode');

Route::post('addMarkerIcon', 'AdminController@addMarkerIcon')->name('addMarkerIcon');

Route::get('AZPopulate', 'AdminController@AZPopulate')->name('AZPopulate');

Route::resource('map', 'MapController');
Route::post('map/undelete/{id}', 'MapController@undelete')->name('map.undelete');

Route::get('lang/{lang}', 'GeneralController@changeLanguage')->name('lang');

Route::get('help', function () {
  return view('help.index');
})->name('help');

Route::get('twomapautoupdatetest', function () {
  return view('twomapsautoupdate');
});

Route::post('renewapikey', 'HomeController@renewApiKey')->name('renewapikey');

Route::get('api', function(){
  return view('api.about');
})->name('api');