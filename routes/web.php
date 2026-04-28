<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController\DashboardController;
use App\Http\Controllers\MainController\ProfileController;
use App\Http\Controllers\MainController\HomeController ;
use App\Http\Controllers\UserController\UserController;
use App\Http\Controllers\ProductController\CategoryController;
use App\Http\Controllers\ProductController\CollectionsController;
use App\Http\Controllers\BlogController\BlogController;
use App\Http\Controllers\BlogController\BlogCategoryController;
use App\Http\Controllers\BlogController\TagController;


Route::get('/', [HomeController::class, 'index'])->middleware('guest');
Route::get('/login', [AuthController::class, 'ShowLogin'])->name('login');
Route::post('/login', [AuthController::class, 'Login']);
Route::get('/register', [AuthController::class, 'ShowRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/landingpage', [HomeController::class, 'index'])->name('landingpage');
Route::get('/product/{id}', [HomeController::class, 'showProduct'])->name('product.show');
Route::get('/profil', [ProfileController::class, 'showProfile'])->name('profile');

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

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::middleware('role:superadmin')
        ->prefix('superadmin')
        ->name('superadmin.')
        ->group(function () {

            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
            Route::post('/users', [UserController::class, 'store'])->name('users.store');
            Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
            Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
            Route::get('/users/loglogin', [UserController::class, 'loglogin'])->name('users.loglogin');

            Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
            Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
            Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
            Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
            Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
            Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
            Route::get('/collections', [CollectionsController::class, 'index'])->name('collections.index');
            Route::get('/collections/create', [CollectionsController::class, 'create'])->name('collections.create');
            Route::post('/collections', [CollectionsController::class, 'store'])->name('collections.store');
            Route::get('/collections/{collection}/edit', [CollectionsController::class, 'edit'])->name('collections.edit');
            Route::put('/collections/{collection}', [CollectionsController::class, 'update'])->name('collections.update');
            Route::delete('/collections/{collection}', [CollectionsController::class, 'destroy'])->name('collections.destroy');

            Route::resource('blog-categories', BlogCategoryController::class);
            Route::resource('tags', TagController::class);
            Route::resource('blogs', BlogController::class);
            Route::get('blogs/{blog}/publish', [BlogController::class, 'relaseblog'])->name('blogs.publish');

    });
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard/admin', function () {
        return view('Admin.Dashboard.index');
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
