<?php

use App\Http\Controllers\AcnDivesController;
use App\Http\Controllers\AcnDiveCreationController;
use App\Http\Controllers\AcnGroupsMakingController;
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

Route::post('diveCreationForm', [AcnDiveCreationController::class, 'create'])->name("diveCreationForm");

Route::get('/groupsMaking', function () {
    return AcnGroupsMakingController::getAll();
})->middleware(['auth'])->middleware('isDirector')->name("groupsMaking");

Route::get('/addGroup', function () {
    return AcnGroupsMakingController::add_group();
})->middleware(['auth'])->middleware('isDirector')->name("addGroup");

Route::get('removeFromGroup', [AcnGroupsMakingController::class, 'remove_member'])->middleware(['auth'])->middleware('isDirector')->name("removeFromGroup");

Route::post('addMemberToGroup', [AcnGroupsMakingController::class, 'add'])->middleware(['auth'])->middleware('isDirector')->name("addMemberToGroup");

require __DIR__.'/auth.php';
