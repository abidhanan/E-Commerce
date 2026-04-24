<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/login', [AuthController::class, 'ShowLogin'])->name('login');
Route::post('/login', [AuthController::class, 'Login']);
Route::get('/register', [AuthController::class, 'ShowRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('DashboardAdmin');

Route::prefix('admin')->group(function () {

    Route::middleware('guest')->controller(AuthController::class)->group(function () {
        // Route::get('/login', 'ShowLogin')->name('login');
        // Route::post('/login', 'login');
        // Route::get('/register', 'ShowRegister')->name('register');
        // Route::post('/register', 'register');
    });

    Route::middleware(['auth', 'CheckRole:admin'])->group(function () {

        Route::controller(DashboardController::class)->group(function () {
            Route::get('/dashboard', 'index')->name('dashboard');
        });

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});