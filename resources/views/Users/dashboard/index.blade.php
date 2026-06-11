<x-layouts.app>
    
    <x-hero-banner />

    <!-- 1 -->
    <section class="max-w-screen-xl mx-auto px-6 pb-20 -mt-24 md:-mt-32 relative z-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            @forelse($categories->take(3) as $category)
                <a href="{{ route('category.show', $category->slug) }}" class="relative group cursor-pointer border-4 border-white shadow-xl overflow-hidden bg-gray-100 aspect-[3/4] md:aspect-auto md:h-[400px] block">
                    
                    <img src="{{ asset('storage/' . $category->image) }}" 
                         onerror="this.src='https://images.unsplash.com/photo-1642886512785-b5fee9faad7f?q=80&w=764&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'" 
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-700" 
                         alt="{{ $category->name }}">
                    
                    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 w-[80%] text-center">
                        <span class="bg-white px-6 py-2 text-[15px] md:text-[17px] font-bold tracking-widest uppercase shadow-md inline-block w-full">
                            {{ $category->name }}
                        </span>
                    </div>
                    
                    <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition duration-300 z-10"></div>
                </a>
            @empty
                <div class="col-span-3 text-center py-10 bg-white shadow-lg text-gray-500">
                    Kategori belum diatur di Admin Panel.
                </div>
            @endforelse

        </div>
    </section>

    <!-- 2 -->
    <section class="w-full pb-24">
        <div class="relative w-full h-[50vh] md:h-[60vh] bg-black overflow-hidden flex items-center justify-center group">
            
            <img src="{{ asset('images/collab-banner.jpg') }}" 
                 onerror="this.src='https://images.unsplash.com/photo-1469334031218-e382a71b716b?q=80&w=2070&auto=format&fit=crop'" 
                 class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:scale-105 transition duration-700 ease-out" 
                 alt="Exclusive Collaboration">
            
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

            <div class="relative z-10 text-center px-4 mt-20 md:mt-0">
                <p class="text-white text-[10px] md:text-xs font-bold uppercase tracking-[0.4em] mb-3 text-[#c4a052]">
                    Exclusive Collaboration
                </p>
                <h2 class="text-3xl md:text-5xl font-light text-white uppercase tracking-widest mb-8">
                    Clothique <span class="text-sm mx-2">X</span> The Muse
                </h2>
                <a href="{{ route('shop.index') }}" class="inline-block bg-white text-black px-10 py-4 text-xs font-bold uppercase tracking-widest hover:bg-[#c4a052] hover:text-white hover:border-[#c4a052] transition-colors duration-300">
                    Discover The Campaign
                </a>
            </div>

        </div>
    </section>

    <!-- 3 -->
    <section class="max-w-screen-xl mx-auto px-6 pb-24">
        <div class="flex flex-col items-center mb-12">
            <h2 class="text-3xl font-normal uppercase tracking-widest text-center text-gray-900 mb-4">Best Sellers</h2>
            <div class="w-16 h-1 bg-black"></div>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-6">
            @php
                // Logika Pembajakan: Ambil dari Bestseller, jika kosong pinjam dari kategori
                $displayProducts = (isset($bestsellers) && $bestsellers->count() > 0) 
                    ? $bestsellers 
                    : ($categories->firstWhere(fn($cat) => $cat->products->count() > 0)?->products ?? collect());
            @endphp

            @forelse($displayProducts->take(5) as $product)
                <x-product-card :product="$product" />
            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-12 text-gray-500">
                    <p>Katalog produk kosong.</p>
                </div>
            @endforelse
        </div>
        
        <div class="text-center mt-12">
            <a href="{{ route('shop.index') }}" class="inline-block border border-black text-black px-10 py-3 text-xs font-bold uppercase tracking-widest hover:bg-black hover:text-white transition-colors duration-300">
                View All Products
            </a>
        </div>
    </section>

    <!-- 4 -->
    <section class="w-full h-[60vh] relative flex items-center justify-center overflow-hidden">
        <img src="{{ asset('images/bottom-promo.jpg') }}" onerror="this.src='https://images.unsplash.com/photo-1445205170230-053b83016050?q=80&w=2071&auto=format&fit=crop'" class="absolute inset-0 w-full h-full object-cover" alt="Promo">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative z-10 text-center px-4">
            <h2 class="text-3xl md:text-5xl font-light text-white uppercase tracking-widest mb-6">
                Custom Collections
            </h2>
            <a href="{{ route('shop.index') }}" class="inline-block bg-transparent border border-white text-white px-10 py-4 text-xs font-bold uppercase tracking-widest hover:bg-white hover:text-black transition-all duration-300">
                Explore More
            </a>
        </div>
    </section>

    <section class="w-full pb-20">
        <div class="relative w-full h-[50vh] md:h-[70vh] bg-gray-900 overflow-hidden flex items-center justify-center group">
            
            <img src="{{ asset('images/banner-be-unique.jpg') }}" 
                 onerror="this.src='https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=2070&auto=format&fit=crop'" 
                 class="absolute inset-0 w-full h-full object-cover opacity-70 group-hover:scale-105 transition duration-1000 ease-out" 
                 alt="Be Unique Be You">
            
            <div class="absolute inset-0 bg-black/20"></div>

            <div class="relative z-10 text-center px-4">
                <h2 class="text-4xl md:text-6xl font-light text-white uppercase tracking-widest mb-4 drop-shadow-lg">
                    Be Unique, Be You
                </h2>
                <p class="text-white text-xs md:text-sm font-medium uppercase tracking-[0.3em] mb-8 drop-shadow-md">
                    Discover The Collection
                </p>
                <a href="{{ route('shop.index') }}" class="inline-block bg-white text-black px-12 py-4 text-xs font-bold uppercase tracking-widest hover:bg-[#c4a052] hover:text-white transition-colors duration-300">
                    See More
                </a>
            </div>

        </div>
    </section>

    <!-- 5 -->
    <section class="max-w-screen-xl mx-auto px-6 pb-24">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
            
            @php
                // Logika Cerdas: Ambil data dari Collections. 
                // Jika kosong, lompat 3 data pertama dari Categories agar tidak kembar dengan yang di atas.
                $bottomGrids = (isset($collections) && $collections->count() >= 3) 
                    ? $collections->take(3) 
                    : $categories->skip(3)->take(3);
            @endphp

            @forelse($bottomGrids as $gridItem)
                @php 
                    $route = isset($gridItem->slug) 
                        ? (isset($collections) && $collections->count() >= 3 ? route('collection.show', $gridItem->slug) : route('category.show', $gridItem->slug)) 
                        : route('shop.index'); 
                @endphp

                <a href="{{ $route }}" class="relative aspect-[4/5] overflow-hidden group block bg-gray-100">
                    <img src="{{ asset('storage/' . $gridItem->image) }}" 
                         onerror="this.src='https://images.unsplash.com/photo-1445205170230-053b83016050?q=80&w=1000&auto=format&fit=crop'" 
                         class="w-full h-full object-cover transition duration-700 group-hover:scale-105" 
                         alt="{{ $gridItem->name }}">
                    
                    <div class="absolute inset-0 bg-black/10 group-hover:bg-black/30 transition duration-500"></div>
                    
                    <div class="absolute inset-x-0 bottom-8 flex justify-center text-white z-10">
                        <h3 class="text-xl md:text-2xl font-light uppercase tracking-[0.2em] bg-black/60 backdrop-blur-sm px-8 py-3 group-hover:bg-black transition duration-300">
                            {{ $gridItem->name }}
                        </h3>
                    </div>
                </a>
            @empty
                <div class="col-span-3 text-center py-10 text-gray-400">Data Koleksi/Kategori tambahan belum tersedia di database.</div>
            @endforelse

        </div>
    </section>

    <!-- 6 -->
    <section class="w-full">
        <div class="relative w-full h-[50vh] md:h-[60vh] bg-gray-900 overflow-hidden flex flex-col items-center justify-center group">
            
            <img src="{{ asset('images/banner-about.jpg') }}" 
                 onerror="this.src='https://images.unsplash.com/photo-1595777457583-95e059d581b8?q=80&w=2000&auto=format&fit=crop'" 
                 class="absolute inset-0 w-full h-full object-cover opacity-50 group-hover:scale-105 transition duration-1000 ease-out" 
                 alt="Discover Clothique">
            
            <div class="absolute inset-0 bg-black/40"></div>

            <div class="relative z-10 text-center px-6">
                <h2 class="text-3xl md:text-5xl font-light text-white uppercase tracking-[0.2em] mb-4">
                    Discover Clothique
                </h2>
                
                <p class="text-white/80 text-[10px] md:text-xs font-bold uppercase tracking-[0.3em] mb-8">
                    Find out more about our story
                </p>
                
                <a href="{{ url('/about') }}" class="inline-block border border-white text-white px-10 py-4 text-xs font-bold uppercase tracking-widest hover:bg-white hover:text-black transition-colors duration-300">
                    Discover The Brand
                </a>
            </div>

        </div>
    </section>

</x-layouts.app>