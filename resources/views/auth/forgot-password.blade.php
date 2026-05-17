<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Account</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/android-chrome-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('images/android-chrome-512x512.png') }}">
    @include('auth.partials.premium-auth-styles')
</head>

<body>
    <div class="auth-shell">
        @include('auth.partials.premium-slider')

        <main class="auth-panel">
            <div class="auth-card">
                <span class="auth-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="E-Store">
                </span>

                <span class="auth-brand-kicker">Secure Recovery</span>
                <h1 class="auth-title">Reset Access</h1>
                <p class="auth-subtitle">Enter your email address and we will send a secure password reset link.</p>

                @if (session('status'))
                    <div class="alert-success">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert-error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" id="forgot-form" data-disable-on-submit
                    data-loading-text="Sending Reset Link..." data-save-slide-state>
                    @csrf

                    <div class="auth-group">
                        <label class="auth-label" for="email">Email</label>
                        <input id="email" type="email" name="email"
                            class="auth-input @error('email') is-invalid @enderror" value="{{ old('email') }}"
                            placeholder="name@example.com" autocomplete="email" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="submit-btn">Send Reset Link</button>
                </form>

                <div class="form-footer">
                    Remember your password?
                    <button type="button" onclick="authNavigate('{{ route('login') }}')">Back to login</button>
                </div>
            </div>
        </main>
    </div>

    @include('auth.partials.premium-auth-scripts')
    @include('Shared.disable-submit-script')
</body>

</html>
