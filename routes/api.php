<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

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


//auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);




Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::middleware('auth:sanctum')->group(function () {
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});


// //cek data user
// Route::middleware(['auth:sanctum'])->group(function () {
    // Route::get('/users', [UserController::class, 'index'])->middleware('role:admin,dosen,mahasiswa');
// });

// //buat user
// Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
// });




// //get mahasiswas and dosens (admin and dosen only)
// Route::middleware(['auth:sanctum', 'role:admin,dosen'])->group(function () {
//     Route::get('/mahasiswas', [UserController::class, 'getMahasiswas']);
//     Route::get('/dosens', [UserController::class, 'getDosens']);
// });

// //update profile (for all users)
// Route::middleware('auth:sanctum')->put('/me', [UserController::class, 'updateProfile']);

// //search user (admin and dosen only)
// Route::middleware(['auth:sanctum', 'role:admin,dosen'])->get('/users/search', [UserController::class, 'searchUser']);



Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);    
    Route::put('/update-role', [AuthController::class, 'updateRole']);
});

// //ubah password
// Route::put('/change-password', [AuthController::class, 'changePassword'])->middleware('auth:sanctum');

// //reset password (admin only)
// Route::put('/reset-password', [AuthController::class, 'resetPassword'])->middleware(['auth:sanctum', 'role:admin']);
