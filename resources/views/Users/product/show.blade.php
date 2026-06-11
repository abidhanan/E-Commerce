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
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Pilih Ukuran</span>
                        @if($product->sizeGuide)
                            <a href="#" class="text-[10px] underline text-gray-400">Size Guide</a>
                        @endif
                    </div>
                    
                    <div class="flex flex-wrap gap-2">
                        @forelse($product->variants as $variant)
                            <button type="button" 
                                onclick="selectVariant(this, '{{ $variant->id }}', {{ $variant->price }}, {{ $variant->stock }})"
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
                
                <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full border border-black text-[10px] font-bold tracking-widest uppercase py-4 hover:bg-gray-50 transition">
                        Add to Wishlist
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Logika Interaksi Gambar (Zoom)
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

        // Logika Pergantian Thumbnail
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

        // Logika Eksekusi Varian (Harga & Formulir)
        function selectVariant(btn, variantId, variantPrice, variantStock) {
            // 1. Masukkan ID ke dalam form agar bisa masuk cart
            document.getElementById('selected-variant-id').value = variantId;

            // 2. Ubah tampilan harga sesuai ukuran yang diklik
            document.getElementById('display-price').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(variantPrice);

            // 3. Reset warna semua tombol ukuran
            const buttons = document.querySelectorAll('.variant-btn');
            buttons.forEach(b => {
                b.classList.remove('bg-black', 'text-white');
                b.classList.add('bg-white', 'text-black');
            });

            // 4. Hitamkan tombol yang sedang dipilih
            btn.classList.remove('bg-white', 'text-black');
            btn.classList.add('bg-black', 'text-white');

            // 5. Hidupkan tombol Cart
            const addToCartBtn = document.getElementById('add-to-cart-btn');
            addToCartBtn.disabled = false;
            addToCartBtn.innerText = 'ADD TO CART';
        }

            // Mencegat tombol Submit "ADD TO CART"
        document.getElementById('add-to-cart-form').addEventListener('submit', async function(e) {
            // 1. KUNCI UTAMA: Cegah browser melakukan reload halaman
            e.preventDefault(); 

            const btn = document.getElementById('add-to-cart-btn');
            const originalText = btn.innerText;
            btn.innerText = 'ADDING...';
            btn.disabled = true;

            const variantId = document.getElementById('selected-variant-id').value;
            const qty = document.getElementById('qty').value;
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            try {
                // Tembak data ke backend
                const response = await fetch("{{ route('cart.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({
                        variant_id: variantId,
                        qty: qty
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    alert(data.message || 'Gagal menambahkan produk ke keranjang.');
                    btn.innerText = originalText;
                    btn.disabled = false;
                    return;
                }

                // ==========================================
                // TITIK EKSEKUSI MUTLAK (Memaksa UI Berubah)
                // ==========================================

                // 1. Paksa ubah angka di Navbar secara instan detik itu juga
                const navBadge = document.getElementById('navbar-cart-count');
                if (navBadge) {
                    navBadge.innerText = data.count; // Mengambil total dari JSON backend Ela
                }

                // 2. Paksa buka Sidebar Cart (Memanggil otak sidebar secara langsung)
                if (typeof window.toggleCart === 'function') {
                    const cartSidebar = document.getElementById('cart-sidebar');
                    if (cartSidebar && cartSidebar.classList.contains('translate-x-full')) {
                        window.toggleCart(); // Buka laci & otomatis muat data terbaru
                    } else if (typeof window.fetchCart === 'function') {
                        window.fetchCart(); // Jika laci sudah terbuka, cukup refresh datanya
                    }
                } else {
                    console.warn('Sistem gagal menemukan fungsi toggleCart. Pastikan file cart-sidebar.blade.php sudah di-include di app.blade.php.');
                }

                // 3. Kembalikan kondisi tombol
                btn.innerText = 'ADDED TO CART!';
                setTimeout(() => {
                    btn.innerText = 'ADD TO CART';
                    btn.disabled = false;
                }, 2000);

            } catch (error) {
                console.error('Terjadi kesalahan sistem:', error);
                alert('Sistem gagal terhubung ke server. Tekan F12 dan cek tab Console.');
                btn.innerText = 'ERROR, TRY AGAIN';
                btn.disabled = false;
            }
        });
    </script>
</x-layouts.app>