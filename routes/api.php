<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
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

Route::group(["prefix" => "admin", "middleware" => ["auth:api", "isAdmin"], "as" => "admin."], function () {
    Route::get('/', function () {
        return response()->json([
            'message' => 'Welcome Admin',
        ], Response::HTTP_OK);
    });
});

Route::group(["prefix" => "driver", "middleware" => ["auth:api", "isDriver"], "as" => "driver."], function () {
    Route::get('/', function () {
        return response()->json([
            'message' => 'Welcome Driver',
        ], Response::HTTP_OK);
    });
});
