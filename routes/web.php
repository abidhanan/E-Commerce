<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Http\Request;
use App\Http\Controllers\MainController\ProfileController;
use App\Http\Controllers\MainController\HomeController ;

Route::get('/', [HomeController::class, 'index'])->middleware('guest');
Route::get('/login', [AuthController::class, 'ShowLogin'])->name('login');
Route::post('/login', [AuthController::class, 'Login']);
Route::get('/register', [AuthController::class, 'ShowRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/landingpage', [HomeController::class, 'index'])->name('landingpage');
Route::get('product.show', [HomeController::class, 'showProduct'])->name('product.show');
Route::get('/profil', [ProfileController::class, 'showProfile'])->name('profile.show');

Route::get('/password/reset', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/password/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email')->middleware('throttle:5,1');
Route::get('/password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [AuthController::class, 'reset'])->name('password.update')->middleware('throttle:5,1');
/*
|--------------------------------------------------------------------------
| EMAIL VERIFICATION 
|--------------------------------------------------------------------------
*/
Route::get('/email/verify', [AuthController::class, 'showVerify'])
    ->middleware('auth')
    ->name('verification.notice');
    
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->name('verification.verify');

Route::get('/email/verification-notification', [AuthController::class, 'resendVerification'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');
    
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('User.home');
        });
    });

Route::prefix('admin')->group(function () {

    Route::middleware('guest')->controller(AuthController::class)->group(function () {  
    });
    Route::middleware(['auth', 'role:admin'])->group(function () {

        Route::controller(DashboardController::class)->group(function () {
            Route::get('/AdminDashboard', 'index')->name('AdminDashboard');
        });

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});

Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/dashboard/superadmin', function () {
        return view('SuperAdmin.Dashboard.index');
    });
});

Route::middleware(['auth', 'role:seller'])->group(function () {
    Route::get('/dashboard/seller', function () {
        return view('Seller.Dashboard');
    });
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard/user', function () {
        return view('User.Home');
    });
});
