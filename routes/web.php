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
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Route::get('/login', function () {
//     return view('auth.login');
// })->name('login');

// Route::get('/register', function () {
//     return view('auth.register');
// })->name('register');

// // Route untuk Halaman Detail Produk
// Route::get('/product/{id}', function ($id) {
//     // Nanti teman backend-mu akan mengganti ini dengan query database sungguhan.
//     // Sementara ini, kita pura-pura mencari produk berdasarkan ID.
//     $product = \App\Models\Product::find($id);
    
//     if (!$product) {
//         abort(404); // Jika produk tidak ada, tampilkan halaman 404
//     }

//     return view('product-detail', compact('product'));
// })->name('product.show');

// Tambahkan ini di routes/web.php
Route::get('/seller/info', function () {
    return view('seller-info');
});

Route::get('/seller/register', function () {
    return view('auth.seller-register'); 
});