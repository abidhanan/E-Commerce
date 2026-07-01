<x-layouts.app>
    
    {{-- Banner Utama dengan Teks Berjalan --}}
    <x-hero-banner :displays="$displays" />

    <!-- 1. GRID KATEGORI UTAMA (featured_home) -->
    <section class="max-w-screen-xl mx-auto px-6 pb-20 -mt-24 md:-mt-32 relative z-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            @forelse($categories->take(3) as $category)
                <a href="{{ route('category.show', $category->slug) }}" class="relative group cursor-pointer border-4 border-white shadow-xl overflow-hidden bg-gray-100 aspect-[3/4] md:aspect-auto md:h-[400px] block">
                    
                    <img src="{{ asset('storage/' . $category->img) }}" 
                         onerror="this.src='https://images.unsplash.com/photo-1642886512785-b5fee9faad7f?q=80&w=764&auto=format&fit=crop'" 
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-700" 
                         alt="{{ $category->name }}">
                    
                    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 w-[80%] text-center">
                        <span class="bg-white px-6 py-2 text-[15px] md:text-[17px] font-bold tracking-widest uppercase shadow-md inline-block w-full transition-colors group-hover:bg-black group-hover:text-white">
                            {{ $category->name }}
                        </span>
                    </div>
                    
                    <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition duration-300 z-10"></div>
                </a>
            @empty
                <div class="col-span-3 text-center py-12 bg-white shadow-md text-gray-400 uppercase tracking-widest text-xs font-bold border border-gray-200">
                    Kategori utama belum diatur di Admin Panel.
                </div>
            @endforelse

        </div>
    </section>

    <!-- 2. SECTION KAMPANYE KOLABORASI EKSKLUSIF (Hero 2) -->
    @php
        // Kebijakan Kontrol Mutlak: Hanya tayang jika data valid dan diaktifkan oleh admin
        $showCollaboration = isset($displays) && !empty($displays->image_2_path) && !empty($displays->image_2_is_active);
    @endphp

    @if($showCollaboration)
        <section class="w-full pb-24">
            <div class="relative w-full h-[60vh] md:h-[75vh] bg-black overflow-hidden flex items-center justify-center group">
                
                <img src="{{ asset('storage/' . $displays->image_2_path) }}" 
                     class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:scale-105 transition duration-700 ease-out" 
                     alt="{{ strip_tags($displays->image_2_title ?? 'Exclusive Collaboration') }}">
                
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

                <div class="relative z-10 text-center px-4 mt-20 md:mt-0">
                    
                    <p class="text-white text-[10px] md:text-xs font-bold uppercase tracking-[0.4em] mb-3 text-[#c4a052]">
                        {{ $displays->image_2_sub_title ?? 'Exclusive Collaboration' }}
                    </p>
                    
                    <h2 class="text-3xl md:text-5xl font-light text-white uppercase tracking-widest mb-8">
                        {!! $displays->image_2_title ?? 'Clothique <span class="text-sm mx-2">X</span> The Muse' !!}
                    </h2>
                    
                    <a href="{{ !empty($displays->image_2_link) ? $displays->image_2_link : route('shop.index') }}" class="inline-block bg-white text-black px-10 py-4 text-xs font-bold uppercase tracking-widest hover:bg-[#c4a052] hover:text-white hover:border-[#c4a052] transition-colors duration-300">
                        Discover The Campaign
                    </a>
                    
                </div>
            </div>
        </section>
    @endif

    <!-- 3. SECTION BEST SELLERS -->
    <section class="max-w-screen-xl mx-auto px-6 pb-24">
        <div class="flex flex-col items-center mb-12">
            <h2 class="text-3xl font-normal uppercase tracking-widest text-center text-gray-900 mb-4">Best Sellers</h2>
            <div class="w-16 h-1 bg-black"></div>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-6">
            @php
                // Jaring Pengaman Memori: Ambil data produk terpilih, batasi maksimal 5 item
                $displayProducts = (isset($bestsellers) && $bestsellers->count() > 0) 
                    ? $bestsellers->take(5) 
                    : ($categories->firstWhere(fn($cat) => $cat->products->count() > 0)?->products->take(5) ?? collect());
            @endphp

            @forelse($displayProducts as $product)
                <x-product-card :product="$product" />
            @empty
                <div class="col-span-full text-center py-16 text-gray-400 text-xs uppercase tracking-widest font-bold border border-dashed border-gray-200">
                    Katalog produk bestseller masih kosong.
                </div>
            @endforelse
        </div>
        
        <div class="text-center mt-12">
            <a href="{{ route('shop.index') }}" class="inline-block border border-black text-black px-10 py-3 text-xs font-bold uppercase tracking-widest hover:bg-black hover:text-white transition-colors duration-300">
                View All Products
            </a>
        </div>
    </section>

    <!-- 4. SECTION CUSTOM SHOWCASE COLLECTION (Pilihan Admin) -->
    @php
        $hasFeatured = isset($featuredCollection) && $featuredCollection;
        
        $bannerUrl = $hasFeatured && $featuredCollection->img 
            ? asset('storage/' . $featuredCollection->img) 
            : asset('images/bottom-promo.jpg');
            
        $titleText = $hasFeatured ? $featuredCollection->name : 'Custom Collections';
        
        $targetUrl = $hasFeatured 
            ? route('collection.show', $featuredCollection->slug) 
            : route('shop.index');
    @endphp

    <section class="w-full h-[50vh] relative flex items-center justify-center overflow-hidden mb-24">
        <img src="{{ $bannerUrl }}" 
            onerror="this.src='https://images.unsplash.com/photo-1445205170230-053b83016050?q=80&w=2071&auto=format&fit=crop'" 
            class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 hover:scale-105"
            alt="{{ $titleText }}">
            
        <div class="absolute inset-0 bg-black/40"></div>
        
        <div class="relative z-10 text-center px-4">
            <h2 class="text-3xl md:text-5xl font-light text-white uppercase tracking-[0.2em] mb-6">
                {{ $titleText }}
            </h2>
            
            <a href="{{ $targetUrl }}" class="inline-block bg-transparent border border-white text-white px-10 py-4 text-xs font-bold uppercase tracking-widest hover:bg-white hover:text-black transition-all duration-300">
                {{ $hasFeatured ? 'Explore Collection' : 'Explore More' }}
            </a>
        </div>
    </section>

    <!-- 5. GRID SERI / KOLEKSI BAWAH -->
    <section class="max-w-screen-xl mx-auto px-6 pb-24">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
            
            @php
                // Logika Hibrida: Gunakan 3 Collection teratas, jika kosong gunakan pecahan sisa kategori
                $bottomGrids = (isset($collections) && $collections->count() >= 3) 
                    ? $collections->take(3) 
                    : $categories->skip(3)->take(3);
            @endphp

            @forelse($bottomGrids as $gridItem)
                @php 
                    $isCollection = isset($collections) && $collections->count() >= 3;
                    
                    $route = isset($gridItem->slug) 
                        ? ($isCollection ? route('collection.show', $gridItem->slug) : route('category.show', $gridItem->slug)) 
                        : route('shop.index');
                        
                    // PERBAIKAN ARSITEKTUR KUNCI: Menyelaraskan nama kolom properti gambar ($gridItem->img)
                    $gridImg = $isCollection ? $gridItem->img : $gridItem->img; 
                @endphp

                <a href="{{ $route }}" class="relative aspect-[4/5] overflow-hidden group block bg-gray-100 shadow-sm border border-gray-100">
                    <img src="{{ $gridImg ? asset('storage/' . $gridImg) : 'https://images.unsplash.com/photo-1445205170230-053b83016050?q=80&w=1000&auto=format&fit=crop' }}" 
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
                <div class="col-span-3 text-center py-12 text-gray-400 text-xs uppercase tracking-widest font-bold border border-dashed border-gray-200">
                    Data Koleksi tambahan belum tersedia di database.
                </div>
            @endforelse

        </div>
    </section>

    <!-- 6. SECTION BRAND STORY BRAND (About Us Link) -->
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