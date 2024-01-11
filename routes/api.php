<?php

use App\Http\Controllers\Api\AcnBoatController;
use App\Http\Controllers\Api\AcnPrerogativeController;
use App\Http\Controllers\Api\AcnSiteController;
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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::apiResource("sites", AcnSiteController::class);
Route::apiResource("boats", AcnBoatController::class);
Route::apiResource("prerogatives", AcnPrerogativeController::class);
