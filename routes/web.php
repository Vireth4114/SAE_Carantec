<?php

use App\Http\Controllers\AcnDiveModifyController;
use App\Http\Controllers\AcnDiveCreationController;

use App\Http\Controllers\AcnBoatController;
use App\Http\Controllers\AcnDivesController;
use App\Http\Controllers\AcnSiteController;
use App\Http\Controllers\ManagerPanelController;
use App\Http\Controllers\AcnDirectorController;
use App\Http\Controllers\AcnMemberController;
use App\Http\Controllers\AcnRegisteredController;
use Illuminate\Http\Request;

use App\Models\web\AcnMember;


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

Route::get('/dives/informations/{id}', function ($id){
    return AcnDivesController::getAllDiveInformation($id);
})->name("dives_informations");

Route::post('/dives/register', function (Request $request){
    return AcnDivesController::register($request);
})->name("membersDivesRegister");

Route::post('/dives/unregister', function (Request $request){
    return AcnDivesController::unregister($request);
})->name("membersDivesUnregister");

Route::get('/dashboard', function () {
    return view('dashboard', ["name" => auth()->user()->MEM_NAME, "surname" => auth()->user()->MEM_SURNAME]);
})->middleware(['auth'])->name('dashboard');

Route::get('/secretary', function () {
    return view('secretary', ["name" => auth()->user()->MEM_NAME, "surname" => auth()->user()->MEM_SURNAME, "function" => auth()->user()->FUN_LABEL]);
})->middleware(['auth'])->middleware('isSecretary')->name("secretary");

Route::get('/diveCreation', function () {
    return AcnDiveCreationController::getAll();
})->middleware(['auth'])->middleware('isManager')->name("diveCreation");


Route::get('/diveModify/{diveId}', function ($diveId) {
    return AcnDiveModifyController::getAll($diveId);
})->middleware(['auth'])->middleware('isDirectorOrManager')->name("diveModify");

Route::get('/panel/director/redirectDiveModify/{diveId}', function($diveId) {
    if (AcnMember::isUserManager(auth()->user()->MEM_NUM_MEMBER)) {
        return redirect(route('dives'));
    } else {
        return redirect(route('diveInformation', $diveId));
    }
})->middleware(['auth'])->middleware('isDirectorOrManager')->name("redirectDiveModify");

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

Route::get('/panel/director/addMember/{diveId}', function ($diveId)  {
    return AcnDirectorController::addDiveMember($diveId);
})->name("addMember");

Route::post('/panel/director/addMemberToDiveForm', function (Request $request) {
    AcnRegisteredController::create($request->numMember, $request->numDive);
    return redirect()->route('addMember', ['diveId' => $request -> numDive] );
})->name("addMemberToDiveForm");

Route::post('/panel/director/removeDirectorFromDiveForm', function (Request $request) {
    AcnRegisteredController::delete($request->numMember, $request->numDive);
    return redirect()->route('addMember', ['diveId' => $request -> numDive] );
})->name("removeDirectorFromDiveForm");

Route::get('/panel/director/diveInformation/{diveId}', function ($diveId)  {
    return AcnDirectorController::diveInformation($diveId);
})->name("diveInformation");

Route::post('/panel/director/removeMemberFromDiveForm', function (Request $request) {
    AcnRegisteredController::delete($request->numMember, $request->numDive);
    return redirect()->route('diveInformation', ['diveId' => $request -> numDive] );
})->name("removeMemberFromDiveForm");

Route::get('/members', function () {
    return view('members', ["name" => auth()->user()->MEM_NAME, "surname" => auth()->user()->MEM_SURNAME, "function" => auth()->user()->FUN_LABEL]);
})->middleware(['auth'])->middleware('isSecretary')->name("members");

Route::get('/members/registration', function () {
    return AcnMemberController::registerForm();
})->middleware(['auth'])->middleware('isSecretary')->name("member_registration");

Route::get('/profil', function () {
    return AcnMemberController::getProfilePage();
})->middleware(['auth'])->name("profil_page");

Route::post('member/registration/validation', [AcnMemberController::class, 'register'])->name('register_member');

Route::get('/members/modification/{mem_num_member}', function ($mem_num_member) {
    return AcnMemberController::modifyForm($mem_num_member);
})->middleware(['auth'])->middleware('isSecretary')->name("member_modification");

Route::get('/members/status/{mem_num_member}', function ($mem_num_member) {
    return AcnMemberController::updateStatus($mem_num_member);
})->middleware(['auth'])->middleware('isSecretary')->name("member_status");

Route::get('/panel/director/myDirectorDives', function() {
    return AcnDirectorController::myDirectorDives();
})->middleware(['auth'])->middleware('isDirector')->name("myDirectorDives");


Route::post('member/modification/validation', [AcnMemberController::class, 'modify'])->name('modify_member');

Route::patch('/update/user/roles/{userId}', function (Request $request, $userId) {
    return AcnMemberController::updateRolesMember($request, $userId);
})->middleware(['auth'])->middleware('isManager')->name("userRolesUpdate");

Route::get('/diveReport', function () {
    return AcnDivesController::getMemberDivesReport();
})->name("diveReport");

Route::get('/managerDivesReport', function () {
    return AcnDivesController::getAllDivesReport();
})->name("managerDivesReport");


Route::post('diveCreationForm', [AcnDiveCreationController::class, 'create'])->name("diveCreationForm");


Route::post('diveModifyForm', [AcnDiveModifyController::class, 'modify'])->name('diveModifyForm');

Route::get('/groupsMaking', function () {
    return AcnGroupsMakingController::getAll("");
})->middleware(['auth'])->middleware('isDirector')->name("groupsMaking");

Route::get('validateGroup/{diveId}', [AcnGroupsMakingController::class, 'validateButton'])->middleware(['auth'])->middleware('isDirector')->name("validateGroup");

Route::get('automaticGroup/{diveId}', [AcnGroupsMakingController::class, 'automatic'])->middleware(['auth'])->middleware('isDirector')->name("automaticGroup");

Route::get('removeFromGroup', [AcnGroupsMakingController::class, 'removeMember'])->middleware(['auth'])->middleware('isDirector')->name("removeFromGroup");

Route::post('addMemberToGroup', [AcnGroupsMakingController::class, 'add'])->middleware(['auth'])->middleware('isDirector')->name("addMemberToGroup");

Route::post('addGroup', [AcnGroupsMakingController::class, 'addGroup'])->middleware(['auth'])->middleware('isDirector')->name("addGroup");

require __DIR__.'/auth.php';
