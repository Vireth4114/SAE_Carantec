<?php

use App\Http\Controllers\AcnDivesController;
use App\Http\Controllers\AcnDiveCreationController;
use App\Http\Controllers\AcnDirectorController;
use App\Http\Controllers\AcnMemberController;
use App\Http\Controllers\AcnRegisteredController;
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

Route::get('/panel/director/addMember/{diveId}', function ($diveId)  {
    return AcnDirectorController::addDiveMember($diveId);
})->name("addMember");

Route::post('/panel/director/addMemberToDiveForm', function (Request $request) {
    AcnRegisteredController::create($request->numMember, $request->numDive);
    return redirect()->route('addMember', ['diveId' => $request -> numDive] );
})->name("addMemberToDiveForm");


Route::post('diveCreationForm', [AcnDiveCreationController::class, 'create'])->name("diveCreationForm");

require __DIR__.'/auth.php';
