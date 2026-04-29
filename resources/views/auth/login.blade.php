<x-layouts.app>
    <div class="pt-32 pb-24 flex justify-center items-center min-h-[70vh] bg-white">
        <div class="w-full max-w-md px-6">
            
            <h2 class="text-3xl font-normal tracking-wide mb-2 uppercase text-center">Login</h2>
            <div class="w-16 h-1 bg-black mx-auto mb-10"></div>

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-4 mb-6 flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 mt-0.5 text-green-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h4 class="text-sm font-bold tracking-wide uppercase mb-1">Berhasil!</h4>
                        <p class="text-xs font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <p class="text-xs text-red-700 font-semibold">{{ $errors->first() }}</p>
                </div>
            @endif

            <form method="POST" action="{{ url('/login') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-xs font-bold tracking-wide uppercase mb-2">Email</label>
                    <input type="email" name="email" required autofocus class="w-full bg-[#f0f0f0] border border-gray-300 px-4 py-4 text-sm focus:outline-none focus:border-black transition">
                </div>

                <div>
                    <label class="block text-xs font-bold tracking-wide uppercase mb-2">Password</label>
                    <input type="password" name="password" required class="w-full bg-[#f0f0f0] border border-gray-300 px-4 py-4 text-sm focus:outline-none focus:border-black transition">
                    
                    <div class="mt-2 text-right">
                        <a href="{{ url('/password/reset') }}" class="text-[#c4a052] text-xs font-semibold hover:underline">Forgot your password?</a>
                    </div>
                </div>

                <button type="submit" class="w-full bg-black text-white font-bold tracking-widest uppercase py-4 mt-2 hover:bg-gray-800 transition duration-300">
                    Login
                </button>
            </form>

            <div class="mt-8 text-center text-sm text-gray-800 flex flex-col gap-3 border-t border-gray-200 pt-6">
                <p>Don't have an account? <a href="{{ route('register') }}" class="text-[#c4a052] font-bold uppercase tracking-wide hover:underline transition">Register</a></p>
            </div>

        </div>
    </div>
</x-layouts.app>