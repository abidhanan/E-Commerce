<x-layouts.app>
    <div class="pt-32 pb-24 flex justify-center items-center min-h-[70vh] bg-white">
        <div class="w-full max-w-md px-6">
            
            <h2 class="text-3xl font-normal tracking-wide mb-2 uppercase text-center">Login</h2>
            <div class="w-16 h-1 bg-black mx-auto mb-10"></div>

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
                        <a href="{{ url('/password/reset') }}" class="text-[#c4a052] text-xs font-semibold hover:underline">Lupa password?</a>
                    </div>
                </div>

                <button type="submit" class="w-full bg-black text-white font-bold tracking-widest uppercase py-4 mt-2 hover:bg-gray-800 transition duration-300">
                    Login
                </button>
            </form>

            <div class="mt-8 text-center text-sm text-gray-800 flex flex-col gap-3 border-t border-gray-200 pt-6">
                <p>Belum punya akun? <a href="{{ route('register') }}" class="text-[#c4a052] font-bold uppercase tracking-wide hover:underline transition">Register</a></p>
            </div>

        </div>
    </div>
</x-layouts.app>