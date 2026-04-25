<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Http\Request;


Route::get('/', [HomeController::class, 'index'])->middleware('guest');
Route::get('/login', [AuthController::class, 'ShowLogin'])->name('login');
Route::post('/login', [AuthController::class, 'Login']);
Route::get('/register', [AuthController::class, 'ShowRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/landingpage', [HomeController::class, 'index'])->name('landingpage');

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

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Link verifikasi dikirim ulang!');
    })->middleware(['auth', 'throttle:6,1'])->name('verification.send');
    
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
        return view('SuperAdmin.Dashboard');
    });
});

Route::middleware(['auth', 'role:seller'])->group(function () {
    Route::get('/dashboard/seller', function () {
        return view('Seller.Dashboard');
    });
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard/user', function () {
        return view('User.Dashboard');
    });
});
