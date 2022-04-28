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

        Route::get('repairs/{repair}/repair-done', [RepairController::class, 'getRepairDone']);
        Route::get('repairs/{repair}/repair-cancel', [RepairController::class, 'getRepairCancel']);
        Route::get('repairs/{repair}/repair-approve', [RepairController::class, 'getRepairApprove']);
        Route::apiResource('repairs', RepairController::class);

        Route::get('repair-services', [RepairController::class, 'getRepairServices']);
        Route::get('repair-services/{repair_service}', [RepairController::class, 'getRepairServicesShow']);
        Route::put('repair-services/{repair_service}', [RepairController::class, 'postAssignMechanics']);

        Route::get('repair-mechanics', [RepairController::class, 'getRepairServicesByMechanic']);
        Route::get('repair-mechanics/{repair_mechanic}', [RepairController::class, 'getRepairServicesByMechanicShow']);
        Route::put('repair-mechanics/{repair_mechanic}', [RepairController::class, 'postDoneService']);

        Route::get('repair-inspects', [RepairController::class, 'getInspect']);
        Route::get('repair-inspects/{repair_inspect}', [RepairController::class, 'getInspectShow']);
        Route::put('repair-inspects/{repair_inspect}', [RepairController::class, 'postComplaineService']);
    });
});