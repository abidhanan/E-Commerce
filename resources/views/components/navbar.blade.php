<nav class="w-full bg-white border-b border-gray-200">
    <div class="max-w-screen-2xl mx-auto px-6 py-4 flex justify-between items-center">
        <div class="flex-1 hidden md:block">
            <div class="relative w-64">
                <input type="text" placeholder="Search for Sweater" class="w-full bg-gray-100 border-none py-2 pl-4 pr-10 text-xs focus:ring-1 focus:ring-black">
                <svg class="w-4 h-4 absolute right-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>
        
        <div class="flex-1 flex justify-center items-center">
            <img src="{{ asset('images/Logo/logo.png') }}" alt="Clothiquee Logo" class="h-12 md:h-15 w-auto object-contain">
        </div>

        <div class="flex-1 flex justify-end items-center space-x-6 text-xs font-bold uppercase">
            @guest
                <a href="{{ route('login') }}" class="flex items-center space-x-1">
                    <span class="bg-gray-200 p-1 rounded-full"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path></svg></span> 
                    <span>Log In</span>
                </a>
            @endguest

            @auth
                <a href="/dashboard" class="flex items-center space-x-1">
                    <span class="bg-black text-white p-1 rounded-full"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path></svg></span> 
                    <span>My Account</span>
                </a>
            @endauth
            <a href="#" class="flex items-center space-x-1"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg> <span>Cart</span></a>
        </div>
    </div>
    <div class="flex justify-center space-x-12 py-3 text-xs font-bold uppercase tracking-widest border-t border-gray-100">
        <a href="#">Man</a><a href="#">Women</a><a href="#">Kids</a><a href="#">Baby</a>
    </div>
</nav>