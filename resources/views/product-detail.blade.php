<x-layouts.app>
    <div class="max-w-screen-xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start relative">
    
            <div class="flex flex-col lg:col-span-7">
                <div class="relative w-full bg-gray-100 overflow-hidden cursor-crosshair group aspect-[3/4] mb-4"
                    id="main-image-container"
                    onmousemove="zoomImage(event, this)"
                    onmouseleave="resetZoom(this)">
                    
                    <img id="main-product-image"
                        src="{{ asset('images/products/chanel-1.jpg') }}"
                        class="w-full h-full object-cover transition-transform duration-300 ease-out group-hover:scale-150 origin-center"
                        alt="Tampak Depan">
                </div>

                <div class="grid grid-cols-5 gap-3 mb-10">
                    <button type="button" onclick="changeMainImage(this)" class="relative aspect-[3/4] bg-gray-200 border-b-2 border-black focus:outline-none overflow-hidden group">
                        <img src="{{ asset('images/products/chanel-1.jpg') }}" class="w-full h-full object-cover group-hover:opacity-75 transition">
                    </button>

                    <button type="button" onclick="changeMainImage(this)" class="relative aspect-[3/4] bg-gray-200 border-b-2 border-transparent hover:border-gray-400 focus:outline-none overflow-hidden group">
                        <img src="{{ asset('images/products/chanel-2.png') }}" class="w-full h-full object-cover group-hover:opacity-75 transition">
                    </button>

                    <button type="button" onclick="changeMainImage(this)" class="relative aspect-[3/4] bg-gray-200 border-b-2 border-transparent hover:border-gray-400 focus:outline-none overflow-hidden group">
                        <img src="{{ asset('images/products/chanel-3.png') }}" class="w-full h-full object-cover group-hover:opacity-75 transition">
                    </button>

                    <button type="button" onclick="changeMainImage(this)" class="relative aspect-[3/4] bg-gray-200 border-b-2 border-transparent hover:border-gray-400 focus:outline-none overflow-hidden group">
                        <img src="{{ asset('images/products/chanel-4.png') }}" class="w-full h-full object-cover group-hover:opacity-75 transition">
                    </button>

                    <button type="button" onclick="changeMainImage(this)" class="relative aspect-[3/4] bg-gray-200 border-b-2 border-transparent hover:border-gray-400 focus:outline-none overflow-hidden group">
                        <img src="{{ asset('images/products/chanel-5.png') }}" class="w-full h-full object-cover group-hover:opacity-75 transition">
                    </button>
                </div>
                
                <div class="mt-4 border-t pt-8">
                    <h3 class="text-sm font-bold uppercase tracking-widest mb-4">Description</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus congue neque at congue lacinia. Donec efficitur nibh at porttitor lobortis. Sed placerat, dolor quis ultrices vulputate, eros velit dignissim ligula, ac sodales dui lectus vel est. Praesent in tincidunt ligula, quis semper erat. Ut in enim turpis. Nam vitae volutpat tortor.
                    </p>
                </div>
            </div>

            <div class="flex flex-col lg:col-span-5 sticky top-28">
                <h1 class="text-xl font-bold text-gray-900 mb-1 leading-tight">{{ $product->name }}</h1>
                <h2 class="text-xs font-bold uppercase tracking-widest text-gray-500 mb-6">Chanel</h2>

                <div class="mb-6">
                    <span class="text-[10px] font-bold uppercase tracking-wider block mb-3 text-gray-400">Pilih Warna</span>
                    <div class="flex items-center space-x-2">
                        <button class="w-6 h-6 rounded-full bg-black ring-1 ring-offset-2 ring-black"></button>
                        <button class="w-6 h-6 rounded-full bg-[#9c917f]"></button>
                    </div>
                </div>

                <div class="mb-8">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Pilih Ukuran</span>
                        <a href="#" class="text-[10px] underline text-gray-400">Size Guide</a>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['XS', 'S', 'M', 'L', 'XL'] as $sz)
                            <button class="w-10 h-10 border border-black text-[10px] font-bold {{ $sz == 'M' ? 'bg-black text-white' : 'bg-white' }}">
                                {{ $sz }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="mb-8 border-t pt-6">
                    <div class="text-3xl text-red-500 italic font-medium">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    <span class="text-sm text-gray-400 line-through">Rp {{ number_format($product->price * 1.3, 0, ',', '.') }}</span>
                </div>

                <form action="#" method="POST" class="space-y-4">
                    @csrf
                    <div class="flex space-x-3">
                        <div class="flex items-center bg-gray-100 px-4 py-3 rounded-full flex-shrink-0">
                            <button type="button" class="font-bold text-lg">&minus;</button>
                            <input type="number" value="1" class="w-8 text-center bg-transparent border-none text-xs font-bold focus:ring-0" readonly>
                            <button type="button" class="font-bold text-lg">&plus;</button>
                        </div>
                        <button type="submit" class="flex-1 bg-black text-white text-[10px] font-bold tracking-widest uppercase py-4 rounded-full hover:bg-gray-800 transition">
                            Add to Cart
                        </button>
                    </div>
                    <button type="button" class="w-full border border-black text-[10px] font-bold tracking-widest uppercase py-4 rounded-full hover:bg-gray-50 transition">
                        Add to Wishlist
                    </button>
                </form>
            </div>
        </div>
    </div>
    <script>
        function zoomImage(e, container) {
            const img = container.querySelector('img');
            const rect = container.getBoundingClientRect();
            
            // Kalkulasi posisi kursor relatif terhadap ukuran gambar
            const x = ((e.clientX - rect.left) / rect.width) * 100;
            const y = ((e.clientY - rect.top) / rect.height) * 100;
            
            img.style.transformOrigin = `${x}% ${y}%`;
        }

        function resetZoom(container) {
            const img = container.querySelector('img');
            img.style.transformOrigin = 'center center';
        }

        function changeMainImage(btn) {
            // 1. Ambil URL dari thumbnail yang diklik
            const newSrc = btn.querySelector('img').src;
            
            // 2. Timpa gambar utama dengan URL baru
            document.getElementById('main-product-image').src = newSrc;

            // 3. Reset garis bawah dari semua thumbnail
            const thumbs = btn.parentElement.querySelectorAll('button');
            thumbs.forEach(t => {
                t.classList.remove('border-black');
                t.classList.add('border-transparent');
            });

            // 4. Beri indikator garis bawah hitam pada thumbnail yang sedang aktif
            btn.classList.remove('border-transparent');
            btn.classList.add('border-black');
        }
    </script>
</x-layouts.app>