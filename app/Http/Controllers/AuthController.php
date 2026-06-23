<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Models\DisplayLogin;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AuthController extends Controller
{
    public function showLogin()
    {
        // 1. Pengecekan sesi sudah benar
        if (Auth::check()) {
            return Auth::user()->hasRole('user')
                ? redirect('/')
                : redirect('/dashboard');
        }

        // 2. Kueri Mutlak: HANYA ambil yang aktif, urutkan posisinya, dan beri nama variabel yang selaras dengan antarmuka
        $banners = DisplayLogin::where('is_active', true)
                               ->orderBy('position')
                               ->get();

        return view('auth.login', compact('banners'));
    }

        public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string'
        ]);

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah'
            ]);
        }

        $request->session()->regenerate();

        $agent = new Agent();

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'event'      => 'login',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'device'     => $agent->device(),
            'browser'    => $agent->browser(),
            'platform'   => $agent->platform(),
        ]);

        // ================= AUTO DELETE > 6 BULAN =================
        ActivityLog::where('created_at', '<', now()->subMonths(6))->delete();

        return Auth::user()->hasRole('user')
            ? redirect()->intended('/')
            : redirect()->intended('/dashboard');
    }

    public function showRegister()
{
     $displayLogins = DisplayLogin::orderBy('position')->get();
    return view('auth.register', compact('displayLogins'));
}
public function verifyEmail(Request $request, $id, $hash)
{
    $user = User::findOrFail($id);

    // Validasi hash email
    if (!hash_equals($hash, sha1($user->getEmailForVerification()))) {
        abort(403);
    }

    // Validasi signature URL
    if (!URL::hasValidSignature($request)) {
        abort(403);
    }

    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
    }

    Auth::login($user);
    if (Auth::user()->hasRole('user')) {
        return redirect('/')->with('message', 'Email berhasil diverifikasi. Selamat datang di toko kami!');
    }else{
    return redirect()->route('dashboard')
        ->with('message', 'Email berhasil diverifikasi.');
    }
   
}
    public function register(Request $request)
    {
        // 1. Paksa sistem memvalidasi data yang masuk
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()],
            'phone' => 'required|string|max:20', 
            'gender' => 'required|string|in:pria,wanita,unisex', 
            'date_of_birth' => 'required|date', 
        ]);

        // 2. Paksa sistem memasukkannya ke database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,           
            'gender' => $request->gender,         
            'date_of_birth' => $request->date_of_birth, 
        ]);

        $user->assignRole('user');

        $user->sendEmailVerificationNotification();

        Auth::login($user);

        return redirect('/email/verify'); 
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
    public function showVerify()
    {
         $displayLogins = DisplayLogin::orderBy('position')->get();
   
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect('/dashboard');
        }
        return view('auth.verify', compact('displayLogins'));
    }

    public function resendVerification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect('/dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
    public function showForgotPassword()
{
    $displayLogins = DisplayLogin::orderBy('position')->get();
  
    return view('auth.forgot-password', compact('displayLogins'));
}

public function sendResetLinkEmail(Request $request)
{
    $request->validate([
        'email' => 'required|email|max:255'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return back()->with('status', 'Jika email terdaftar, link reset password akan dikirim.');
    }

    $token = Password::createToken($user);

    $resetUrl = url(route('password.reset', [
        'token' => $token,
        'email' => $user->email,
    ], false));

    $user->notify(new class($resetUrl, $user) extends \Illuminate\Notifications\Notification {

        protected $resetUrl;
        protected $user;

        public function __construct($resetUrl, $user)
        {
            $this->resetUrl = $resetUrl;
            $this->user = $user;
        }

        public function via($notifiable)
        {
            return ['mail'];
        }

        public function toMail($notifiable)
        {
            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('Reset Password')
                ->view('Email.Reset', [
                    'name'     => $this->user->name,
                    'resetUrl' => $this->resetUrl,
                    'expire'   => config('auth.passwords.users.expire') . ' menit',
                ]);
        }
    });

    return back()->with('status', 'Jika email terdaftar, link reset password akan dikirim.');
}
public function showResetForm(Request $request, $token)
{
    $displayLogins = DisplayLogin::orderBy('position')->get();
    return view('auth.reset-password', [
        'token' => $token,
        'email' => $request->email,
        'displayLogins' => $displayLogins
    ]);
}

public function reset(Request $request)
{
    $request->validate([
        'token' => 'required',
        'email' => 'required|email|max:255',
        'password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()],
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
            ])->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('message', __($status))
        : back()->withErrors(['email' => [__($status)]]);
}
}
