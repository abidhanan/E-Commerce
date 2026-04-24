<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Memproses form Register
    public function register(Request $request)
    {
        // 1. Validasi Input Ketat (Tambahkan field baru)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'dob' => 'required|date',
            'gender' => 'required|in:man,women',
        ]);

        // 2. Simpan ke Database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->country_code . $request->phone, // Menggabungkan +62 dengan nomor
            'dob' => $request->dob,
            'gender' => $request->gender,
        ]);

        // 3. Login & Redirect
        Auth::login($user);
        return redirect('/')->with('success', 'Registrasi berhasil! Selamat datang di Clothique.');
    }

    // Memproses form Login
    public function login(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Coba cocokkan data dengan Database
        if (Auth::attempt($credentials)) {
            // Jika berhasil: Putar ulang sesi untuk mencegah serangan 'Session Fixation'
            $request->session()->regenerate();
            
            return redirect()->intended('/')->with('success', 'Berhasil masuk.');
        }

        // Jika gagal: Kembalikan ke form dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // Memproses aksi Logout
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah keluar.');
    }
}