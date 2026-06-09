<x-layouts.app>
    <div class="pt-32 pb-24 flex justify-center items-center min-h-[70vh] bg-white">
        <div class="w-full max-w-lg px-6">
            
            <h2 class="text-3xl font-normal tracking-wide mb-2 uppercase">Reset Password</h2>
            <div class="w-16 h-1 bg-black mb-10"></div>

            @if (session('status'))
                <div class="bg-green-50 text-green-800 p-4 mb-6 border-l-4 border-green-500 text-sm">
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

            <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label class="block text-xs font-bold tracking-wide uppercase mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ $email }}" readonly class="w-full bg-[#f0f0f0] border border-gray-300 px-4 py-4 text-sm focus:outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold tracking-wide uppercase mb-2">New Password</label>
                    <input type="password" name="password" required class="w-full bg-[#f0f0f0] border border-gray-300 px-4 py-4 text-sm focus:outline-none focus:border-black transition">
                </div>

                <div>
                    <label class="block text-xs font-bold tracking-wide uppercase mb-2">Confirm New Password</label>
                    <input type="password" name="password_confirmation" required class="w-full bg-[#f0f0f0] border border-gray-300 px-4 py-4 text-sm focus:outline-none focus:border-black transition">
                </div>

                <button type="submit" class="w-full bg-black text-white font-bold tracking-widest uppercase py-4 mt-6 hover:bg-gray-800 transition duration-300">
                    RESET PASSWORD
                </button>
            </form>

        </div>
    </div>
</x-layouts.app>