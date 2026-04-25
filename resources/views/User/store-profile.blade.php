<x-layouts.app>
    <div id="store-profile-app" class="min-h-screen bg-white hidden">
        
        <div class="relative w-full h-64 md:h-80 bg-gray-200">
            <img id="store-banner" src="" class="w-full h-full object-cover" alt="Store Banner">
            
            <div class="absolute -bottom-16 left-6 md:left-12 flex items-end space-x-6">
                <div class="w-32 h-32 rounded-full border-4 border-white bg-white shadow-lg overflow-hidden">
                    <img id="store-logo" src="" class="w-full h-full object-contain" alt="Store Logo">
                </div>
                <div class="mb-4 bg-white/90 backdrop-blur px-4 py-2 rounded-lg shadow-sm">
                    <h1 id="store-name" class="text-2xl font-bold uppercase tracking-widest text-gray-900">Loading...</h1>
                    <p id="store-meta" class="text-xs font-bold text-gray-500 mt-1 uppercase tracking-wider">Loading...</p>
                </div>
            </div>
            
            <div class="absolute bottom-4 right-6 md:right-12">
                <button class="bg-black text-white text-xs font-bold uppercase tracking-widest px-8 py-3 hover:bg-gray-800 transition">Follow +</button>
            </div>
        </div>

        <div class="max-w-screen-xl mx-auto px-6 mt-24 mb-8 border-b border-gray-200">
            <div class="flex space-x-8 text-sm font-bold uppercase tracking-widest">
                <a href="#" class="pb-4 border-b-2 border-transparent hover:border-black transition text-gray-500">Home</a>
                <a href="#" class="pb-4 border-b-2 border-black text-black">All Product</a>
                <a href="#" class="pb-4 border-b-2 border-transparent hover:border-black transition text-gray-500">Kategori</a>
            </div>
        </div>

        <div class="max-w-screen-xl mx-auto px-6 mb-20">
            <div class="flex justify-between items-center mb-6">
                <p class="text-sm text-gray-500">Showing <span id="product-count" class="font-bold text-black">0</span> products</p>
                
                <div class="flex items-center space-x-2 text-sm">
                    <span class="text-gray-500">Sort by:</span>
                    <select class="border border-gray-300 px-3 py-1 font-bold text-black focus:outline-none cursor-pointer bg-white">
                        <option>Most Relevant</option>
                        <option>Newest</option>
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
                    </select>
                </div>
            </div>

            <div id="product-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. INI ADALAH MOCK DATA (KONTRAK API). 
            // Nanti, data ini akan datang dari fetch('/api/stores/chanel') buatan temanmu.
            const mockApiData = {
                status: "success",
                store: {
                    name: "CHANEL Official Store",
                    location: "Paris, France",
                    followers: "100k",
                    rating: "4.5/5",
                    logo_url: "https://upload.wikimedia.org/wikipedia/en/thumb/9/92/Chanel_logo_interlocking_cs.svg/1200px-Chanel_logo_interlocking_cs.svg.png",
                    banner_url: "https://images.unsplash.com/photo-1549439602-43ebca2327af?q=80&w=2070&auto=format&fit=crop" // Gambar placeholder estetik
                },
                products: [
                    { id: 1, name: "Vintage Ribbon Advantage", price: 350000, old_price: 499000, discount: 33, image: "https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=500&q=80" },
                    { id: 2, name: "Classic Tweed Jacket", price: 850000, old_price: null, discount: null, image: "https://images.unsplash.com/photo-1550639524-a6f58345a059?w=500&q=80" },
                    { id: 3, name: "Noir Elegance Coat", price: 1200000, old_price: 1500000, discount: 20, image: "https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=500&q=80" },
                    { id: 4, name: "Ivory Silk Blouse", price: 450000, old_price: null, discount: null, image: "https://images.unsplash.com/photo-1434389673926-805175653457?w=500&q=80" }
                ]
            };

            // 2. Fungsi untuk "Menggambar" UI berdasarkan Data
            function renderStore() {
                // Tampilkan kontainer utama
                document.getElementById('store-profile-app').classList.remove('hidden');

                // Render Info Toko
                document.getElementById('store-name').innerText = mockApiData.store.name;
                document.getElementById('store-meta').innerText = `${mockApiData.store.location} • ${mockApiData.store.followers} Followers • ${mockApiData.store.rating} ★`;
                document.getElementById('store-logo').src = mockApiData.store.logo_url;
                document.getElementById('store-banner').src = mockApiData.store.banner_url;
                
                // Render Produk
                document.getElementById('product-count').innerText = mockApiData.products.length;
                const grid = document.getElementById('product-grid');
                grid.innerHTML = ''; // Kosongkan grid

                mockApiData.products.forEach(product => {
                    // Logika format rupiah
                    const priceFormatted = new Intl.NumberFormat('id-ID').format(product.price);
                    const oldPriceHtml = product.old_price 
                        ? `<span class="text-gray-400 line-through text-xs mr-2">Rp ${new Intl.NumberFormat('id-ID').format(product.old_price)}</span> <span class="bg-red-100 text-red-600 text-[10px] font-bold px-1 py-0.5 rounded">-${product.discount}%</span>` 
                        : '';

                    // Cetak Kartu Produk
                    grid.innerHTML += `
                        <div class="group cursor-pointer">
                            <div class="relative w-full aspect-[3/4] bg-gray-100 mb-3 overflow-hidden">
                                <img src="${product.image}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" alt="${product.name}">
                            </div>
                            <h3 class="text-xs font-bold uppercase text-gray-900 truncate pr-4">${product.name}</h3>
                            <p class="text-[10px] text-gray-500 uppercase tracking-widest my-1">${mockApiData.store.name.split(' ')[0]}</p>
                            <div class="mt-2">
                                <span class="text-red-600 font-bold text-sm block mb-1">Rp ${priceFormatted}</span>
                                ${oldPriceHtml}
                            </div>
                        </div>
                    `;
                });
            }

            // 3. Jalankan fungsi render (Di dunia nyata, ini dipanggil setelah fetch() sukses)
            renderStore();
        });
    </script>
</x-layouts.app>