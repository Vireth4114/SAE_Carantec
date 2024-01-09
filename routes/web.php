<?php

use App\Http\Controllers\AcnBoatController;
use App\Http\Controllers\AcnMemberController;
use App\Http\Controllers\AcnDivesController;
use App\Http\Controllers\AcnDiveCreationController;
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
    return view("accueil");
})->name("accueil");

Route::get('/dives', function () {
    return AcnDivesController::getDivesValues();
})->name("dives");

Route::get('/dashboard', function () {
    return view('dashboard', ["name" => auth()->user()->MEM_NAME, "surname" => auth()->user()->MEM_SURNAME]);
})->middleware(['auth'])->name('dashboard');

Route::get('/secretary', function () {
    return view('secretary', ["name" => auth()->user()->MEM_NAME, "surname" => auth()->user()->MEM_SURNAME, "function" => auth()->user()->FUN_LABEL]);
})->middleware(['auth'])->middleware('isSecretary')->name("secretary");

Route::get('/suggest', function () {
    return AcnDiveCreationController::getAll();
})->name("suggest");

Route::post('formSuggest', [AcnDiveCreationController::class, 'create']);

require __DIR__.'/auth.php';
