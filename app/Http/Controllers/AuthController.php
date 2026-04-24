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

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

        public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah'
            ]);
        }

        $request->session()->regenerate();

        // ================= LOG LOGIN =================
        // $agent = new Agent();

        // ActivityLog::create([
        //     'user_id'    => Auth::id(),
        //     'event'      => 'login',
        //     'ip_address' => $request->ip(),
        //     'user_agent' => $request->userAgent(),
        //     'device'     => $agent->device(),
        //     'browser'    => $agent->browser(),
        //     'platform'   => $agent->platform(),
        // ]);

        // ================= AUTO DELETE > 1 BULAN =================
        ActivityLog::where('created_at', '<', now()->subMonth())->delete();

        return redirect()->intended('/dashboard');
    }

    public function showRegister()
{
    return view('auth.register');
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

    return redirect()->route('dashboard')
        ->with('message', 'Email berhasil diverifikasi.');
}
public function register(Request $request)
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
        'role'=> 'superadmin',
    ]);



    $user->sendEmailVerificationNotification();

    return redirect('/login')->with('message', 'Silakan cek email untuk verifikasi.');
}


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
    public function showVerify()
    {
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect('/dashboard');
        }
        return view('auth.verify');
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
    return view('auth.forgot-password');
}

public function sendResetLinkEmail(Request $request)
{
    $request->validate([
        'email' => 'required|email'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return back()->withErrors(['email' => 'Email tidak ditemukan']);
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

    return back()->with('status', 'Link reset password berhasil dikirim.');
}
public function showResetForm(Request $request, $token)
{
    return view('auth.reset-password', [
        'token' => $token,
        'email' => $request->email
    ]);
}

public function reset(Request $request)
{
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed',
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