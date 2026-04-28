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
                    <ul class="text-xs text-red-700 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" id="forgot-form" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-xs font-bold tracking-wide uppercase mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus 
                        class="w-full bg-[#f0f0f0] border border-gray-300 px-4 py-4 text-sm focus:outline-none focus:border-black transition @error('email') border-red-500 @enderror">
                    
                    @error('email')
                        <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-black text-white font-bold tracking-widest uppercase py-4 hover:bg-gray-800 transition duration-300">
                    GET THE LINK
                </button>
            </form>

            <div class="mt-6 text-sm text-black">
                Back to <a href="{{ route('login') }}" class="text-[#c4a052] font-semibold hover:underline transition">Log in</a> page
            </div>

        </div>
    </div>
</x-layouts.app>