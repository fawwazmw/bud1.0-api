<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SensorDataController; // <-- Tambahkan import untuk controller Anda

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

// Route default dari Laravel Sanctum untuk mendapatkan informasi user terotentikasi
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Endpoint untuk menerima data sensor dari ESP32 Anda
Route::post('/sensor-data', [SensorDataController::class, 'store'])->name('sensor.data.store');
