<?php

use App\Http\Controllers\Api\ConsultantsController;
use App\Http\Controllers\Api\CounselorsController;
use App\Http\Controllers\Api\FinancePlanController;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('/v1/counselors', CounselorsController::class);
Route::post('/v1/consultants', ConsultantsController::class);
Route::post('/v1/finance-plan', FinancePlanController::class);
