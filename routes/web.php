<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthViewController;
use App\Http\Controllers\CRUDUserController;
use App\Http\Controllers\SessionController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('login');
});


Route::get('/login', [AuthViewController::class, 'login'])->name('login');
Route::post('/login', [SessionController::class, 'login'])->name('login.post');
// Route::get('/register', [AuthViewController::class, 'register'])->name('register');
// Route::post('/logout', [SessionController::class, 'logout']);


Route::middleware(['auth'])->group(function () {
    Route::get('/user', [CRUDUserController::class, 'index']);
    Route::get('/users', [CRUDUserController::class, 'index']);
    Route::post('/users', [CRUDUserController::class, 'store']);
    Route::get('/users/{id}/edit', [CRUDUserController::class, 'edit']);
    Route::put('/users/{id}', [CRUDUserController::class, 'update']);
    Route::delete('/users/{id}', [CRUDUserController::class, 'destroy']);
});




// Route::post('/session', [SessionController::class, 'login']);
// Route::post('/register', [SessionController::class, 'register']);
Route::post('/logout', [SessionController::class, 'logout']);

// Route::get('/users', [UserController::class, 'index'])->name('users.index');
// Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
// Route::post('/users', [UserController::class, 'store'])->name('users.store');
// Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
// Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
// Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');