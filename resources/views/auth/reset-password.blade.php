<x-layouts.app>
    <div class="pt-32 pb-24 flex justify-center items-center min-h-[70vh] bg-white">
        <div class="w-full max-w-xl px-6">
            
            <h2 class="text-3xl font-normal tracking-wide mb-2 uppercase">RESET PASSWORD</h2>
            <div class="w-16 h-1 bg-black mb-10"></div>

            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 mb-6 text-sm font-medium">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <p class="text-xs text-red-700 font-semibold">{{ $errors->first() }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                @csrf

                <input type="hidden" name="token" value="{{ request()->route('token') }}">
                <input type="hidden" name="email" value="{{ request('email') }}">

                <div>
                    <label class="block text-xs font-bold tracking-wide uppercase mb-2">NEW PASSWORD</label>
                    <div class="relative">
                        <input type="password" name="password" required autofocus
                            class="w-full bg-[#f0f0f0] border border-gray-300 px-4 py-4 text-sm focus:outline-none focus:border-black transition pr-12">
                        <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold tracking-wide uppercase mb-2">RE-ENTER YOUR PASSWORD</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" required 
                            class="w-full bg-[#f0f0f0] border border-gray-300 px-4 py-4 text-sm focus:outline-none focus:border-black transition pr-12">
                        <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </span>
                    </div>
                </div>

                <button type="submit" class="w-full bg-black text-white font-bold tracking-widest uppercase py-4 mt-2 hover:bg-gray-800 transition duration-300">
                    CHANGE MY PASSWORD
                </button>
            </form>

        </div>
    </div>
</x-layouts.app>