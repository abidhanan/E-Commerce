<x-layouts.app>
    <div class="pt-32 pb-24 flex justify-center items-center min-h-screen bg-white">
        <div class="bg-[#fafafa] w-full max-w-xl p-10 shadow-sm border border-gray-100">
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
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus class="w-full bg-[#f0f0f0] border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition">
                </div>

                <div>
                    <label class="block text-xs font-bold tracking-wide uppercase mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full bg-[#f0f0f0] border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition">
                </div>

                <div>
                    <label class="block text-xs font-bold tracking-wide uppercase mb-2">Phone Number</label>
                    <div class="flex gap-2">
                        <select name="country_code" class="bg-[#f0f0f0] border border-gray-300 px-3 py-3 text-sm focus:outline-none focus:border-black transition">
                            <option value="+62" {{ old('country_code') == '+62' ? 'selected' : '' }}>+62 (ID)</option>
                            </select>
                        <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="81234567890" class="w-full bg-[#f0f0f0] border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold tracking-wide uppercase mb-2">Gender</label>
                        <select name="gender" required class="w-full bg-[#f0f0f0] border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition">
                            <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select</option>
                            <option value="pria" {{ old('gender') == 'pria' ? 'selected' : '' }}>Pria</option>
                            <option value="wanita" {{ old('gender') == 'wanita' ? 'selected' : '' }}>Wanita</option>
                            <option value="unisex" {{ old('gender') == 'unisex' ? 'selected' : '' }}>Unisex</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold tracking-wide uppercase mb-2">Date of Birth</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required class="w-full bg-[#f0f0f0] border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition uppercase">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold tracking-wide uppercase mb-2">Password</label>
                    <div class="relative">
                        <input type="password" id="reg-password" name="password" required class="w-full bg-[#f0f0f0] border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black focus:ring-0 transition">
                        
                        <button type="button" onclick="toggleRegPassword('reg-password', 'eye-icon-pass')" class="absolute right-4 top-3.5 text-gray-600 hover:text-black focus:outline-none">
                            <svg id="eye-icon-pass" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold tracking-wide uppercase mb-2">Confirm Password</label>
                    <div class="relative">
                        <input type="password" id="reg-password-confirm" name="password_confirmation" required class="w-full bg-[#f0f0f0] border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black focus:ring-0 transition">
                        
                        <button type="button" onclick="toggleRegPassword('reg-password-confirm', 'eye-icon-confirm')" class="absolute right-4 top-3.5 text-gray-600 hover:text-black focus:outline-none">
                            <svg id="eye-icon-confirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full bg-black text-white font-bold tracking-widest uppercase py-4 mt-6 hover:bg-gray-800 transition duration-300">
                    CREATE ACCOUNT
                </button>
            </form>

            <div class="mt-6 text-sm text-gray-800 text-center">
                Already have an account? <a href="{{ route('login') }}" class="text-[#c4a052] font-semibold hover:underline">Log In</a>
            </div>
        </div>
    </div>

    <script>
        function toggleRegPassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                // Ikon Mata Dicoret
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';
            } else {
                input.type = 'password';
                // Ikon Mata Terbuka
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            }
        }
    </script>
</x-layouts.app>