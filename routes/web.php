<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Route untuk Halaman Detail Produk
Route::get('/product/{id}', function ($id) {
    
    $product = \App\Models\Product::find($id);
    
    if (!$product) {
        abort(404); 
    }

    return view('product-detail', compact('product'));
})->name('product.show');

use App\Http\Controllers\AuthController;

// Hanya bisa diakses oleh tamu (belum login)
Route::middleware('guest')->group(function () {
    // Rute Menampilkan UI
    Route::get('/login', function () { return view('auth.login'); })->name('login');
    Route::get('/register', function () { return view('auth.register'); })->name('register');
    
    // Rute Memproses Data Form (Mengarah ke AuthController)
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Hanya bisa diakses oleh yang sudah login
Route::middleware('auth')->group(function () {
    // Rute Logout harus menggunakan POST demi keamanan (mencegah CSRF logout attack)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/catalog', function () {
    return view('catalog');
});

Route::middleware('auth')->group(function () {
    // Tambahkan baris ini
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});