<?php

use App\Http\Controllers\Api\AcnBoatController;
use App\Http\Controllers\Api\AcnFunctionController;
use App\Http\Controllers\Api\AcnMemberController;
use App\Http\Controllers\Api\AcnPeriodController;
use App\Http\Controllers\Api\AcnPrerogativeController;
use App\Http\Controllers\Api\AcnSiteController;
use App\Models\web\AcnDives;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::apiResource("sites", AcnSiteController::class);
Route::apiResource("boats", AcnBoatController::class);
Route::apiResource("prerogatives", AcnPrerogativeController::class);
Route::apiResource("functions", AcnFunctionController::class);
Route::apiResource("periods", AcnPeriodController::class);
Route::apiResource("members", AcnMemberController::class);
Route::post("/members/{memberId}/prerogative/{prerogativeId}", function(Request $request, $memberId, $prerogativeId) {
    return AcnMemberController::storeMemberPrerogative($memberId, $prerogativeId);
});
Route::delete("/members/{memberId}/prerogative/{prerogativeId}", function(Request $request, $memberId, $prerogativeId) {
    return AcnMemberController::deleteMemberPrerogative($memberId, $prerogativeId);
});
Route::post("/members/{memberId}/function/{functionId}", function(Request $request, $memberId, $functionId) {
    return AcnMemberController::storeMemberFunction($memberId, $functionId);
});
Route::delete("/members/{memberId}/function/{functionId}", function(Request $request, $memberId, $functionId) {
    return AcnMemberController::deleteMemberFunction($memberId, $functionId);
});