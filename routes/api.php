<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ParkingController;
use App\Http\Controllers\VehicleController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Auth::routes();

Route::resource('clients', ClientController::class);
Route::resource('vehicles', VehicleController::class);
Route::resource('parking', ParkingController::class);

// More
Route::get('monthly-pension-payments', [ParkingController::class, 'monthlyReport']);


