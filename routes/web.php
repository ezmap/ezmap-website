<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\SnazzyMapsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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


Route::get('/', [GeneralController::class, "index"])->middleware(['guest'])->name('index');
Route::get('feedback', function () {
  return view('feedback');
});
Route::post('feedback', [GeneralController::class, "feedback"])->name('feedback');

Route:: get('test', function () {
  return view('test');
})->middleware('admin');

//Route::auth();
Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('/logout', [LoginController::class, 'logout']);

Route::get('admin', [AdminController::class, 'index'])->middleware('admin');
Route::get('stealth/{userid}', [AdminController::class, "stealth"])->name('stealth')->middleware('admin');
Route::get('unstealth', [AdminController::class, "unstealth"])->name('unstealth')->middleware('admin');
Route::delete('admin/deleteuser/{userId}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser')->middleware('admin');
Route::post('admin', [SnazzyMapsController::class, 'populateThemes'])->name('populateThemes')->middleware('admin');

Route::post('addNewIcon', [HomeController::class, 'addNewIcon'])->name('addNewIcon')->middleware('auth');
Route::post('deleteIcon', [HomeController::class, 'deleteIcon'])->name('deleteIcon')->middleware('auth');
Route::post('geocodeAddress', [GeneralController::class, 'geocodeAddress'])->name('geocode');

Route::post('addMarkerIcon', [AdminController::class, 'addMarkerIcon'])->name('addMarkerIcon')->middleware('admin');

Route::get('AZPopulate', [AdminController::class, 'AZPopulate'])->name('AZPopulate')->middleware('admin');

Route::post('map/undelete/{id}', [MapController::class, 'undelete'])->name('map.undelete')->middleware('auth');
Route::get('map/image/download/{map}', [MapController::class, 'download'])->name('map.download')->middleware('auth');
Route::get('map/image/{map}', [MapController::class, 'image'])->name('map.image')->middleware('auth');
Route::get('map/kml/{map}', [MapController::class, 'exportKml'])->name('map.kml')->middleware('auth');
Route::get('map/kmz/{map}', [MapController::class, 'exportKmz'])->name('map.kmz')->middleware('auth');
Route::resource('map', MapController::class)->middleware('auth')->except(['show']);
Route::get('map/{map}', [MapController::class, 'show'])->name('map.show');

Route::get('lang/{lang}', [GeneralController::class, 'changeLanguage'])->name('lang');

Route::get('help', function () {
  return view('help.index');
})->name('help');

Route::get('twomapautoupdatetest', function () {
  return view('twomapsautoupdate');
});

Route::post('renewapikey', [HomeController::class, 'renewApiKey'])->name('renewapikey')->middleware('auth');
Route::delete('deleteaccount', [HomeController::class, 'deleteAccount'])->name('deleteaccount')->middleware('auth');

Route::get('api', function () {
  return view('api.about');
})->name('api');

// Blog routes
Route::get('blog', [\App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
Route::get('blog/mapkit-alternative', [\App\Http\Controllers\BlogController::class, 'mapkitAlternative'])->name('blog.mapkit-alternative');
