<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

// Login & Register (tanpa middleware)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Logout + admin-only actions
Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // Admin full CRUD access
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
    });

    // Dosen dan Mahasiswa bisa update profil sendiri
    Route::put('/me', [UserController::class, 'updateProfile']);

    // Dosen bisa melihat semua dosen dan mahasiswa
    Route::middleware(['role:dosen'])->group(function () {
        Route::get('/dosens', [UserController::class, 'getDosens']);
        Route::get('/mahasiswas', [UserController::class, 'getMahasiswas']);
    });

    // Mahasiswa dan dosen bisa melihat dirinya sendiri
    Route::get('/me', function () {
        return response()->json(auth()->user());
    });
});
