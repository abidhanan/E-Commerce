<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Account</title>
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


                <h1 class="auth-title">Welcome Back</h1>
                <p class="auth-subtitle">Sign in to manage catalog, orders, content, and brand operations from one
                    premium workspace.</p>

                <div class="auth-tabs">
                    <button class="auth-tab active" type="button">Login</button>
                    <button class="auth-tab" type="button" onclick="authNavigate('{{ route('register') }}')">Create
                        Account</button>
                </div>

                @if (session('message'))
                    <div class="alert-success">{{ session('message') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert-error">{{ session('error') }}</div>
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

                <form method="POST" action="{{ route('login') }}">
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

                    <div class="auth-group">
                        <label class="auth-label" for="password">Password</label>
                        <div class="auth-input-wrap">
                            <input id="password" type="password" name="password"
                                class="auth-input has-toggle @error('password') is-invalid @enderror"
                                placeholder="Enter your password" autocomplete="current-password" required>
                            <button type="button" class="password-toggle" data-password-toggle="password">Show</button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="auth-row">
                        <label class="auth-check" for="remember">
                            <input type="checkbox" name="remember" id="remember">
                            <span>Remember me</span>
                        </label>

                        <button type="button" class="auth-link"
                            onclick="authNavigate('{{ route('password.request') }}')">
                            Forgot password?
                        </button>
                    </div>

                    <button type="submit" class="submit-btn">Login</button>
                </form>

                <div class="form-footer">
                    Don't have an account?
                    <button type="button" onclick="authNavigate('{{ route('register') }}')">Create one</button>
                </div>
            </div>
        </main>
    </div>

    @include('auth.partials.premium-auth-scripts')
</body>

</html>
