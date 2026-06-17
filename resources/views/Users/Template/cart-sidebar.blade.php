<div id="cart-overlay" class="fixed inset-0 bg-black/50 z-[100] hidden transition-opacity opacity-0 backdrop-blur-sm" onclick="toggleCart()"></div>

<div id="cart-sidebar" class="fixed top-0 right-0 h-full w-full sm:w-[400px] bg-white z-[101] transform translate-x-full transition-transform duration-500 ease-in-out flex flex-col shadow-2xl">
    
    <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100">
        <h2 class="text-lg font-light uppercase tracking-[0.2em]">Your Cart (<span id="cart-header-count">0</span>)</h2>
        <button onclick="toggleCart()" class="p-2 text-gray-400 hover:text-black transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    @auth
        <div class="px-6 pt-4">
            <label class="flex items-center gap-2 cursor-pointer text-xs font-bold uppercase tracking-widest text-gray-600">
                <input type="checkbox" id="select-all-cart" class="w-4 h-4 accent-black" checked>
                Pilih Semua Produk
            </label>
        </div>

        <form id="cart-checkout-form" class="flex-grow overflow-y-auto p-6 space-y-6 scroll-smooth">
            <div id="cart-items-container" class="space-y-6">
                <div id="cart-loader" class="flex justify-center items-center h-full">
                    <svg class="animate-spin h-8 w-8 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </div>
            </div>
        </form>

        <div class="border-t border-gray-100 p-6 bg-gray-50">
            <div class="flex justify-between items-center mb-6">
                <span class="text-sm font-bold uppercase tracking-widest text-gray-500">Subtotal</span>
                <span id="cart-subtotal" class="text-xl font-bold text-black">Rp 0</span>
            </div>
            <button type="button" onclick="proceedToCheckout()" class="block w-full bg-black text-white text-center py-4 text-xs font-bold uppercase tracking-widest hover:bg-[#c4a052] transition-colors">
                Proceed to Checkout
            </button>
        </div>
    @endauth

    @guest
        <div class="flex-grow flex flex-col items-center justify-center p-8 text-center bg-gray-50">
            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-6 border border-gray-200">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2 uppercase tracking-wide">Akses Terbatas</h3>
            <p class="text-sm text-gray-500 mb-8 leading-relaxed">
                Silakan login atau register terlebih dahulu untuk menambahkan produk dan melihat isi keranjang belanja.
            </p>
            <div class="flex flex-col w-full gap-3">
                <a href="{{ route('login') }}" class="w-full bg-black text-white py-4 text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition">
                    Login ke Akun
                </a>
                <a href="{{ route('register') }}" class="w-full bg-white border border-black text-black py-4 text-xs font-bold uppercase tracking-widest hover:bg-gray-50 transition">
                    Buat Akun Baru
                </a>
            </div>
        </div>
    @endguest
