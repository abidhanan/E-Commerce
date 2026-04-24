<x-layouts.app>
    <div class="pt-32 pb-24 flex justify-center items-center min-h-screen bg-white">
        <div class="bg-[#fafafa] w-full max-w-[500px] p-10 shadow-sm border border-gray-100">
            <h2 class="text-3xl font-light tracking-wide mb-1">CREATE ACCOUNT</h2>
            <div class="w-16 h-1 bg-black mb-8"></div>

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-xs font-bold text-red-800 uppercase tracking-wider">Pendaftaran Gagal</h3>
                            <ul class="mt-2 text-xs text-red-700 list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="/register" class="space-y-5">
                @csrf
                
                <div>
                    <label class="block text-xs font-bold tracking-wide uppercase mb-2">Email</label>
                    <input type="email" name="email" required class="w-full bg-[#f0f0f0] border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black">
                </div>

                <div>
                    <label class="block text-xs font-bold tracking-wide uppercase mb-2">Full Name</label>
                    <input type="text" name="name" required class="w-full bg-[#f0f0f0] border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black">
                </div>

                <div>
                    <label class="block text-xs font-bold tracking-wide uppercase mb-2">Telephone</label>
                    <div class="flex">
                        <select name="country_code" class="bg-[#e0e0e0] border border-gray-300 border-r-0 px-3 py-3 text-sm focus:outline-none focus:border-black">
                            <option value="+62">+62</option>
                        </select>
                        <input type="tel" name="phone" required class="w-full bg-[#f0f0f0] border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold tracking-wide uppercase mb-2">Birth Date</label>
                    <input type="date" name="dob" required class="w-full bg-[#f0f0f0] border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black text-gray-500 uppercase">
                </div>

                <div>
                    <label class="block text-xs font-bold tracking-wide uppercase mb-2">Gender</label>
                    <div class="flex space-x-6 mt-2">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="gender" value="man" required class="w-4 h-4 text-black border-gray-300 focus:ring-black">
                            <span class="text-sm font-semibold uppercase">Man</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="gender" value="women" required class="w-4 h-4 text-black border-gray-300 focus:ring-black">
                            <span class="text-sm font-semibold uppercase">Women</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold tracking-wide uppercase mb-2">Password</label>
                    <div class="relative mt-2">
                        <input type="password" id="password-input" name="password" required class="w-full bg-[#f0f0f0] border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black focus:ring-0 transition">
                        <button type="button" onclick="togglePassword('password-input', 'eye-icon')" class="absolute right-4 top-3.5 text-gray-600 hover:text-black focus:outline-none">
                            <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold tracking-wide uppercase mb-2">Confirm Password</label>
                    <div class="relative mt-2">
                        <input type="password" id="password-confirm-input" name="password_confirmation" required class="w-full bg-[#f0f0f0] border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black focus:ring-0 transition">
                        <button type="button" onclick="togglePassword('password-confirm-input', 'eye-icon-confirm')" class="absolute right-4 top-3.5 text-gray-600 hover:text-black focus:outline-none">
                            <svg id="eye-icon-confirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <script>
                    // Script diperbarui agar bisa menangani dua input password yang berbeda
                    function togglePassword(inputId, iconId) {
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

                <div class="pt-2">
                    <label class="flex items-start space-x-3 cursor-pointer">
                        <input type="checkbox" required class="mt-1 w-5 h-5 border-2 border-black rounded-sm text-black focus:ring-0">
                        <span class="text-sm font-medium text-gray-800 leading-snug">
                            By signing up, you agree to Clothique's <a href="#" class="text-[#c4a052] hover:underline">Terms & Conditions</a> and <a href="#" class="text-[#c4a052] hover:underline">Privacy Policy</a>
                        </span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-black text-white font-bold tracking-widest uppercase py-4 mt-6 hover:bg-gray-800 transition duration-300">
                    REGISTER
                </button>
            </form>

            <div class="mt-6 text-sm text-gray-800">
                Already have an account? <a href="{{ route('login') }}" class="text-[#c4a052] font-semibold hover:underline">Log in</a>
            </div>
        </div>
    </div>
</x-layouts.app>