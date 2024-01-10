<?php

use App\Http\Controllers\AcnBoatController;
use App\Http\Controllers\AcnDivesController;
use App\Http\Controllers\AcnDiveCreationController;
use App\Http\Controllers\AcnMemberController;
use App\Http\Controllers\AcnSiteController;
use App\Http\Controllers\ManagerPanelController;
use Illuminate\Http\Request;
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

Route::get('/panel/manager', function () {
    return ManagerPanelController::displayManagerPanel();
})->middleware(['auth'])->middleware('isManager')->name("managerPanel");

Route::get('/create/boat', function () {
    return view("manager/createBoat");
})->middleware(['auth'])->middleware('isManager')->name("boatCreate");

Route::post('/create/boat', function (Request $request) {
    return AcnBoatController::create($request);
})->middleware(['auth'])->middleware('isManager')->name("boatCreateForm");

Route::get('/update/boat/{boatId}', function ($boatId) {
    return AcnBoatController::getBoatUpdateView($boatId);
})->middleware(['auth'])->middleware('isManager')->name("boatUpdate");

Route::patch('/update/boat/{boatId}', function (Request $request, $boatId) {
    return AcnBoatController::update($request, $boatId);
})->middleware(['auth'])->middleware('isManager')->name("boatUpdateForm");

Route::delete('/delete/boat/{boatId}', function ($boatId) {
    AcnBoatController::delete($boatId);
    return back();
})->middleware(['auth'])->middleware('isManager')->name("boatDelete");

Route::get('/create/site', function () {
    return view("manager/createSite");
})->middleware(['auth'])->middleware('isManager')->name("siteCreate");

Route::post('/create/site', function (Request $request) {
    return AcnSiteController::create($request);
})->middleware(['auth'])->middleware('isManager')->name("siteCreateForm");

Route::get('/update/site/{siteId}', function ($siteId) {
    return AcnSiteController::getSiteUpdateView($siteId);
})->middleware(['auth'])->middleware('isManager')->name("siteUpdate");

Route::patch('/update/site/{siteId}', function (Request $request, $siteId) {
    return AcnSiteController::update($request, $siteId);
})->middleware(['auth'])->middleware('isManager')->name("siteUpdateForm");

Route::delete('/delete/site/{siteId}', function ($siteId) {
    AcnSiteController::delete($siteId);
    return back();
})->middleware(['auth'])->middleware('isManager')->name("siteDelete");

Route::patch('/update/user/roles/{userId}', function (Request $request, $userId) {
    return AcnMemberController::updateRolesMember($request, $userId);
})->middleware(['auth'])->middleware('isManager')->name("userRolesUpdate");

Route::post('diveCreationForm', [AcnDiveCreationController::class, 'create'])->name("diveCreationForm");

require __DIR__.'/auth.php';
