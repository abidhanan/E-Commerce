<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ config('app.name', 'Clothique') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Montserrat', sans-serif; }</style>
</head>
<body class="bg-white text-gray-900 antialiased selection:bg-black selection:text-white">

    <div class="flex min-h-screen">
        
        @php
            // Jaring Pengaman Mutlak: Menangkap variabel dari Controller apa pun namanya
            $slideData = $banners ?? ($displayLogins ?? collect());
        @endphp
        
        <div class="hidden lg:block lg:w-1/2 relative bg-black overflow-hidden group">
            
            <div id="auth-slider-track" class="flex h-full w-full transition-transform duration-1000 ease-in-out">
                @forelse($slideData as $banner)
                    <div class="w-full h-full flex-shrink-0 relative">
                        <img src="{{ asset('storage/' . $banner->image_path) }}" alt="{{ $banner->label }}" class="absolute inset-0 w-full h-full object-cover opacity-80 filter brightness-75">
                        <div class="absolute inset-0 bg-black/20"></div>
                        
                        <div class="absolute bottom-20 left-16 z-10 text-white">
                            <h1 class="text-4xl font-bold tracking-widest uppercase">{{ config('app.name', 'Clothique') }}</h1>
                            <p class="mt-4 text-sm font-light tracking-widest uppercase">{{ $banner->label }}</p>
                        </div>
                    </div>
                @empty
                    <div class="w-full h-full flex-shrink-0 relative">
                        <img src="https://images.unsplash.com/photo-1517649763962-0c623066013b?w=1200&h=1600&fit=crop" class="absolute inset-0 w-full h-full object-cover opacity-80 filter brightness-75">
                        <div class="absolute inset-0 bg-black/20"></div>
                        
                        <div class="absolute bottom-20 left-16 z-10 text-white">
                            <h1 class="text-4xl font-bold tracking-widest uppercase">{{ config('app.name', 'Clothique') }}</h1>
                            <p class="mt-4 text-sm font-light tracking-widest uppercase">Premium Quality Collection</p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($slideData->count() > 1)
                <div class="absolute bottom-10 left-16 flex space-x-2 z-20">
                    @foreach($slideData as $index => $banner)
                        <button type="button" 
                                class="slider-dot w-2 h-2 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-white w-6' : 'bg-gray-400' }}"
                                onclick="goToSlide({{ $index }})">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 lg:p-24 bg-[#fafafa]">
            <div class="w-full max-w-md">
                
                <h2 class="text-3xl font-light tracking-wide mb-1">LOG IN</h2>
                <div class="w-12 h-1 bg-black mb-10"></div>

                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                        <ul class="text-xs text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-bold tracking-wide uppercase mb-2">Email</label>
                        <input type="email" name="email" required autofocus class="w-full bg-white border border-gray-200 px-4 py-4 text-sm focus:outline-none focus:border-black transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold tracking-wide uppercase mb-2">Password</label>
                        <div class="relative">
                            <input type="password" id="password-input" name="password" required class="w-full bg-white border border-gray-200 px-4 py-4 text-sm focus:outline-none focus:border-black focus:ring-0 transition">
                            
                            <button type="button" onclick="togglePassword()" class="absolute right-4 top-4 text-gray-400 hover:text-black focus:outline-none transition">
                                <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="mt-3 text-right">
                            <a href="{{ route('password.request') }}" class="text-gray-500 hover:text-black text-xs font-bold uppercase tracking-widest transition">Forgot Password?</a>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-black text-white font-bold tracking-widest uppercase py-4 mt-6 hover:bg-[#c4a052] transition-colors duration-300">
                        LOG IN
                    </button>
                </form>

                <div class="mt-10 pt-8 border-t border-gray-200 text-[12px] text-gray-500 text-center uppercase tracking-widest font-semibold">
                    Don't have an account? <br>
                    <a href="{{ route('register') }}" class="inline-block mt-2 text-black border-b border-black pb-1 hover:text-[#c4a052] hover:border-[#c4a052] transition">Create Account</a>
                </div>

            </div>
        </div>

    </div>

    <script>
        // 1. Logika Toggle Password (Bawaanmu)
        function togglePassword() {
            const input = document.getElementById('password-input');
            const icon = document.getElementById('eye-icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            }
        }

        // 2. Logika Mesin Slider Banner (Injeksi Baru)
        document.addEventListener('DOMContentLoaded', function() {
            const track = document.getElementById('auth-slider-track');
            const dots = document.querySelectorAll('.slider-dot');
            const slideCount = {{ $slideData->count() }};
            
            if (slideCount <= 1 || !track) return;

            let currentIndex = 0;
            let slideInterval;

            window.goToSlide = function(index) {
                currentIndex = index;
                track.style.transform = `translateX(-${currentIndex * 100}%)`;
                
                dots.forEach((dot, i) => {
                    if (i === currentIndex) {
                        dot.classList.replace('bg-gray-400', 'bg-white');
                        dot.classList.replace('w-2', 'w-6');
                    } else {
                        dot.classList.replace('bg-white', 'bg-gray-400');
                        dot.classList.replace('w-6', 'w-2');
                    }
                });

                resetInterval();
            };

            function nextSlide() {
                let nextIndex = (currentIndex + 1) % slideCount;
                goToSlide(nextIndex);
            }

            function resetInterval() {
                clearInterval(slideInterval);
                slideInterval = setInterval(nextSlide, 5000);
            }

            resetInterval();
        });
    </script>
</body>
</html>