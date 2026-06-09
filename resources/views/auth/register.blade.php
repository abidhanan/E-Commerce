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
                    <input type="password" name="password" required class="w-full bg-[#f0f0f0] border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition">
                </div>

                <div>
                    <label class="block text-xs font-bold tracking-wide uppercase mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" required class="w-full bg-[#f0f0f0] border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition">
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
</x-layouts.app>