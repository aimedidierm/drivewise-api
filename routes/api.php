<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\FuelPriceController;
use App\Http\Controllers\FuelVehicleController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\JourneyController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\VehicleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to Drivewise API'
    ], Response::HTTP_OK);
});

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::put('/settings', [AuthController::class, 'update'])->middleware('auth:api')->name('settings.update');
Route::get('/settings', function () {
    return response()->json([
        'user' => Auth::user(),
    ], Response::HTTP_OK);
})->middleware('auth:api')->name('settings.details');

Route::group(["prefix" => "admin", "middleware" => ["auth:api", "isAdmin"], "as" => "admin."], function () {
    Route::get('/', [DashboardController::class, 'adminDashboard']);
    Route::apiResource('/driver', DriverController::class)->only('index', 'store', 'show', 'update', 'destroy');
    Route::apiResource('/group', GroupController::class)->only('index', 'store', 'show', 'update', 'destroy');
    Route::apiResource('/vehicle', VehicleController::class)->only('index', 'store', 'show', 'update', 'destroy');
    Route::apiResource('/maintenance', MaintenanceController::class)->only('index', 'store', 'show', 'update', 'destroy');
    Route::apiResource('/prices', FuelPriceController::class)->only('index', 'update');
    Route::get('/issues', [IssueController::class, 'index'])->name('issues');
    Route::apiResource('/fuel', FuelVehicleController::class)->only('index', 'show');
    Route::get('/report', [FuelVehicleController::class, 'report']);
});

Route::group(["prefix" => "driver", "middleware" => ["auth:api", "isDriver"], "as" => "driver."], function () {
    Route::get('/', function () {
        return response()->json([
            'message' => 'Welcome Driver',
        ], Response::HTTP_OK);
    });
    // Route::get('/notifications', 'NotificationController@index');
    Route::get('/', [DashboardController::class, 'driverDashboard']);
    Route::apiResource('/issues', IssueController::class)->only('index', 'store', 'show', 'update', 'destroy');
    Route::apiResource('/journey', JourneyController::class)->only('index', 'store', 'show');
    Route::apiResource('/fuel', FuelVehicleController::class)->only('index', 'store', 'show');
    Route::apiResource('/prices', FuelPriceController::class)->only('index');
    Route::get('/vehicle', function () {
        return response()->json([
            'vehicle' => Auth::user()->vehicle->load('group', 'user'),
        ], Response::HTTP_OK);
    });
    Route::get('/report', [FuelVehicleController::class, 'report']);
});
