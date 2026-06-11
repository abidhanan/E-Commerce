<x-layouts.app>
    <div class="max-w-screen-xl mx-auto px-6 py-16 flex flex-col md:flex-row gap-12 min-h-screen">
        
        @include('Users.account.sidebar')

        <main class="flex-1 border-l border-gray-200 pl-0 md:pl-12">
            
            <div class="bg-black text-white inline-block px-12 py-4 mb-8">
                <h2 class="text-2xl font-light tracking-widest uppercase">Wishlist</h2>
            </div>

            <div class="space-y-6">
                @forelse($wishlists ?? [] as $item)
                    @php
                        // Menangkap produk dari relasi wishlist
                        $product = $item->product ?? $item; 
                        $basePrice = $product->variants->min('price') ?? 0;
                        $primaryImage = $product->images->where('is_primary', true)->first() ?? $product->images->first();
                        $imagePath = $primaryImage ? asset('storage/' . $primaryImage->image) : asset('images/no-image.jpg');
                    @endphp

                    <div class="flex gap-6 items-start border-b border-gray-200 pb-6 relative group wishlist-item-row">
                        
                        <a href="{{ route('product.show', $product->slug ?? $product->id) }}" class="w-32 h-40 bg-gray-100 flex-shrink-0">
                            <img src="{{ $imagePath }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        </a>

                        <div class="flex-grow flex flex-col justify-between h-40">
                            <div>
                                <a href="{{ route('product.show', $product->slug ?? $product->id) }}" class="text-base font-bold text-gray-900 hover:text-[#c4a052] transition">
                                    {{ $product->name }}
                                </a>
                                <p class="text-xs font-bold uppercase tracking-widest text-gray-500 mt-1">
                                    {{ $product->collection->name ?? ($product->category->name ?? 'BRAND') }}
                                </p>
                            </div>

                            <div class="flex items-end justify-between w-full pr-12">
                                <a href="{{ route('product.show', $product->slug ?? $product->id) }}" class="text-xs font-bold uppercase tracking-widest border-b border-black pb-1 hover:text-[#c4a052] hover:border-[#c4a052] transition">
                                    Lihat Produk
                                </a>
                                <div class="text-xl font-medium italic text-gray-900">
                                    Rp {{ number_format($basePrice, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        <button type="button" 
                                onclick="removeWishlistRow(this, '{{ route('wishlist.toggle', $product->id) }}')" 
                                class="absolute top-0 right-0 text-black hover:text-red-500 transition p-2 cursor-pointer" 
                                title="Hapus dari Wishlist">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L12 8.343l3.172-3.171a4 4 0 115.656 5.656L12 21.657l-8.828-8.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                @empty
                    <div class="text-center py-16">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        <p class="text-gray-500 text-lg">Wishlist Anda masih kosong.</p>
                        <a href="{{ route('shop.index') }}" class="inline-block mt-4 text-sm font-bold uppercase tracking-widest border-b border-black pb-1 hover:text-[#c4a052] transition">Jelajahi Produk</a>
                    </div>
                @endforelse
            </div>

        </main>
    </div>

    <script>
        async function removeWishlistRow(btn, url) {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            // Mencari elemen berdasarkan kelas absolut, bukan .group
            const row = btn.closest('.wishlist-item-row');
            
            row.style.opacity = '0.4';
            row.style.pointerEvents = 'none';

            try {
                const response = await fetch(url, {
                    method: 'POST', 
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token
                    }
                });

                if (response.ok) {
                    row.remove();
                    
                    // Menghitung sisa elemen secara presisi
                    const remainingItems = document.querySelectorAll('.wishlist-item-row');
                    if (remainingItems.length === 0) {
                        window.location.reload();
                    }
                } else {
                    const data = await response.json();
                    alert(data.message || 'Gagal menghapus produk.');
                    row.style.opacity = '1';
                    row.style.pointerEvents = 'auto';
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Koneksi terputus.');
                row.style.opacity = '1';
                row.style.pointerEvents = 'auto';
            }
        }
    </script>
</x-layouts.app>