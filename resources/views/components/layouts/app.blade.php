<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Token Mutlak untuk Autentikasi AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Clothique') }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Montserrat', sans-serif; }
        
        /* Elemen Estetika Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #fafafa; }
        ::-webkit-scrollbar-thumb { background: #1a1a1a; }
        ::-webkit-scrollbar-thumb:hover { background: #c4a052; }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased selection:bg-black selection:text-white flex flex-col min-h-screen">
    
    <x-navbar />

    <main class="flex-grow">
        {{ $slot }}
    </main>

    <x-footer />

    <!-- INJEKSI KERANJANG BELANJA AJAX -->
    <!-- Baris inilah yang menghubungkan UI keranjang dan JavaScript ke seluruh halaman web-mu -->
    @include('Users.Template.cart-sidebar')

    <script>
        // Mesin Eksekusi Wishlist AJAX
        window.toggleWishlistAjax = async function(url, btn) {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const svg = btn.querySelector('svg');
            
            // Opsional: Buat tombol sedikit transparan saat proses loading agar UX terasa hidup
            btn.style.opacity = '0.5';

            try {
                const response = await fetch(url, {
                    method: 'POST', // Backend mu menggunakan POST untuk toggle
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token
                    }
                });
                
                const data = await response.json();
                
                if(response.ok) {
                    // UI Manipulation: Mengubah warna hati tanpa reload
                    if(data.wishlisted) {
                        btn.classList.remove('text-gray-300');
                        btn.classList.add('text-red-500');
                        svg.setAttribute('fill', 'currentColor'); // Hati penuh
                    } else {
                        btn.classList.remove('text-red-500');
                        btn.classList.add('text-gray-300');
                        svg.setAttribute('fill', 'none'); // Hati kosong
                    }
                } else {
                    alert(data.message || 'Gagal mengubah wishlist.');
                }
            } catch(error) {
                console.error('Wishlist error:', error);
                alert('Koneksi ke server terputus.');
            } finally {
                // Kembalikan transparansi tombol ke normal
                btn.style.opacity = '1';
            }
        };
    </script>

    <script>
    async function synchronizeWishlistUI() {
        try {
            // Tambahkan parameter waktu (timestamp) & header no-cache untuk membunuh cache memori browser
            const response = await fetch("{{ route('wishlist.status') }}?t=" + new Date().getTime(), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Cache-Control': 'no-cache, no-store, must-revalidate'
                }
            });

            if (!response.ok) return;

            const data = await response.json();
            
            // Konversi mutlak: pastikan semua ID yang datang dipaksa menjadi Integer
            const wishlistedIds = (data.product_ids || []).map(id => parseInt(id, 10));

            // Update badge angka di keranjang/wishlist jika ada
            const wishlistCounter = document.getElementById('wishlist-badge-count');
            if (wishlistCounter) {
                wishlistCounter.innerText = data.count;
            }

            // Sapu bersih tombol hati di layar dan cocokkan dengan data peladen
            document.querySelectorAll('.wishlist-sync-btn').forEach(btn => {
                const productId = parseInt(btn.getAttribute('data-product-id'), 10);
                const icon = btn.querySelector('.heart-icon');
                
                if (!icon) return;

                if (wishlistedIds.includes(productId)) {
                    // Barang ada di wishlist -> Nyalakan Hati
                    icon.setAttribute('fill', 'currentColor');
                    btn.classList.add('text-red-500');
                    btn.classList.remove('text-gray-300');
                } else {
                    // Barang tidak ada di wishlist -> Matikan Hati
                    icon.setAttribute('fill', 'none');
                    btn.classList.remove('text-red-500');
                    btn.classList.add('text-gray-300');
                }
            });

        } catch (error) {
            console.error('Sinkronisasi wishlist gagal:', error);
        }
    }

    // 1. Eksekusi saat halaman pertama kali dimuat
    document.addEventListener('DOMContentLoaded', synchronizeWishlistUI);

    // 2. Eksekusi saat pengguna menekan tombol "Back" atau "Forward" di Browser
    window.addEventListener('pageshow', function(event) {
        // event.persisted bernilai true jika halaman dimuat dari memori (BFCache)
        if (event.persisted) {
            synchronizeWishlistUI();
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil SEMUA form kartu produk yang ada di layar
        const cardForms = document.querySelectorAll('.wishlist-card-form');
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        cardForms.forEach(form => {
            form.addEventListener('submit', async function(e) {
                e.preventDefault(); // Cegat reload

                // Cari tombol dan ikon spesifik HANYA di dalam kartu yang sedang diklik
                const btn = this.querySelector('button[type="submit"]');
                const icon = this.querySelector('.wishlist-icon');
                const url = this.action;

                if (!btn || !icon) return;

                btn.disabled = true;
                // Opsional: Efek denyut saat loading
                icon.classList.add('animate-pulse');

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': token
                        }
                    });

                    if (response.status === 401) {
                        window.location.href = "{{ route('login') }}";
                        return;
                    }

                    const data = await response.json();

                    if (!response.ok) {
                        console.error(data.message);
                        return;
                    }

                    // MUTASI VISUAL KARTU
                    if (data.status === 'added') {
                        icon.classList.remove('text-gray-400', 'fill-transparent');
                        icon.classList.add('text-red-500', 'fill-red-500');
                    } else {
                        icon.classList.remove('text-red-500', 'fill-red-500');
                        icon.classList.add('text-gray-400', 'fill-transparent');
                    }

                } catch (error) {
                    console.error('Sistem Gagal:', error);
                } finally {
                    btn.disabled = false;
                    icon.classList.remove('animate-pulse');
                }
            });
        });
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
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

                if (response.status === 401) { window.location.href = "{{ route('login') }}"; return; }

                const data = await response.json();
                if (data.status === 'added') {
                    this.classList.replace('text-gray-300', 'text-red-500');
                    icon.setAttribute('fill', 'currentColor');
                } else {
                    this.classList.replace('text-red-500', 'text-gray-300');
                    icon.setAttribute('fill', 'none');
                }
            } catch (err) {
                console.error("Gagal update wishlist:", err);
            }
        };
    });
});
</script>

</body>
</html>