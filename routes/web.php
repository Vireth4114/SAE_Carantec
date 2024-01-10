<?php

use App\Http\Controllers\AcnBoatController;
use App\Http\Controllers\AcnDivesController;
use App\Http\Controllers\AcnDiveCreationController;
use App\Http\Controllers\AcnSiteController;
use App\Http\Controllers\DirectorPanelController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view("welcome");
})->name("welcome");

Route::get('/dives', function () {
    return AcnDivesController::getAllDivesValues();
})->middleware(['auth'])->name("dives");

Route::get('/dashboard', function () {
    return view('dashboard', ["name" => auth()->user()->MEM_NAME, "surname" => auth()->user()->MEM_SURNAME]);
})->middleware(['auth'])->name('dashboard');

Route::get('/secretary', function () {
    return view('secretary', ["name" => auth()->user()->MEM_NAME, "surname" => auth()->user()->MEM_SURNAME, "function" => auth()->user()->FUN_LABEL]);
})->middleware(['auth'])->middleware('isSecretary')->name("secretary");

Route::get('/diveCreation', function () {
    return AcnDiveCreationController::getAll();
})->middleware(['auth'])->middleware('isManager')->name("diveCreation");

Route::get('/panel/director', function () {
    return DirectorPanelController::displayDirectorPanel();
})->middleware(['auth'])->middleware('isManager')->name("directorPanel");

Route::get('/create/boat', function () {
    //return AcnDiveCreationController::getAll();
})->middleware(['auth'])->middleware('isManager')->name("boatCreate");

Route::get('/update/boat/{boatId}', function ($boatId) {
    return AcnBoatController::getBoatUpdateView($boatId);
})->middleware(['auth'])->middleware('isManager')->name("boatUpdate");

Route::delete('/delete/boat/{boatId}', function ($boatId) {
    AcnBoatController::delete($boatId);
    return back();
})->middleware(['auth'])->middleware('isManager')->name("boatDelete");

Route::get('/create/site', function () {
    //return AcnDiveCreationController::getAll();
})->middleware(['auth'])->middleware('isManager')->name("siteCreate");

Route::get('/update/site/{siteId}', function ($siteId) {
    return AcnSiteController::getSiteUpdateView($siteId);
})->middleware(['auth'])->middleware('isManager')->name("siteUpdate");

Route::delete('/delete/site/{siteId}', function ($siteId) {
    AcnSiteController::delete($siteId);
    return back();
})->middleware(['auth'])->middleware('isManager')->name("siteDelete");

Route::post('diveCreationForm', [AcnDiveCreationController::class, 'create'])->name("diveCreationForm");

require __DIR__.'/auth.php';
