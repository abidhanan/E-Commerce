<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Account</title>
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
                <h1 class="auth-title">Create New Password</h1>
                <p class="auth-subtitle">Choose a new password to restore access to your premium admin account.</p>

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

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="auth-group">
                        <label class="auth-label" for="email">Email</label>
                        <input id="email" type="email" name="email"
                            class="auth-input @error('email') is-invalid @enderror" value="{{ old('email', $email) }}"
                            placeholder="name@example.com" autocomplete="email" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="auth-group">
                        <label class="auth-label" for="password">New Password</label>
                        <div class="auth-input-wrap">
                            <input id="password" type="password" name="password"
                                class="auth-input has-toggle @error('password') is-invalid @enderror"
                                placeholder="Enter a new password" autocomplete="new-password" required>
                            <button type="button" class="password-toggle" data-password-toggle="password">Show</button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="auth-group">
                        <label class="auth-label" for="password_confirmation">Confirm New Password</label>
                        <div class="auth-input-wrap">
                            <input id="password_confirmation" type="password" name="password_confirmation"
                                class="auth-input has-toggle" placeholder="Confirm your new password"
                                autocomplete="new-password" required>
                            <button type="button" class="password-toggle"
                                data-password-toggle="password_confirmation">Show</button>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">Reset Password</button>
                </form>

                <div class="form-footer">
                    Remember your password?
                    <button type="button" onclick="authNavigate('{{ route('login') }}')">Back to login</button>
                </div>
            </div>
        </main>
    </div>

    @include('auth.partials.premium-auth-scripts')
</body>

</html>
