@props(['product'])

@php
    // 1. Ambil harga termurah dari seluruh varian yang dimiliki produk ini
    $basePrice = $product->variants->min('price') ?? 0;
    
    // 2. Ambil gambar utama. Jika tidak ada, gunakan gambar kosong sebagai fallback.
    $primaryImage = $product->images->where('is_primary', true)->first();
    $imagePath = $primaryImage ? asset('storage/' . $primaryImage->image) : asset('images/no-image.jpg');
    
    // 3. Batasi deskripsi agar tidak merusak layout
    $shortDescription = Str::limit($product->description ?? 'Deskripsi belum tersedia.', 45);

    // 4. CEK STATUS WISHLIST (Logika baru yang kamu lewatkan)
    $isInWishlist = false;
    if (auth()->check()) {
        // Mengecek apakah di relasi wishlists milik user saat ini, ada product_id ini
        // (Asumsi relasimu di model User bernama 'wishlists')
        $isInWishlist = auth()->user()->wishlists->where('product_id', $product->id)->isNotEmpty();
    }
@endphp

<div class="bg-gray-50 p-2 group rounded-lg transition-all duration-300 ease-out hover:scale-[1.01] hover:shadow-xl relative cursor-pointer overflow-hidden">
    <a href="{{ route('product.show', $product->slug) }}" class="block">
        <div class="relative aspect-[3/4] bg-gray-200 mb-3 overflow-hidden rounded-lg">
            <img src="{{ $imagePath }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" alt="{{ $product->name }}">
            
            @if(!$product->is_active)
                <span class="absolute top-2 left-2 bg-gray-900 text-white text-[10px] px-2 py-1 rounded font-bold uppercase tracking-wider z-10">Draft</span>
            @endif

            <div class="absolute top-2 right-2 z-20">
                @auth
                    <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" onclick="event.stopPropagation();" class="p-1 transition focus:outline-none drop-shadow-md cursor-pointer {{ $isInWishlist ? 'text-red-500' : 'text-gray-300 hover:text-red-500' }}" title="{{ $isInWishlist ? 'Hapus dari Wishlist' : 'Tambah ke Wishlist' }}">
                            <svg class="w-6 h-6" fill="{{ $isInWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" onclick="event.stopPropagation();" class="block p-1 text-gray-300 hover:text-red-500 transition focus:outline-none drop-shadow-md cursor-pointer" title="Login untuk menambahkan ke wishlist">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </a>
                @endauth
            </div>
        </div>

        <div class="px-1 relative pb-6"> 
            <h3 class="text-[17px] font-bold uppercase text-gray-900 leading-tight group-hover:text-[#c4a052] transition pr-8 mt-2">{{ $product->name }}</h3>
            
            <p class="text-[13px] text-gray-500 line-clamp-2 my-1">{{ $shortDescription }}</p>
            
            <div class="mt-2 mb-2">
                @if($basePrice > 0)
                    <span class="text-gray-900 font-bold text-[15px]">Rp {{ number_format($basePrice, 0, ',', '.') }}</span>
                    <span class="text-red-500 line-through text-[12px] block">Rp {{ number_format($basePrice * 1.2, 0, ',', '.') }}</span>
                @else
                    <span class="text-red-600 font-bold text-[15px]">Harga belum diatur</span>
                @endif
            </div>
            
            <div class="absolute bottom-0 left-1 flex items-center text-[13px] font-bold text-gray-700">
                <svg class="w-3 h-3 text-yellow-500 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                5.0 
            </div>
        </div>
    </a>
</div>