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

</body>
</html>