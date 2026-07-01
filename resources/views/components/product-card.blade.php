@props(['product'])

@if(!$product)
    {{-- Jangan render apapun, atau tampilkan placeholder "Produk Tidak Tersedia" --}}
@else
    @php
        $basePrice = $product->variants ? $product->variants->min('price') : 0;
        $primaryImage = $product->images ? $product->images->where('is_primary', true)->first() : null;
        $imagePath = $primaryImage ? asset('storage/' . $primaryImage->image) : asset('images/no-image.jpg');
        $shortDescription = Str::limit($product->description ?? 'Deskripsi belum tersedia.', 45);
        $isInWishlist = auth()->check() && auth()->user()->wishlists->where('product_id', $product->id)->isNotEmpty();
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
                        <button type="button" 
                                data-url="{{ route('wishlist.toggle', $product->id) }}"
                                class="wishlist-btn p-1 transition focus:outline-none drop-shadow-md cursor-pointer {{ $isInWishlist ? 'text-red-500' : 'text-gray-300 hover:text-red-500' }}">
                            <svg class="w-6 h-6 heart-icon" fill="{{ $isInWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="block p-1 text-gray-300 hover:text-red-500 transition">
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
                    @else
                        <span class="text-red-600 font-bold text-[15px]">Harga belum diatur</span>
                    @endif
                </div>
            </div>
        </a>
    </div>
@endif

{{-- Script Lokal untuk menangani klik tombol tanpa reload --}}
<script>
(function() {
    document.querySelectorAll('.wishlist-btn').forEach(btn => {
        btn.onclick = async function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const url = this.getAttribute('data-url');
            const icon = this.querySelector('.heart-icon');
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': token
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.status === 'added') {
                        this.classList.replace('text-gray-300', 'text-red-500');
                        icon.setAttribute('fill', 'currentColor');
                    } else {
                        this.classList.replace('text-red-500', 'text-gray-300');
                        icon.setAttribute('fill', 'none');
                    }
                }
            } catch (err) {
                console.error("Gagal update wishlist:", err);
            }
        };
    });
})();
</script>