<x-layouts.app>
    <div id="catalog-container" class="opacity-0 transition-opacity duration-700 pb-20">
        
        <div class="relative w-full h-[40vh] bg-gray-200 overflow-hidden">
            <img id="collection-banner" src="" class="w-full h-full object-cover filter brightness-75" alt="Collection Banner">
            
            <div class="absolute inset-0 flex flex-col items-center justify-center text-white">
                <h1 id="collection-name" class="text-5xl font-light tracking-[0.2em] uppercase mb-4">Memuat...</h1>
                <p id="collection-desc" class="text-sm font-medium tracking-widest uppercase text-gray-200"></p>
            </div>
        </div>

        <div class="max-w-screen-xl mx-auto px-6 mt-12 flex justify-between items-center border-b border-gray-200 pb-4">
            <h2 class="text-xs font-bold uppercase tracking-widest text-gray-500">
                Showing <span id="product-count" class="text-black">0</span> products
            </h2>
            
            <select class="text-xs font-bold uppercase tracking-widest border-none bg-transparent focus:ring-0 cursor-pointer">
                <option>Most Relevant</option>
                <option>New Arrivals</option>
                <option>Price: Low to High</option>
                <option>Price: High to Low</option>
            </select>
        </div>

        <div class="max-w-screen-xl mx-auto px-6 py-12">
            <div id="product-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-8 gap-y-16">
                </div>
        </div>
    </div>

    <script>
        // 1. KONTRAK API PALSU (Mock Data)
        // Jika API backend temanmu sudah jadi, kamu HANYA perlu menghapus variabel ini 
        // dan menggantinya dengan fetch('/api/v1/collections/summer')
        const mockApiResponse = {
            status: "success",
            data: {
                collection: {
                    name: "CLOTHIQUE ESSENTIALS",
                    description: "Timeless silhouettes for the modern individual.",
                    banner_url: "https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?auto=format&fit=crop&q=80&w=2000"
                },
                products: [
                    {
                        id: 101,
                        name: "Minimalist Linen Jacket",
                        price: 850000,
                        discount_price: 550000,
                        image: "https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&q=80&w=600"
                    },
                    {
                        id: 102,
                        name: "Heavyweight Cotton T-Shirt",
                        price: 250000,
                        discount_price: null,
                        image: "https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&q=80&w=600"
                    },
                    {
                        id: 103,
                        name: "Pleated Wide Trousers",
                        price: 650000,
                        discount_price: null,
                        image: "https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?auto=format&fit=crop&q=80&w=600"
                    },
                    {
                        id: 104,
                        name: "Classic Wool Overcoat",
                        price: 1200000,
                        discount_price: 990000,
                        image: "https://images.unsplash.com/photo-1539533018447-63fcce2678e3?auto=format&fit=crop&q=80&w=600"
                    }
                ]
            }
        };

        // 2. FUNGSI RENDER (DOM Manipulation)
        document.addEventListener('DOMContentLoaded', function() {
            
            // Kita gunakan setTimeout untuk mensimulasikan jeda jaringan (Loading State) selama 0.8 detik
            setTimeout(() => {
                const data = mockApiResponse.data;

                // A. Suntikkan Header Data
                document.getElementById('collection-name').innerText = data.collection.name;
                document.getElementById('collection-desc').innerText = data.collection.description;
                document.getElementById('collection-banner').src = data.collection.banner_url;
                document.getElementById('product-count').innerText = data.products.length;

                // B. Suntikkan Produk (Looping Array)
                const productGrid = document.getElementById('product-grid');
                productGrid.innerHTML = ''; // Kosongkan grid terlebih dahulu

                data.products.forEach(product => {
                    // Logika Format Rupiah standar
                    const formattedPrice = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(product.price);
                    
                    // Logika Harga Coret jika ada diskon
                    let priceHtml = `<p class="text-sm font-bold mt-2 text-gray-900">${formattedPrice}</p>`;
                    if (product.discount_price) {
                        const formattedDiscount = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(product.discount_price);
                        priceHtml = `
                            <div class="mt-2 flex items-center space-x-3">
                                <p class="text-sm font-bold text-red-600">${formattedDiscount}</p>
                                <p class="text-xs text-gray-400 line-through">${formattedPrice}</p>
                            </div>
                        `;
                    }

                    // Templat HTML Produk (Backtick ` memungkinkan variabel disisipkan dengan ${})
                    const productCard = `
                        <div class="group cursor-pointer flex flex-col">
                            <div class="relative w-full aspect-[3/4] bg-gray-100 overflow-hidden mb-4">
                                <img src="${product.image}" class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110" alt="${product.name}">
                                
                                <button class="absolute bottom-0 left-0 w-full bg-black text-white text-xs font-bold tracking-widest uppercase py-3 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                    Quick Add
                                </button>
                            </div>
                            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 truncate">${product.name}</h3>
                            ${priceHtml}
                        </div>
                    `;
                    
                    // Tempelkan kartu produk ke dalam grid
                    productGrid.insertAdjacentHTML('beforeend', productCard);
                });

                // C. Selesai memuat, tampilkan halaman (Hilangkan efek transparan)
                document.getElementById('catalog-container').classList.remove('opacity-0');

            }, 800); // Waktu jeda pura-pura
        });
    </script>
</x-layouts.app>