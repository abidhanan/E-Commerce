<x-layouts.app>
    @php
        // Persiapan Data Gambar
        $primaryImage = $product->images->where('is_primary', true)->first() ?? $product->images->first();
        $primaryImageUrl = $primaryImage ? asset('storage/' . $primaryImage->image) : asset('images/no-image.jpg');
        
        // Persiapan Data Harga Bawaan (Varian Termurah)
        $basePrice = $product->variants->min('price') ?? 0;
    @endphp

    <div class="max-w-screen-xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start relative">
    
            <div class="flex flex-col lg:col-span-7">
                <div class="relative w-full max-w-[500px] mx-auto bg-gray-100 overflow-hidden cursor-crosshair group aspect-[3/4] mb-4"
                    id="main-image-container"
                    onmousemove="zoomImage(event, this)"
                    onmouseleave="resetZoom(this)">
                    
                    <img id="main-product-image"
                        src="{{ $primaryImageUrl }}"
                        class="w-full h-full object-cover transition-transform duration-300 ease-out group-hover:scale-150 origin-center"
                        alt="{{ $product->name }}">
                </div>

                @if($product->images->count() > 0)
                <div class="grid grid-cols-5 gap-3 mb-10 max-w-[500px] mx-auto">
                    @foreach($product->images as $img)
                        <button type="button" onclick="changeMainImage(this)" class="relative aspect-[3/4] bg-gray-200 border-b-2 {{ $img->is_primary ? 'border-black' : 'border-transparent hover:border-gray-400' }} focus:outline-none overflow-hidden group">
                            <img src="{{ asset('storage/' . $img->image) }}" class="w-full h-full object-cover group-hover:opacity-75 transition">
                        </button>
                    @endforeach
                </div>
                @endif
                
                <div class="mt-4 border-t pt-8">
                    <h3 class="text-sm font-bold uppercase tracking-widest mb-4">Description</h3>
                    <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">
                        {{ $product->description ?? 'Tidak ada deskripsi untuk produk ini.' }}
                    </p>
                </div>

                <div class="mt-4 border-t pt-8 grid grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-bold uppercase tracking-widest mb-2 text-gray-900">Spesifikasi Teknis</h3>
                        <ul class="text-sm text-gray-600 space-y-2">
                            <li><span class="font-semibold">Gender:</span> {{ ucfirst($product->gender) }}</li>
                            <li><span class="font-semibold">Weight:</span> {{ $product->weight }}g</li>
                            <li><span class="font-semibold">Intensity:</span> {{ $intensityReference->name ?? ucfirst($product->intensity) }}</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold uppercase tracking-widest mb-2 text-gray-900">Material</h3>
                        <ul class="text-sm text-gray-600 space-y-2 list-disc list-inside">
                            @forelse($materials as $material)
                                <li>{{ $material->name }}</li>
                            @empty
                                <li>Bahan standar</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <div class="flex flex-col lg:col-span-5 sticky top-28">
                <h1 class="text-2xl font-bold text-gray-900 mb-1 leading-tight">{{ $product->name }}</h1>
                <h2 class="text-xs font-bold uppercase tracking-widest text-gray-500 mb-6">
                    {{ $product->category->name ?? 'Uncategorized' }} 
                    @if($product->collection) | {{ $product->collection->name }} @endif
                </h2>

                <div class="mb-8">
                    <div class="flex flex-col mb-3">
                        <div class="flex justify-between items-center w-full">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Pilih Ukuran</span>
                            @if($product->sizeGuide)
                                <a href="#" class="text-[10px] underline text-gray-400">Size Guide</a>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap gap-2">
                        @forelse($product->variants as $variant)
                            <button type="button" 
                                data-size="{{ $variant->size }}"  onclick="selectVariant(this, '{{ $variant->id }}', {{ $variant->price }}, {{ $variant->stock }})"
                                class="variant-btn w-12 h-12 border border-black text-xs font-bold bg-white text-black transition hover:bg-gray-100 disabled:opacity-30 disabled:cursor-not-allowed"
                                {{ $variant->stock <= 0 ? 'disabled' : '' }}
                                title="Stok: {{ $variant->stock }}">
                                {{ $variant->size }}
                            </button>
                        @empty
                            <span class="text-sm text-red-500">Varian belum diatur.</span>
                        @endforelse
                    </div>
                </div>

                <div class="mb-8 border-t pt-6">
                    <div id="display-price" class="text-3xl text-gray-900 font-medium">Rp {{ number_format($basePrice, 0, ',', '.') }}</div>
                </div>

                <form id="add-to-cart-form" class="space-y-4">
                    <input type="hidden" id="selected-variant-id" value="">
                    
                    <div class="flex space-x-3">
                        <div class="flex items-center border border-gray-300 px-4 py-3 flex-shrink-0">
                            <button type="button" onclick="document.getElementById('qty').stepDown()" class="font-bold text-lg">&minus;</button>
                            <input type="number" name="qty" id="qty" value="1" min="1" class="w-12 text-center bg-transparent border-none text-sm font-bold focus:ring-0" readonly>
                            <button type="button" onclick="document.getElementById('qty').stepUp()" class="font-bold text-lg">&plus;</button>
                        </div>
                        <button type="submit" id="add-to-cart-btn" class="flex-1 bg-black text-white text-[12px] font-bold tracking-widest uppercase py-4 hover:bg-gray-800 transition disabled:opacity-50" disabled>
                            PILIH UKURAN DULU
                        </button>
                    </div>
                </form>
                
                @php
                    // Proteksi Radar: Membaca status di database server secara real-time
                    $inWishlist = auth()->check() && \Illuminate\Support\Facades\DB::table('wishlists')
                        ->where('user_id', auth()->id())
                        ->where('product_id', $product->id)
                        ->exists();
                @endphp

                <form id="wishlist-form" action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="mt-4">
                    @csrf
                    @if(!auth()->check())
                        <a href="{{ route('login') }}" class="block text-center w-full border border-black text-[10px] font-bold tracking-widest uppercase py-4 hover:bg-gray-50 transition">
                            Login to Add to Wishlist
                        </a>
                    @else
                        <button type="submit" id="wishlist-btn" class="w-full border border-black text-[10px] font-bold tracking-widest uppercase py-4 transition {{ $inWishlist ? 'bg-black text-white hover:bg-gray-800' : 'bg-transparent text-black hover:bg-gray-50' }}">
                            {{ $inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}
                        </button>
                    @endif
                </form>
            </div> 
        </div> 

        <div class="mt-24 border-t border-gray-200 pt-16">
            <h2 class="text-2xl font-light tracking-wide uppercase text-gray-900 mb-10">Customer Reviews</h2>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <div class="lg:col-span-4">
                    <h3 class="text-sm font-bold uppercase tracking-widest mb-6">Verified Ratings</h3>
                    
                    @php
                        $reviews = $product->verified_reviews ?? collect();
                        $averageRating = $reviews->count() > 0 ? round($reviews->avg('rating'), 1) : 0;
                    @endphp

                    <div class="bg-gray-50 border border-gray-200 p-8 text-center">
                        <div class="text-6xl font-light text-black mb-2">{{ number_format($averageRating, 1) }}</div>
                        <div class="flex justify-center text-[#c4a052] mb-4">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-6 h-6 {{ $i <= round($averageRating) ? 'text-[#c4a052]' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-widest">{{ $reviews->count() }} Ulasan Terverifikasi</p>
                    </div>

                    <p class="text-[10px] text-gray-400 mt-6 leading-relaxed uppercase tracking-widest text-center">
                        Ulasan hanya dapat diberikan oleh pelanggan yang telah menyelesaikan transaksi produk ini.
                    </p>
                </div>

                <div class="lg:col-span-8 space-y-8">
                    @forelse($reviews as $review)
                        <div class="border-b border-gray-100 pb-8">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-sm font-bold text-gray-900">{{ $review->user->name ?? 'Verified Buyer' }}</h4>
                                        <span class="bg-green-100 text-green-800 text-[8px] font-bold uppercase tracking-widest px-2 py-0.5 rounded-sm">Verified</span>
                                    </div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">{{ $review->created_at->format('d M Y') }}</p>
                                </div>
                                <div class="flex items-center bg-black text-white px-2 py-1">
                                    <span class="text-xs font-bold">{{ $review->rating }}</span>
                                    <svg class="w-3 h-3 ml-1 text-[#c4a052]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 leading-relaxed mt-4">
                                {{ $review->comment }}
                            </p>
                        </div>
                    @empty
                        <div class="py-16 text-center border border-gray-100 bg-gray-50 flex flex-col items-center justify-center h-full">
                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            <p class="text-sm font-bold text-gray-500 uppercase tracking-widest">Belum ada ulasan</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div> 
    
    <script>
        function zoomImage(e, container) {
            const img = container.querySelector('img');
            const rect = container.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width) * 100;
            const y = ((e.clientY - rect.top) / rect.height) * 100;
            img.style.transformOrigin = `${x}% ${y}%`;
        }

        function resetZoom(container) {
            const img = container.querySelector('img');
            img.style.transformOrigin = 'center center';
        }

        function changeMainImage(btn) {
            const newSrc = btn.querySelector('img').src;
            document.getElementById('main-product-image').src = newSrc;
            const thumbs = btn.parentElement.querySelectorAll('button');
            thumbs.forEach(t => {
                t.classList.remove('border-black');
                t.classList.add('border-transparent');
            });
            btn.classList.remove('border-transparent');
            btn.classList.add('border-black');
        }

        function selectVariant(btn, variantId, variantPrice, variantStock) {
            document.getElementById('selected-variant-id').value = variantId;
            document.getElementById('display-price').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(variantPrice);

            const buttons = document.querySelectorAll('.variant-btn');
            buttons.forEach(b => {
                b.classList.remove('bg-black', 'text-white');
                b.classList.add('bg-white', 'text-black');
            });

            btn.classList.remove('bg-white', 'text-black');
            btn.classList.add('bg-black', 'text-white');

            const addToCartBtn = document.getElementById('add-to-cart-btn');
            addToCartBtn.disabled = false;
            addToCartBtn.innerText = 'ADD TO CART';
        }

        document.getElementById('add-to-cart-form').addEventListener('submit', async function(e) {
            e.preventDefault(); 

            const btn = document.getElementById('add-to-cart-btn');
            const originalText = btn.innerText;
            btn.innerText = 'ADDING...';
            btn.disabled = true;

            const variantId = document.getElementById('selected-variant-id').value;
            const qty = document.getElementById('qty').value;
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            try {
                const response = await fetch("{{ route('cart.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({ variant_id: variantId, qty: qty })
                });

                const data = await response.json();

                if (!response.ok) {
                    alert(data.message || 'Gagal menambahkan produk ke keranjang.');
                    btn.innerText = originalText;
                    btn.disabled = false;
                    return;
                }

                const navBadge = document.getElementById('navbar-cart-count');
                if (navBadge) { navBadge.innerText = data.count; }

                if (typeof window.toggleCart === 'function') {
                    const cartSidebar = document.getElementById('cart-sidebar');
                    if (cartSidebar && cartSidebar.classList.contains('translate-x-full')) {
                        window.toggleCart(); 
                    } else if (typeof window.fetchCart === 'function') {
                        window.fetchCart(); 
                    }
                }

                btn.innerText = 'ADDED TO CART!';
                setTimeout(() => {
                    btn.innerText = 'ADD TO CART';
                    btn.disabled = false;
                }, 2000);

            } catch (error) {
                console.error('Terjadi kesalahan sistem:', error);
                alert('Sistem gagal terhubung ke server.');
                btn.innerText = 'ERROR, TRY AGAIN';
                btn.disabled = false;
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wishlistForm = document.getElementById('wishlist-form');
            
            if (wishlistForm) {
                wishlistForm.addEventListener('submit', async function(e) {
                    // Mencegat reload halaman standar HTML
                    e.preventDefault();

                    const btn = document.getElementById('wishlist-btn');
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    const url = this.action;

                    // Kunci tombol saat transaksi berlangsung demi mencegah spam click
                    btn.disabled = true;
                    const originalText = btn.innerText;
                    btn.innerText = 'PROCESSING...';

                    try {
                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': token
                            }
                        });

                        // Gerbang Keamanan Otentikasi: Jika session mati di tengah jalan
                        if (response.status === 401) {
                            window.location.href = "{{ route('login') }}";
                            return;
                        }

                        const data = await response.json();

                        if (!response.ok) {
                            alert(data.message || 'Gagal mengeksekusi Wishlist.');
                            btn.innerText = originalText;
                            btn.disabled = false;
                            return;
                        }

                        // MUTASI VISUAL: Merestrukturisasi Tailwind CSS secara instan tanpa reload
                        if (data.status === 'added') {
                            btn.className = "w-full border border-black text-[10px] font-bold tracking-widest uppercase py-4 transition bg-black text-white hover:bg-gray-800";
                            btn.innerText = 'Remove from Wishlist';
                        } else {
                            btn.className = "w-full border border-black text-[10px] font-bold tracking-widest uppercase py-4 transition bg-transparent text-black hover:bg-gray-50";
                            btn.innerText = 'Add to Wishlist';
                        }

                    } catch (error) {
                        console.error('Wishlist System Failure:', error);
                        alert('Gagal terhubung ke peladen. Pastikan konfigurasi jaringan aman.');
                        btn.innerText = originalText;
                    } finally {
                        btn.disabled = false;
                    }
                });
            }

            // Otomasi Seleksi Ukuran Berdasarkan URL (Dari halaman Cart)
            const urlParams = new URLSearchParams(window.location.search);
            const targetSize = urlParams.get('size');
            if (targetSize) {
                const sizeBtn = document.querySelector(`.variant-btn[data-size="${targetSize}"]`);
                if (sizeBtn && !sizeBtn.disabled) { sizeBtn.click(); }
            }
        });
    </script>
</x-layouts.app>