</div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const cartOverlay = document.getElementById('cart-overlay');
    const cartSidebar = document.getElementById('cart-sidebar');
    const selectAll = document.getElementById('select-all-cart');

    function toggleCart() {
        const isHidden = cartSidebar.classList.contains('translate-x-full');
        if (isHidden) {
            cartOverlay.classList.remove('hidden');
            setTimeout(() => cartOverlay.classList.remove('opacity-0'), 10);
            cartSidebar.classList.remove('translate-x-full');
            
            // KUNCI MUTLAK: Hanya tarik data jika user terotentikasi
            @auth
                fetchCart(); 
            @endauth
        } else {
            cartOverlay.classList.add('opacity-0');
            cartSidebar.classList.add('translate-x-full');
            setTimeout(() => cartOverlay.classList.add('hidden'), 500);
        }
    }

    // Toggle Select All & Hitung Ulang
    selectAll.addEventListener('change', (e) => {
        document.querySelectorAll('.cart-item-checkbox').forEach(cb => {
            cb.checked = e.target.checked;
        });
        calculateSelectedSubtotal(); // Panggil kalkulator saat Select All ditekan
    });

    async function fetchCart() {
        try {
            // Membunuh cache browser secara mutlak dengan parameter waktu (timestamp)
            const response = await fetch(`{{ url('/cart') }}?t=${new Date().getTime()}`, { 
                headers: { 
                    'Accept': 'application/json',
                    'Cache-Control': 'no-cache, no-store, must-revalidate'
                }
            });
            const data = await response.json();
            renderCart(data);
        } catch (error) { 
            console.error('Gagal memuat keranjang:', error); 
        }
    }

    // ==========================================
    // INSTING SINKRONISASI OTOMATIS
    // ==========================================
    
    // 1. Eksekusi setiap kali halaman selesai dimuat
    document.addEventListener('DOMContentLoaded', fetchCart);

    // 2. Eksekusi setiap kali pengguna menggunakan tombol "Back" atau "Forward"
    window.addEventListener('pageshow', function(event) {
        // event.persisted bernilai true jika halaman dimuat dari memori RAM browser (BFCache)
        if (event.persisted) {
            fetchCart();
        }
    });

    function renderCart(data) {
        const container = document.getElementById('cart-items-container');
        document.getElementById('cart-header-count').innerText = data.count;

        const navCount = document.getElementById('navbar-cart-count');
        if(navCount) navCount.innerText = data.count;

        if (data.items.length === 0) {
            container.innerHTML = '<div class="text-center text-xs uppercase text-gray-400 py-10">Keranjang Kosong</div>';
            document.getElementById('cart-subtotal').innerText = 'Rp 0';
            return;
        }

        let html = '';
        data.items.forEach(item => {
            // MERAKIT URL CERDAS: Menambahkan parameter '?size=' ke tautan bawaan
            let finalUrl = item.url;
            try {
                let urlObj = new URL(item.url, window.location.origin);
                urlObj.searchParams.set('size', item.variant_name);
                finalUrl = urlObj.toString();
            } catch(e) {
                console.error("URL tidak valid", e);
            }

            html += `
                <div class="flex gap-4 items-center group">
                    <input type="checkbox" name="items[]" value="${item.id}" data-price="${item.price}" data-qty="${item.qty}" class="cart-item-checkbox w-4 h-4 accent-black" checked onchange="calculateSelectedSubtotal()">
                    
                    <a href="${finalUrl}" class="w-20 h-24 bg-gray-100 flex-shrink-0 overflow-hidden block">
                        <img src="${item.image}" class="w-full h-full object-cover" alt="${item.product_name}">
                    </a>
                    
                    <div class="flex-grow">
                        <a href="${finalUrl}" class="text-sm font-bold uppercase hover:text-[#c4a052] transition">${item.product_name}</a>
                        <p class="text-[10px] text-gray-500 uppercase mt-1">Size: ${item.variant_name}</p>
                        <p class="text-sm font-bold text-gray-900 mt-1">${item.price_formatted}</p>
                    </div>
                    
                    <div class="flex flex-col items-end gap-2">
                        <button type="button" onclick="removeCartItem(${item.id})" class="text-gray-400 hover:text-red-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                        <div class="flex items-center border border-gray-200">
                            <button type="button" onclick="updateQty(${item.id}, ${item.qty - 1})" class="px-2 py-0.5">-</button>
                            <span class="px-2 py-0.5 text-xs font-bold">${item.qty}</span>
                            <button type="button" onclick="updateQty(${item.id}, ${item.qty + 1})" class="px-2 py-0.5">+</button>
                        </div>
                    </div>
                </div>`;
        });
        
        container.innerHTML = html;
        
        // Kembalikan status Select All menjadi true setiap kali keranjang dimuat ulang
        selectAll.checked = true;
        
        // Hitung total seketika setelah render selesai
        calculateSelectedSubtotal();
    }

    // MESIN KALKULATOR FRONTEND (Inti Solusi)
    function calculateSelectedSubtotal() {
        let total = 0;
        let allChecked = true;
        const checkboxes = document.querySelectorAll('.cart-item-checkbox');
        
        if(checkboxes.length === 0) allChecked = false;

        checkboxes.forEach(cb => {
            if (cb.checked) {
                // Ambil harga dan qty mentah dari atribut data
                const price = parseFloat(cb.getAttribute('data-price')) || 0;
                const qty = parseInt(cb.getAttribute('data-qty')) || 0;
                total += (price * qty);
            } else {
                allChecked = false; // Jika ada 1 yang tidak dicentang, matikan centang 'Select All'
            }
        });

        // Sinkronisasi visual checkbox 'Select All'
        selectAll.checked = allChecked;

        // Tulis ulang total harga ke layar dengan format Rupiah
        document.getElementById('cart-subtotal').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }

    async function updateQty(itemId, newQty) {
        const response = await fetch(`{{ url('/cart') }}/${itemId}`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ qty: newQty })
        });
        if (response.ok) fetchCart();
    }

    async function removeCartItem(itemId) {
        await fetch(`{{ url('/cart') }}/${itemId}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken }});
        fetchCart();
    }

    function proceedToCheckout() {
        const selected = Array.from(document.querySelectorAll('.cart-item-checkbox:checked')).map(cb => cb.value);
        if (selected.length === 0) return alert('Pilih produk terlebih dahulu!');
        
        window.location.href = `{{ route('checkout.index') }}?items=${selected.join(',')}`;
    }
</script>