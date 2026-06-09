<x-layouts.app>
    <div class="pt-32 pb-24 flex justify-center items-center min-h-[70vh] bg-white">
        <div class="w-full max-w-lg px-6">
            
            <h2 class="text-3xl font-normal tracking-wide mb-2 uppercase">Reset Password</h2>
            <div class="w-16 h-1 bg-black mb-10"></div>

            @if (session('status'))
                <div class="py-10">
                    <p class="text-base text-gray-800 mb-12">
                        Please check your inbox. We've sent a link to reset your password to <strong>{{ session('email_attempt') ?? 'your email' }}</strong>.
                    </p>
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-[#c4a052] hover:underline flex items-center gap-2">
                        Back to Log in page
                    </a>
                </div>

            @else
                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf
                    
                    @error('email')
                        <p class="text-red-500 text-xs font-bold mb-2">{{ $message }}</p>
                    @enderror

                    <div>
                        <label class="block text-xs font-bold tracking-wide uppercase mb-2">Email</label>
                        <input type="email" name="email" required autofocus class="w-full bg-[#f0f0f0] border border-gray-300 px-4 py-4 text-sm focus:outline-none focus:border-black transition">
                    </div>

                    <button type="submit" class="w-full bg-black text-white font-bold tracking-widest uppercase py-4 hover:bg-gray-800 transition duration-300">
                        Get The Link
                    </button>
                </form>

                <div class="mt-6 text-left">
                    <a href="{{ route('login') }}" class="text-sm text-black hover:text-[#c4a052] transition">
                        Back to <span class="text-[#c4a052] font-semibold">Log in</span> page
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-layouts.app>