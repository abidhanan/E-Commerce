<x-layouts.app>
    <div class="pt-32 pb-24 flex justify-center items-center min-h-screen bg-white">
        <div class="bg-[#fafafa] w-full max-w-md p-10 shadow-sm border border-gray-100">
            <h2 class="text-3xl font-light tracking-wide mb-1">LOG IN</h2>
            <div class="w-12 h-1 bg-black mb-8"></div>

            <form method="POST" action="/login" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-xs font-bold tracking-wide uppercase mb-2">Email</label>
                    <input type="email" name="email" required class="w-full bg-[#f0f0f0] border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black focus:ring-0 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold tracking-wide uppercase mb-2">Password</label>
                    <div class="relative">
                        <input type="password" name="password" required class="w-full bg-[#f0f0f0] border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black focus:ring-0 transition">
                        <button type="button" class="absolute right-4 top-3.5 text-gray-600 hover:text-black">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                    <div class="mt-2 text-left">
                        <a href="#" class="text-[#c4a052] text-xs font-semibold hover:underline">Forgot Password</a>
                    </div>
                </div>

                <button type="submit" class="w-full bg-black text-white font-bold tracking-widest uppercase py-4 mt-4 hover:bg-gray-800 transition duration-300">
                    LOG IN
                </button>
            </form>

            <div class="mt-6 text-sm text-gray-800">
                Don't have an account? <a href="{{ route('register') }}" class="text-[#c4a052] font-semibold hover:underline">Create Account</a>
            </div>
        </div>
    </div>
</x-layouts.app>