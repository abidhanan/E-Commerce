<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Account</title>
    <!-- Favicon Standard -->
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">

    <!-- PNG Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">

    <!-- Apple -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">

    <!-- Android -->
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/android-chrome-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('images/android-chrome-512x512.png') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            overflow: hidden;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        /* ── Left: Slider ── */
        .slider-section {
            flex: 1;
            position: relative;
            overflow: hidden;
            background: #000;
        }

        .slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .slide.active {
            opacity: 1;
        }

        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .slide-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.2);
        }

        .slide-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 48px;
            color: white;
        }

        .slide-title {
            font-size: 32px;
            font-weight: 300;
            line-height: 1.3;
            max-width: 500px;
        }

        .nav-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 50%;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s;
            z-index: 10;
        }

        .nav-arrow:hover {
            background: rgba(255, 255, 255, 0.35);
        }

        .nav-arrow.prev {
            left: 24px;
        }

        .nav-arrow.next {
            right: 24px;
        }

        .slide-indicators {
            position: absolute;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
            z-index: 10;
        }

        .indicator {
            width: 8px;
            height: 8px;
            background: rgba(255, 255, 255, 0.5);
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .indicator.active {
            width: 32px;
            background: white;
        }

        /* ── Right: Form ── */
        .form-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 32px;
            background: white;
            overflow-y: auto;
        }

        .form-container {
            width: 100%;
            max-width: 448px;
        }

        /* Tab toggle */
        .toggle-buttons {
            display: flex;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 20px;
        }

        .toggle-btn {
            flex: 1;
            padding: 12px 0;
            background: none;
            border: none;
            font-size: 14px;
            font-weight: 500;
            color: #9ca3af;
            cursor: pointer;
            transition: color 0.3s;
            position: relative;
        }

        .toggle-btn:hover {
            color: #6b7280;
        }

        .toggle-btn.active {
            color: #000;
        }

        .toggle-btn.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 2px;
            background: #000;
        }

        /* Form elements */
        .form-group {
            margin-bottom: 14px;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
            letter-spacing: 0.5px;
        }

        .form-input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-input:focus {
            outline: none;
            border-color: #000;
        }

        .form-input.is-invalid {
            border-color: #ef4444;
        }

        /* Checkbox */
        .checkbox-group {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 10px;
            cursor: pointer;
        }

        .checkbox-group input[type="checkbox"] {
            width: 16px;
            height: 16px;
            min-width: 16px;
            margin-top: 2px;
            cursor: pointer;
            accent-color: #000;
        }

        .checkbox-group span {
            font-size: 12.5px;
            color: #6b7280;
            line-height: 1.5;
        }

        .checkbox-wrap {
            margin-bottom: 16px;
        }

        .submit-btn {
            width: 100%;
            padding: 13px;
            background: #000;
            color: white;
            border: none;
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 1px;
            cursor: pointer;
            transition: background 0.3s, opacity 0.3s;
        }

        .submit-btn:hover:not(:disabled) {
            background: #1f2937;
        }

        .submit-btn:disabled {
            background: #d1d5db;
            color: #9ca3af;
            cursor: not-allowed;
        }

        .form-footer {
            text-align: center;
            margin-top: 16px;
            font-size: 14px;
            color: #6b7280;
        }

        .form-footer button {
            background: none;
            border: none;
            color: #000;
            font-weight: 500;
            cursor: pointer;
            text-decoration: underline;
            font-size: 14px;
        }

        .form-footer button:hover {
            color: #374151;
        }

        /* Laravel error styles */
        .invalid-feedback {
            display: block;
            font-size: 11.5px;
            color: #ef4444;
            margin-top: 5px;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 10px 14px;
            font-size: 13px;
            margin-bottom: 16px;
            border-radius: 2px;
        }

        .alert-error ul {
            margin: 0;
            padding-left: 16px;
        }

        @media (max-width: 1024px) {
            .slider-section {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">

        {{-- ===== LEFT: Slider ===== --}}
        <div class="slider-section">

            @forelse ($displayLogins as $index => $displayLogin)
                <div class="slide {{ $index === 0 ? 'active' : '' }}">
                    <img src="{{ asset('storage/' . $displayLogin->image_path) }}" alt="{{ $displayLogin->label }}">

                    <div class="slide-overlay"></div>


                </div>
            @empty
                {{-- fallback jika belum ada banner --}}
                <div class="slide active">
                    <img src="https://images.unsplash.com/photo-1517649763962-0c623066013b?w=1200&h=1600&fit=crop"
                        alt="Default Banner">

                    <div class="slide-overlay"></div>


                </div>
            @endforelse


            {{-- Navigation arrows hanya muncul jika slide lebih dari 1 --}}
            @if ($displayLogins->count() > 1)
                <button class="nav-arrow prev" onclick="changeSlide(-1)" type="button">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>

                <button class="nav-arrow next" onclick="changeSlide(1)" type="button">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
            @endif


            {{-- Indicators --}}
            @if ($displayLogins->count() > 1)
                <div class="slide-indicators">
                    @foreach ($displayLogins as $index => $displayLogin)
                        <button class="indicator {{ $index === 0 ? 'active' : '' }}"
                            onclick="goToSlide({{ $index }})" type="button">
                        </button>
                    @endforeach
                </div>
            @endif

        </div>

        {{-- ===== RIGHT: Form ===== --}}
        <div class="form-section">
            <div class="form-container">

                {{-- Tab: CREATE ACCOUNT aktif --}}
                <div class="toggle-buttons">
                    <button class="toggle-btn" type="button" onclick="goToLogin()">LOGIN</button>
                    <button class="toggle-btn active" type="button">CREATE ACCOUNT</button>
                </div>

                {{-- Validation errors --}}
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
                    data-loading-text="CREATING ACCOUNT...">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">NAMA *</label>
                        <input type="text" name="name" class="form-input @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">EMAIL *</label>
                        <input type="email" name="email" class="form-input @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">PASSWORD *</label>
                        <input type="password" name="password"
                            class="form-input @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">CONFIRM PASSWORD *</label>
                        <input type="password" name="password_confirmation" class="form-input" required>
                    </div>

                    <div class="checkbox-wrap">
                        <label class="checkbox-group">
                            <input type="checkbox" id="agree_terms" name="agree_terms" onchange="checkboxCheck()">
                            <span>I agree to the terms and conditions and privacy policy *</span>
                        </label>
                        <label class="checkbox-group">
                            <input type="checkbox" id="subscribe_newsletter" name="subscribe_newsletter"
                                onchange="checkboxCheck()">
                            <span>Subscribe to newsletter for exclusive updates and offers *</span>
                        </label>
                    </div>

                    <button type="submit" class="submit-btn" id="submit-btn" disabled>CREATE ACCOUNT</button>
                </form>

                <div class="form-footer">
                    Already have an account?
                    <button type="button" onclick="goToLogin()">Login</button>
                </div>

            </div>
        </div>

    </div>

    <script>
        function checkboxCheck() {
            const terms = document.getElementById('agree_terms').checked;
            const subscribe = document.getElementById('subscribe_newsletter').checked;
            const btn = document.getElementById('submit-btn');
            btn.disabled = !(terms && subscribe);
        }

        // Slider
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const indicators = document.querySelectorAll('.indicator');
        const totalSlides = slides.length;

        function showSlide(index) {
            slides.forEach(s => s.classList.remove('active'));
            indicators.forEach(i => i.classList.remove('active'));
            currentSlide = (index + totalSlides) % totalSlides;
            slides[currentSlide].classList.add('active');
            indicators[currentSlide].classList.add('active');
        }

        function changeSlide(direction) {
            showSlide(currentSlide + direction);
        }

        function goToSlide(index) {
            showSlide(index);
        }

        // Resume slide dari state terakhir (jika baru balik dari halaman login)
        const saved = JSON.parse(localStorage.getItem('slideState') || 'null');
        if (saved) {
            const elapsed = (Date.now() - saved.time) / 1000;
            const skipped = Math.floor(elapsed / 5);
            showSlide((saved.index + skipped) % totalSlides);
            localStorage.removeItem('slideState');
        }

        setInterval(() => changeSlide(1), 5000);

        // Animasi fade-out sebelum pindah ke login
        function goToLogin() {
            // Simpan state slide sebelum pindah halaman
            localStorage.setItem('slideState', JSON.stringify({
                index: currentSlide,
                time: Date.now()
            }));
            const fc = document.querySelector('.form-container');
            fc.style.transition = 'opacity 0.2s ease';
            fc.style.opacity = '0';
            setTimeout(() => {
                window.location.href = '{{ route('login') }}';
            }, 200);
        }
    </script>
    @include('Shared.disable-submit-script')
</body>

</html>

{{-- <!DOCTYPE html>
<html>

<head>
    <title>Register</title>
</head>

<body>
    <h2>Register</h2>

    <form method="POST" action="/register">
        @csrf

        <input type="text" name="name" placeholder="Name" required>
        <br><br>

        <input type="email" name="email" placeholder="Email" required>
        <br><br>

        <input type="password" name="password" placeholder="Password" required>
        <br><br>

        <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
        <br><br>

        <button type="submit">Register</button>
    </form>

    @error('email')
        <p style="color:red">{{ $message }}</p>
    @enderror
</body>

</html> --}}
