<?php

/*
|--------------------------------------------------------------------------
| Api Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get('api/{email}/{apikey}/getmaps', 'ApiController@getMaps')->name('api.getMaps');
Route::post('api/{email}/{apikey}/getmapcode/{map}', 'ApiController@getMapCode')->name('api.getMap');
