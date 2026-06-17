<nav class="w-full bg-white border-b border-gray-200 sticky top-0 z-50" id="main-navbar">
    <div class="max-w-screen-2xl mx-auto px-6 py-4 flex justify-between items-center">
        
        <div class="flex-1 hidden md:flex items-center space-x-8 text-xs font-bold uppercase tracking-widest text-gray-900">
            <a href="{{ route('shop.index') }}" class="hover:text-[#c4a052] transition border-b-2 border-transparent hover:border-[#c4a052] pb-1">
                Shop
            </a>

            <div class="relative">
                <button type="button" onclick="toggleSearchPanel()" class="hover:text-[#c4a052] transition uppercase focus:outline-none border-b-2 border-transparent hover:border-[#c4a052] pb-1">
                    Search
                </button>

                <div id="search-dropdown" class="absolute left-0 top-full mt-6 w-80 bg-white border border-gray-200 shadow-2xl opacity-0 invisible transform -translate-y-2 transition-all duration-300 z-50">
                    <form action="{{ route('shop.index') }}" method="GET" class="relative flex items-center p-2">
                        <input type="text" name="search" id="search-input" placeholder="Search products, categories..." class="w-full bg-gray-50 border-none py-3 pl-4 pr-12 text-xs font-medium focus:ring-1 focus:ring-black outline-none transition">
                        <button type="submit" class="absolute right-4 text-gray-400 hover:text-black transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="flex-1 flex justify-center items-center">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/Logo/logo.png') }}" alt="Clothiquee Logo" class="h-12 md:h-10 w-auto object-contain">
            </a>
        </div>

        <div class="flex-1 flex justify-end items-center space-x-2 text-xs font-bold uppercase">
    
            @guest
                <a href="{{ route('login') }}" class="flex items-center space-x-2 px-5 py-2 transition border-b-2 border-transparent hover:border-[#c4a052] hover:text-[#c4a052] transition-all duration-300">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                    </svg>
                    <span class="text-xs font-bold uppercase hidden sm:block">Log In</span>
                </a>
            @endguest

            @auth
                <a href="/account" class="flex items-center space-x-2 px-5 py-2 transition border-b-2 border-transparent hover:border-[#c4a052] hover:text-[#c4a052] transition-all duration-300">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                    </svg>
                    <span class="text-xs font-bold uppercase hidden sm:block">My Account</span>
                </a>
            @endauth

            <a href="/wishlist" class="flex items-center space-x-2 px-5 py-2 transition border-b-2 border-transparent hover:border-[#c4a052] hover:text-[#c4a052] transition-all duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </a>

           <div class="relative flex items-center">
                <button onclick="toggleCart()" class="flex items-center space-x-2 px-5 py-2 transition border-b-2 border-transparent hover:border-[#c4a052] hover:text-[#c4a052] transition-all duration-300 relative focus:outline-none cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    
                    @php
                        $initialCartCount = auth()->check() ? auth()->user()->cartItems()->sum('qty') : 0;
                    @endphp

                    <span id="navbar-cart-count" class="absolute top-0 right-2 bg-red-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full flex items-center justify-center min-w-[18px] min-h-[18px]">
                        {{ $initialCartCount }}
                    </span>
                </button>
            </div>

        </div>
    </div>

   <div class="relative w-full border-t border-gray-100">
        <div class="flex justify-center space-x-12 py-3 text-[11px] font-bold uppercase tracking-widest">
            
            <div class="group static">
                <a href="#" class="pb-4 hover:text-[#c4a052] border-b-2 border-transparent group-hover:border-[#c4a052] transition cursor-pointer">Man</a>
                
                <div class="absolute left-0 top-full w-full bg-gradient-to-b from-[#fdfbf6] to-white shadow-2xl border-t border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                    
                    <div class="max-w-screen-lg mx-auto px-6 py-12 grid grid-cols-4 gap-8 text-center">
                        
                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-widest mb-6 text-black">Clothes</h3>
                            <ul class="space-y-3 text-[13px] text-gray-600 font-normal capitalize">
                                <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Shirt</a></li>
                                <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">T-shirt</a></li>
                                <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Pants</a></li>
                                <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Short Pants</a></li>
                                <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Jeans</a></li>
                                <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Outerwear</a></li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-widest mb-6 text-black">Accessories</h3>
                            <ul class="space-y-3 text-[13px] text-gray-600 font-normal capitalize">
                                <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Bag</a></li>
                                <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Watch</a></li>
                                <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Hat</a></li>
                                <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Glasses</a></li>
                                <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Belt</a></li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-widest mb-6 text-black">Shoes</h3>
                            <ul class="space-y-3 text-[13px] text-gray-600 font-normal capitalize">
                                <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Sandal</a></li>
                                <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Sneakers</a></li>
                                <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Sport</a></li>
                                <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Formal</a></li>
                                <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Slip On</a></li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-widest mb-6 text-black">Brands</h3>
                            <ul class="space-y-3 text-[13px] text-gray-600 font-normal capitalize">
                                <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Chanel</a></li>
                                <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Christian Dior</a></li>
                                <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Armani</a></li>
                                <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Prada</a></li>
                                <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Louis Vuitton</a></li>
                                <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Calvin Klein</a></li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>

            <div class="group">
                <a href="#" class="pb-4 hover:text-[#c4a052] border-b-2 border-transparent hover:border-[#c4a052] transition cursor-pointer">Women</a>
                    <div class="absolute left-0 top-full w-full bg-gradient-to-b from-[#fdfbf6] to-white shadow-2xl border-t border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                        <div class="max-w-screen-lg mx-auto px-6 py-12 grid grid-cols-4 gap-8 text-center">
                            
                            <div>
                                <h3 class="text-sm font-bold uppercase tracking-widest mb-6 text-black">Clothes</h3>
                                <ul class="space-y-3 text-[13px] text-gray-600 font-normal capitalize">
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Shirt</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">T-shirt</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Pants</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Short Pants</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Jeans</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Outerwear</a></li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-sm font-bold uppercase tracking-widest mb-6 text-black">Accessories</h3>
                                <ul class="space-y-3 text-[13px] text-gray-600 font-normal capitalize">
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Bag</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Watch</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Hat</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Glasses</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Belt</a></li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-sm font-bold uppercase tracking-widest mb-6 text-black">Shoes</h3>
                                <ul class="space-y-3 text-[13px] text-gray-600 font-normal capitalize">
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Sandal</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Sneakers</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Sport</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Formal</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Slip On</a></li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-sm font-bold uppercase tracking-widest mb-6 text-black">Brands</h3>
                                <ul class="space-y-3 text-[13px] text-gray-600 font-normal capitalize">
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Chanel</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Christian Dior</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Armani</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Prada</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Louis Vuitton</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Calvin Klein</a></li>
                                </ul>
                            </div>

                        </div>
                    </div>
            </div>

            <div class="group">
                <a href="#" class="pb-4 hover:text-[#c4a052] border-b-2 border-transparent hover:border-[#c4a052] transition cursor-pointer">Unisex</a>
                    <div class="absolute left-0 top-full w-full bg-gradient-to-b from-[#fdfbf6] to-white shadow-2xl border-t border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                        <div class="max-w-screen-lg mx-auto px-6 py-12 grid grid-cols-4 gap-8 text-center">
                            
                            <div>
                                <h3 class="text-sm font-bold uppercase tracking-widest mb-6 text-black">Clothes</h3>
                                <ul class="space-y-3 text-[13px] text-gray-600 font-normal capitalize">
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Shirt</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">T-shirt</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Pants</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Short Pants</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Jeans</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Outerwear</a></li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-sm font-bold uppercase tracking-widest mb-6 text-black">Accessories</h3>
                                <ul class="space-y-3 text-[13px] text-gray-600 font-normal capitalize">
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Bag</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Watch</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Hat</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Glasses</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Belt</a></li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-sm font-bold uppercase tracking-widest mb-6 text-black">Shoes</h3>
                                <ul class="space-y-3 text-[13px] text-gray-600 font-normal capitalize">
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Sandal</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Sneakers</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Sport</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Formal</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Slip On</a></li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-sm font-bold uppercase tracking-widest mb-6 text-black">Brands</h3>
                                <ul class="space-y-3 text-[13px] text-gray-600 font-normal capitalize">
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Chanel</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Christian Dior</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Armani</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Prada</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Louis Vuitton</a></li>
                                    <li><a href="#" class="hover:text-[#c4a052] hover:font-bold transition">Calvin Klein</a></li>
                                </ul>
                            </div>

                        </div>
                    </div>
            </div>
            
        </div>
    </div>
</nav>

<script>
    function toggleSearchPanel() {
        const panel = document.getElementById('search-dropdown');
        const input = document.getElementById('search-input');
        
        if (panel.classList.contains('invisible')) {
            // Tampilkan Panel
            panel.classList.remove('invisible', 'opacity-0', '-translate-y-2');
            panel.classList.add('opacity-100', 'translate-y-0');
            
            // Otomatis fokus ke input setelah animasi selesai
            setTimeout(() => input.focus(), 150);
        } else {
            // Sembunyikan Panel
            panel.classList.add('invisible', 'opacity-0', '-translate-y-2');
            panel.classList.remove('opacity-100', 'translate-y-0');
        }
    }

    // Insting Pertahanan: Tutup dropdown jika pengguna mengklik sembarang tempat di luar kotak pencarian
    document.addEventListener('click', function(event) {
        const panel = document.getElementById('search-dropdown');
        const trigger = event.target.closest('button[onclick="toggleSearchPanel()"]');
        const isClickInside = panel.contains(event.target);
        
        if (!trigger && !isClickInside && !panel.classList.contains('invisible')) {
            toggleSearchPanel();
        }
    });
</script>