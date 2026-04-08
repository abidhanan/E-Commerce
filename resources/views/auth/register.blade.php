<x-layouts.app>
    <div class="pt-32 pb-24 flex justify-center items-center min-h-screen bg-white">
        <div class="bg-[#fafafa] w-full max-w-[500px] p-10 shadow-sm border border-gray-100">
            <h2 class="text-3xl font-light tracking-wide mb-1">CREATE ACCOUNT</h2>
            <div class="w-16 h-1 bg-black mb-8"></div>

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
                    <div class="relative">
                        <input type="password" name="password" required class="w-full bg-[#f0f0f0] border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black">
                        <button type="button" class="absolute right-4 top-3.5 text-gray-600 hover:text-black">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                        </button>
                    </div>
                </div>

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