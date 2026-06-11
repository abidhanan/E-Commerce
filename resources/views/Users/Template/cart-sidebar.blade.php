<div id="cart-overlay" class="fixed inset-0 bg-black/50 z-[100] hidden transition-opacity opacity-0 backdrop-blur-sm" onclick="toggleCart()"></div>

<div id="cart-sidebar" class="fixed top-0 right-0 h-full w-full sm:w-[400px] bg-white z-[101] transform translate-x-full transition-transform duration-500 ease-in-out flex flex-col shadow-2xl">
    
    <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100">
        <h2 class="text-lg font-light uppercase tracking-[0.2em]">Your Cart (<span id="cart-header-count">0</span>)</h2>
        <button onclick="toggleCart()" class="p-2 text-gray-400 hover:text-black transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <div id="cart-items-container" class="flex-grow overflow-y-auto p-6 space-y-6 scroll-smooth">
        <div id="cart-loader" class="flex justify-center items-center h-full">
            <svg class="animate-spin h-8 w-8 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        </div>
    </div>

    <div class="border-t border-gray-100 p-6 bg-gray-50">
        <div class="flex justify-between items-center mb-6">
            <span class="text-sm font-bold uppercase tracking-widest text-gray-500">Subtotal</span>
            <span id="cart-subtotal" class="text-xl font-bold text-black">Rp 0</span>
        </div>
        <a href="{{ route('checkout.index') }}" class="block w-full bg-black text-white text-center py-4 text-xs font-bold uppercase tracking-widest hover:bg-[#c4a052] transition-colors">
            Proceed to Checkout
        </a>
    </div>
</div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const cartOverlay = document.getElementById('cart-overlay');
    const cartSidebar = document.getElementById('cart-sidebar');

    function toggleCart() {
        const isHidden = cartSidebar.classList.contains('translate-x-full');
        if (isHidden) {
            cartOverlay.classList.remove('hidden');
            setTimeout(() => cartOverlay.classList.remove('opacity-0'), 10);
            cartSidebar.classList.remove('translate-x-full');
            fetchCart(); 
        } else {
            cartOverlay.classList.add('opacity-0');
            cartSidebar.classList.add('translate-x-full');
            setTimeout(() => cartOverlay.classList.add('hidden'), 500);
        }
    }

    async function fetchCart() {
        try {
            const response = await fetch('{{ url("/cart") }}', {
                headers: { 'Accept': 'application/json' }
            });
            const data = await response.json();
            renderCart(data);
        } catch (error) {
            console.error('Gagal memuat keranjang:', error);
        }
    }

    function renderCart(data) {
        const container = document.getElementById('cart-items-container');
        document.getElementById('cart-header-count').innerText = data.count;
        document.getElementById('cart-subtotal').innerText = data.subtotal_formatted;

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
                alert(data.message); 
                return;
            }
            renderCart(data); 
        } catch (error) {
            console.error('Gagal update qty:', error);
        }
    }

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