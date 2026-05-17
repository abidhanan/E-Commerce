<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Account</title>
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
            <div class="auth-card auth-card--register">
                <span class="auth-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="E-Store">
                </span>

                <h1 class="auth-title">Create Your Account</h1>
                <p class="auth-subtitle">Create a secure account to access the same premium workspace experience.</p>

                <div class="auth-tabs">
                    <button class="auth-tab" type="button"
                        onclick="authNavigate('{{ route('login') }}')">Login</button>
                    <button class="auth-tab active" type="button">Create Account</button>
                </div>

                @if ($errors->any())
                    <div class="alert-error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" data-disable-on-submit
                    data-loading-text="Creating Account...">
                    @csrf

                    <div class="auth-group">
                        <label class="auth-label" for="name">Name</label>
                        <input id="name" type="text" name="name"
                            class="auth-input @error('name') is-invalid @enderror" value="{{ old('name') }}"
                            placeholder="Your full name" autocomplete="name" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="auth-group">
                        <label class="auth-label" for="email">Email</label>
                        <input id="email" type="email" name="email"
                            class="auth-input @error('email') is-invalid @enderror" value="{{ old('email') }}"
                            placeholder="name@example.com" autocomplete="email" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="auth-group">
                        <label class="auth-label" for="password">Password</label>
                        <div class="auth-input-wrap">
                            <input id="password" type="password" name="password"
                                class="auth-input has-toggle @error('password') is-invalid @enderror"
                                placeholder="Create a password" autocomplete="new-password" required>
                            <button type="button" class="password-toggle" data-password-toggle="password">Show</button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="auth-group">
                        <label class="auth-label" for="password_confirmation">Confirm Password</label>
                        <div class="auth-input-wrap">
                            <input id="password_confirmation" type="password" name="password_confirmation"
                                class="auth-input has-toggle" placeholder="Confirm your password"
                                autocomplete="new-password" required>
                            <button type="button" class="password-toggle"
                                data-password-toggle="password_confirmation">Show</button>
                        </div>
                    </div>

                    <div class="checkbox-wrap">
                        <label class="checkbox-group" for="agree_terms">
                            <input type="checkbox" id="agree_terms" name="agree_terms" onchange="checkboxCheck()">
                            <span>
                                I agree to the
                                <a href="{{ route('legal.show', 'terms-privacy') }}" class="auth-link"
                                    target="_blank" rel="noopener">terms and conditions and privacy policy</a>
                                *
                            </span>
                        </label>
                        <label class="checkbox-group" for="subscribe_newsletter">
                            <input type="checkbox" id="subscribe_newsletter" name="subscribe_newsletter"
                                onchange="checkboxCheck()">
                            <span>
                                Subscribe to
                                <a href="{{ route('legal.show', 'newsletter-offers') }}" class="auth-link"
                                    target="_blank" rel="noopener">newsletter for exclusive updates and offers</a>
                                *
                            </span>
                        </label>
                    </div>

                    <button type="submit" class="submit-btn" id="submit-btn" disabled>Create Account</button>
                </form>

                <div class="form-footer">
                    Already have an account?
                    <button type="button" onclick="authNavigate('{{ route('login') }}')">Login</button>
                </div>
            </div>
        </main>
    </div>

    @include('auth.partials.premium-auth-scripts')
    <script>
        function checkboxCheck() {
            const terms = document.getElementById('agree_terms').checked;
            const subscribe = document.getElementById('subscribe_newsletter').checked;
            const button = document.getElementById('submit-btn');
            button.disabled = !(terms && subscribe);
        }
    </script>
    @include('Shared.disable-submit-script')
</body>

</html>
