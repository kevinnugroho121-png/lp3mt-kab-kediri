<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\JadwalController; // <--- Pastikan ini ada

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// === RUTE PUBLIK ===
Route::post('/login', [AuthController::class, 'login']);

// === RUTE PRIVAT (Harus Login / Punya Token) ===
Route::middleware('auth:sanctum')->group(function () {
    
    // 1. Logout
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // 2. Profil Saya (PENTING! Jangan dihapus)
    Route::get('/profile', [AuthController::class, 'profile']);

    // 3. Jadwal Latihan (YANG BARU)
    Route::get('/jadwal', [JadwalController::class, 'index']);

});