<x-layouts.app>
    <div class="pb-20">
        
        <div class="relative w-full h-[40vh] md:h-[50vh] bg-gray-900 overflow-hidden">
            <img src="{{ isset($category) ? asset('storage/' . $category->img) : (isset($collection) ? asset('storage/' . $collection->img) : asset('images/shop-banner.jpg')) }}" 
                 onerror="this.src='https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?auto=format&fit=crop&q=80&w=2000'" 
                 class="w-full h-full object-cover filter brightness-50" alt="Shop Banner">
            
            <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-6">
                <h1 class="text-4xl md:text-6xl font-light tracking-[0.2em] uppercase mb-4">
                    {{ $pageTitle ?? 'All Products' }}
                </h1>
                <p class="text-xs md:text-sm font-medium tracking-widest uppercase text-gray-300 max-w-2xl">
                    {{ $pageDescription ?? 'Discover our complete collection of technical apparel.' }}
                </p>
            </div>
        </div>

        <div class="max-w-screen-xl mx-auto px-6 mt-12 flex justify-between items-center border-b border-gray-200 pb-4">
            <h2 class="text-xs font-bold uppercase tracking-widest text-gray-500">
                Showing <span class="text-black font-extrabold">{{ $products->total() ?? $products->count() }}</span> products
            </h2>
            
            <form method="GET" action="{{ url()->current() }}" class="flex items-center">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                
                <select name="sort" onchange="this.form.submit()" class="text-xs font-bold uppercase tracking-widest border-none bg-transparent focus:ring-0 cursor-pointer text-right outline-none">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>New Arrivals</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                </select>
            </form>
        </div>

        <div class="max-w-screen-xl mx-auto px-6 py-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-8 gap-y-16">
                
                @forelse($products as $product)
                    @php
                        // Menarik gambar utama dan harga dasar varian
                        $primaryImage = $product->images->where('is_primary', true)->first() ?? $product->images->first();
                        $imagePath = $primaryImage ? asset('storage/' . $primaryImage->image) : asset('images/no-image.jpg');
                        $basePrice = $product->variants->min('price') ?? 0;
                    @endphp

                    <div class="group cursor-pointer flex flex-col">
                        <a href="{{ route('product.show', $product->slug) }}" class="relative w-full aspect-[3/4] bg-gray-100 overflow-hidden mb-4 block">
                            <img src="{{ $imagePath }}" class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110" alt="{{ $product->name }}">
                            
                            <div class="absolute bottom-0 left-0 w-full bg-black text-white text-center text-xs font-bold tracking-widest uppercase py-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                View Details
                            </div>
                        </a>
                        
                        <a href="{{ route('product.show', $product->slug) }}" class="text-sm font-bold uppercase tracking-wider text-gray-900 truncate hover:text-[#c4a052] transition">
                            {{ $product->name }}
                        </a>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-1">
                            {{ $product->category->name ?? 'Gear' }}
                        </p>
                        <p class="text-sm font-bold mt-2 text-gray-900">
                            Rp {{ number_format($basePrice, 0, ',', '.') }}
                        </p>
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-24 text-gray-400">
                        <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <p class="text-sm uppercase tracking-widest font-bold">Katalog Kosong</p>
                        <p class="text-xs mt-2">Tidak ada produk yang sesuai dengan filter atau pencarian Anda.</p>
                    </div>
                @endforelse

            </div>

            @if(isset($products) && $products->hasPages())
                <div class="mt-16 border-t border-gray-200 pt-10">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

    </div>
</x-layouts.app>