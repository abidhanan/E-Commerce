<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function showLogin ()
    {
        return view('auth.login');
    }

    public function login (Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi salah',
        ]);
    }

    public function showRegister ()
    {
        return view('auth.register');
    }

    public function Register (Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // $user->assignRole('user');
        $user->sendEmailVerificationNotification();

        return redirect('/login')->with('message', 'Silakan cek email untuk verifikasi.');
    }
    public function logout (Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function showVerify ()
    {
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect('/dashboard');
        }
        return view('auth.verify');
    }

    public function verifyEmail (Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Email verifikasi telah dikirim.');
    }
}
