<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admins\CarController;
use App\Http\Controllers\Api\Admins\LoginController;
use App\Http\Controllers\Api\Admins\ServiceController;
use App\Http\Controllers\Api\Admins\CustomerController;
use App\Http\Controllers\Api\Admins\MechanicController;
use App\Http\Controllers\Api\Admins\RepairController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('admin')->group(function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::middleware('auth:api')->group(function () {
        Route::apiResource('customers', CustomerController::class);
        Route::apiResource('mechanics', MechanicController::class);
        Route::apiResource('cars', CarController::class);
        Route::apiResource('services', ServiceController::class);
        Route::apiResource('repairs', RepairController::class);
        Route::get('repairs/repair-services', [RepairController::class, 'getRepairServices']);
        Route::post('repairs/assign-mechanics', [RepairController::class, 'postAssignMechanics']);
        Route::get('repairs/repair-services-mechanic', [RepairController::class, 'getRepairServicesByMechanic']);
        Route::post('repairs/done-service', [RepairController::class, 'postDoneService']);
        Route::post('repairs/complaine-service', [RepairController::class, 'postComplaineService']);
        Route::get('repairs/repair-done', [RepairController::class, 'getRepairDone']);
        Route::get('repairs/repair-cancel', [RepairController::class, 'getRepairCancel']);
    });
});