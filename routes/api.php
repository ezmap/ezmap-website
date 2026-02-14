<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::post('{email}/{apikey}/getmaps', [ApiController::class, 'getMaps'])->name('api.getMaps');
Route::post('{email}/{apikey}/getmapcode/{map}', [ApiController::class, 'getMapCode'])->name('api.getMap');
