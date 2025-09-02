<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\RumahSakitController;
use App\Http\Controllers\UserController;

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

// Routes untuk halaman publik (login)
Route::get('/', [AuthController::class, 'index'])->name('/');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Routes untuk user yang harus login
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // User management
    Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::post('/user', [UserController::class, 'store'])->name('user.store');
    Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');

    // Rumah sakit
    Route::get('/rumahsakit', [RumahSakitController::class, 'index'])->name('rumahsakit');
    Route::post('/rumahsakit', [RumahSakitController::class, 'store'])->name('rumahsakit.store');
    Route::put('/rumahsakit/{rs}', [RumahSakitController::class, 'update'])->name('rumahsakit.update');
    Route::delete('/rumahsakit/{rs}', [RumahSakitController::class, 'destroy'])->name('rumahsakit.destroy');

    // Pasien
    Route::get('/pasien', [PasienController::class, 'index'])->name('pasien');
    Route::post('/pasien', [PasienController::class, 'store'])->name('pasien.store');
    Route::put('/pasien/{pasien}', [PasienController::class, 'update'])->name('pasien.update');
    Route::delete('/pasien/{pasien}', [PasienController::class, 'destroy'])->name('pasien.destroy');
});
