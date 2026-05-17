<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - Account</title>
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

                <span class="auth-brand-kicker">Email Security</span>
                <h1 class="auth-title">Verifikasi Email</h1>
                <p class="auth-subtitle">Verifikasi email Anda terlebih dahulu untuk melanjutkan akses ke account portal.</p>

                @if (session('status') == 'verification-link-sent')
                    <div class="alert-success">
                        Link verifikasi baru telah dikirim ke email Anda.
                    </div>
                @endif

                <div class="auth-alert">
                    Silakan cek inbox atau folder spam untuk menemukan email verifikasi.
                </div>

                <form method="POST" action="{{ route('verification.send') }}" id="verify-form" data-disable-on-submit
                    data-loading-text="Mengirim Email..." data-save-slide-state>
                    @csrf
                    <button type="submit" class="submit-btn">Kirim Ulang Email Verifikasi</button>
                </form>

                <div class="form-footer">
                    Sudah terverifikasi?
                    <button type="button" onclick="authNavigate('{{ route('login') }}')">Kembali ke login</button>
                </div>
            </div>
        </main>
    </div>

    @include('auth.partials.premium-auth-scripts')
    @include('Shared.disable-submit-script')
</body>

</html>
