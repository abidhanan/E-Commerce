<script>
    // Konfigurasi CSRF Token untuk Laravel
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const cartOverlay = document.getElementById('cart-overlay');
    const cartSidebar = document.getElementById('cart-sidebar');

    function toggleCart() {
        const isHidden = cartSidebar.classList.contains('translate-x-full');
        if (isHidden) {
            cartOverlay.classList.remove('hidden');
            setTimeout(() => cartOverlay.classList.remove('opacity-0'), 10);
            cartSidebar.classList.remove('translate-x-full');
            fetchCart(); // Tarik data terbaru setiap kali dibuka
        } else {
            cartOverlay.classList.add('opacity-0');
            cartSidebar.classList.add('translate-x-full');
            setTimeout(() => cartOverlay.classList.add('hidden'), 500);
        }
    }

    // Fungsi Utama Menarik Data dari CartController@index
    async function fetchCart() {
        try {
            // Asumsi rute kamu adalah GET /cart. Sesuaikan jika beda di web.php
            const response = await fetch('{{ url("/cart") }}', {
                headers: { 'Accept': 'application/json' }
            });
            const data = await response.json();
            renderCart(data);
        } catch (error) {
            console.error('Gagal memuat keranjang:', error);
        }
    }

    // Menggambar HTML berdasarkan data JSON
    function renderCart(data) {
        const container = document.getElementById('cart-items-container');
        document.getElementById('cart-header-count').innerText = data.count;
        document.getElementById('cart-subtotal').innerText = data.subtotal_formatted;

        // Update indikator cart di Navbar (jika kamu punya elemen dengan id 'navbar-cart-count')
        const navCount = document.getElementById('navbar-cart-count');
        if(navCount) navCount.innerText = data.count;

        if (data.items.length === 0) {
            container.innerHTML = `
                <div class="flex flex-col items-center justify-center h-full text-gray-400">
                    <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <p class="text-xs uppercase tracking-widest">Keranjang Kosong</p>
                </div>
            `;
            return;
        }

        let html = '';
        data.items.forEach(item => {
            html += `
                <div class="flex gap-4 items-center group">
                    <a href="${item.url}" class="w-20 h-24 bg-gray-100 flex-shrink-0 overflow-hidden block">
                        <img src="${item.image}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" alt="${item.product_name}">
                    </a>
                    <div class="flex-grow">
                        <a href="${item.url}" class="text-sm font-bold uppercase tracking-wide hover:text-[#c4a052] transition">${item.product_name}</a>
                        <p class="text-xs text-gray-500 mt-1 uppercase tracking-widest">Size: ${item.variant_name}</p>
                        <p class="text-sm font-bold text-gray-900 mt-2">${item.price_formatted}</p>
                    </div>
                    <div class="flex flex-col items-end gap-3">
                        <button onclick="removeCartItem(${item.id})" class="text-gray-400 hover:text-red-500 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                        <div class="flex items-center border border-gray-200">
                            <button onclick="updateQty(${item.id}, ${item.qty - 1})" class="px-2 py-1 hover:bg-gray-100 transition ${item.qty <= 1 ? 'opacity-50 cursor-not-allowed' : ''}" ${item.qty <= 1 ? 'disabled' : ''}>-</button>
                            <span class="px-2 py-1 text-xs font-bold w-8 text-center">${item.qty}</span>
                            <button onclick="updateQty(${item.id}, ${item.qty + 1})" class="px-2 py-1 hover:bg-gray-100 transition ${item.qty >= item.stock ? 'opacity-50 cursor-not-allowed' : ''}" ${item.qty >= item.stock ? 'disabled' : ''}>+</button>
                        </div>
                    </div>
                </div>
            `;
        });
        container.innerHTML = html;
    }

    // Fungsi Update Qty ke CartController@update
    async function updateQty(itemId, newQty) {
        try {
            const response = await fetch(`{{ url('/cart') }}/${itemId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ qty: newQty })
            });
            const data = await response.json();
            
            if (!response.ok) {
                alert(data.message); // Tampilkan error stok dari backend
                return;
            }
            renderCart(data); // Langsung render JSON baru
        } catch (error) {
            console.error('Gagal update qty:', error);
        }
    }

    // Fungsi Hapus ke CartController@destroy
    async function removeCartItem(itemId) {
        try {
            const response = await fetch(`{{ url('/cart') }}/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            const data = await response.json();
            renderCart(data);
        } catch (error) {
            console.error('Gagal hapus item:', error);
        }
    }
</script>