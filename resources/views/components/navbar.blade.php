<nav class="w-full bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-screen-2xl mx-auto px-6 py-2 flex justify-between items-center">
        <div class="flex-1 hidden md:block">
            <div class="relative w-64">
                <input type="text" placeholder="Search for Sweater" class="w-full bg-gray-100 border-none py-2 pl-4 pr-10 text-xs focus:ring-1 focus:ring-black">
                <svg class="w-4 h-4 absolute right-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>
        
        <div class="flex-1 flex justify-center items-center">
            <img src="{{ asset('images/Logo/logo.png') }}" alt="Clothiquee Logo" class="h-12 md:h-13 w-auto object-contain">
        </div>

        <div class="flex-1 flex justify-end items-center space-x-2 text-xs font-bold uppercase">
    
            @guest
                <a href="{{ route('login') }}" class="flex items-center space-x-2 px-5 py-2 rounded-full hover:bg-black hover:text-white transition-all duration-300">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                    </svg>
                    <span class="text-xs font-bold uppercase">Log In</span>
                </a>
            @endguest

            @auth
                <a href="/dashboard" class="flex items-center space-x-2 px-5 py-2 rounded-full hover:bg-black hover:text-white transition-all duration-300">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                    </svg>
                    <span class="text-xs font-bold uppercase">My Account</span>
                </a>
            @endauth

            <a href="/wishlist" class="flex items-center space-x-2 px-5 py-2 rounded-full hover:bg-black hover:text-white transition-all duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                
            </a>

            <div class="relative group">
                <a href="/cart" class="flex items-center space-x-2 px-5 py-2 rounded-full hover:bg-black hover:text-white transition-all duration-300 relative z-10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </a>

                <div class="absolute top-full right-0 w-full h-4 bg-transparent z-40"></div>

                <div class="absolute right-0 top-full mt-2 w-80 bg-white shadow-2xl border border-gray-100 rounded-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 transform origin-top-right translate-y-2 group-hover:translate-y-0 p-8 text-center cursor-default">
                    
                    <div class="w-32 h-32 bg-gray-50 rounded-full mx-auto flex items-center justify-center mb-6 relative">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <svg class="w-5 h-5 text-gray-300 absolute top-2 left-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l2.4 7.6H22l-6 4.8 2.4 7.6-6.4-4.8-6.4 4.8 2.4-7.6-6-4.8h7.6z"/></svg>
                        <svg class="w-4 h-4 text-gray-400 absolute bottom-4 right-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l2.4 7.6H22l-6 4.8 2.4 7.6-6.4-4.8-6.4 4.8 2.4-7.6-6-4.8h7.6z"/></svg>
                    </div>

                    <h3 class="text-sm font-bold text-gray-900 mb-2">Your Bag is empty.</h3>
                    <p class="text-xs text-gray-500 mb-6">Start filling it up with your favourites.</p>

                    <a href="/" class="block w-full bg-[#222] hover:bg-black text-white text-xs font-bold uppercase tracking-wider py-4 rounded-xl transition-colors">
                        See what's new
                    </a>
                </div>
            </div>

        </div>
    </div>
    <div class="flex justify-center space-x-12 py-2 text-xs font-bold uppercase tracking-widest border-t border-gray-100 hover:border-gray-300 transition">
        <a href="#" class="hover:underline">Man</a><a href="#" class="hover:underline">Women</a><a href="#" class="hover:underline">Kids</a><a href="#" class="hover:underline">Baby</a>
    </div>
</nav>