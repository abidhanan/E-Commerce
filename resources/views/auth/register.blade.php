<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Account - {{ config('app.name', 'Clothique') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Montserrat', sans-serif; }</style>
</head>
<body class="bg-white text-gray-900 antialiased selection:bg-black selection:text-white">

    <div class="flex min-h-screen">
        
        @php
            $bannerUrl = (isset($displayLogins) && $displayLogins->first()) 
                ? asset('storage/' . $displayLogins->first()->image_path) 
                : 'https://images.unsplash.com/photo-1517649763962-0c623066013b?w=1200&h=1600&fit=crop';
        @endphp
        
        <div class="hidden lg:block lg:w-1/2 relative bg-black">
            <img src="{{ $bannerUrl }}" alt="Register Banner" class="absolute inset-0 w-full h-full object-cover opacity-80">
            <div class="absolute inset-0 bg-black/20"></div>
            
            <div class="absolute bottom-16 left-16 z-10 text-white">
                <h1 class="text-4xl font-bold tracking-widest uppercase">{{ config('app.name', 'Clothique') }}</h1>
                <p class="mt-4 text-sm font-light tracking-widest uppercase">Join Our Exclusive Member</p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 lg:p-16 bg-[#fafafa]">
            <div class="w-full max-w-lg">
                
                <h2 class="text-3xl font-light tracking-wide mb-1">CREATE ACCOUNT</h2>
                <div class="w-12 h-1 bg-black mb-8"></div>

                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                        <ul class="text-xs text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-bold tracking-wide uppercase mb-2">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus class="w-full bg-white border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:border-black transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold tracking-wide uppercase mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full bg-white border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:border-black transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold tracking-wide uppercase mb-2">Phone Number</label>
                        <div class="flex gap-2">
                            <select name="country_code" class="bg-white border border-gray-200 px-3 py-3 text-sm focus:outline-none focus:border-black transition">
                                <option value="+62" {{ old('country_code') == '+62' ? 'selected' : '' }}>+62 (ID)</option>
                            </select>
                            <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="81234567890" class="w-full bg-white border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:border-black transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold tracking-wide uppercase mb-2">Gender</label>
                            <select name="gender" required class="w-full bg-white border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:border-black transition">
                                <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select</option>
                                <option value="pria" {{ old('gender') == 'pria' ? 'selected' : '' }}>Pria</option>
                                <option value="wanita" {{ old('gender') == 'wanita' ? 'selected' : '' }}>Wanita</option>
                                <option value="unisex" {{ old('gender') == 'unisex' ? 'selected' : '' }}>Unisex</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold tracking-wide uppercase mb-2">Date of Birth</label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required class="w-full bg-white border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:border-black transition uppercase">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold tracking-wide uppercase mb-2">Password</label>
                        <div class="relative">
                            <input type="password" id="reg-password" name="password" required class="w-full bg-white border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:border-black focus:ring-0 transition">
                            
                            <button type="button" onclick="toggleRegPassword('reg-password', 'eye-icon-pass')" class="absolute right-4 top-3 text-gray-400 hover:text-black focus:outline-none transition">
                                <svg id="eye-icon-pass" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold tracking-wide uppercase mb-2">Confirm Password</label>
                        <div class="relative">
                            <input type="password" id="reg-password-confirm" name="password_confirmation" required class="w-full bg-white border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:border-black focus:ring-0 transition">
                            
                            <button type="button" onclick="toggleRegPassword('reg-password-confirm', 'eye-icon-confirm')" class="absolute right-4 top-3 text-gray-400 hover:text-black focus:outline-none transition">
                                <svg id="eye-icon-confirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-black text-white font-bold tracking-widest uppercase py-4 mt-6 hover:bg-[#c4a052] transition-colors duration-300">
                        CREATE ACCOUNT
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-gray-200 text-[12px] text-gray-500 text-center uppercase tracking-widest font-semibold">
                    Already have an account? <br>
                    <a href="{{ route('login') }}" class="inline-block mt-2 text-black border-b border-black pb-1 hover:text-[#c4a052] hover:border-[#c4a052] transition">Log In</a>
                </div>

            </div>
        </div>

    </div>

    <script>
        function toggleRegPassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            }
        }
    </script>
</body>
</html>