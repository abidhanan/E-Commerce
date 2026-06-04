<!DOCTYPE html>
<html lang="en">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clothique</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Montserrat', sans-serif; }</style>
</head>
<body class="bg-white text-gray-900">
    <x-navbar />

    <main>
        {{ $slot }}
    </main>

    <x-footer />

    <div id="auth-modal" class="fixed inset-0 z-[999] hidden flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity">
        <div class="bg-white p-8 max-w-sm w-full shadow-2xl relative text-center transform scale-95 transition-transform duration-300" id="auth-modal-content">
            
            <div class="w-16 h-16 bg-gray-50 rounded-full mx-auto flex items-center justify-center mb-6">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </div>

            <h3 class="text-lg font-bold text-gray-900 mb-2">Simpan ke Wishlist?</h3>
            <p class="text-xs text-gray-500 mb-8 leading-relaxed">Kamu harus masuk atau mendaftar terlebih dahulu untuk menyimpan produk favoritmu.</p>

            <div class="flex flex-col space-y-3">
                <a href="{{ route('login') }}" class="w-full bg-black text-white text-xs font-bold tracking-widest uppercase py-4 hover:bg-gray-800 transition shadow-lg">
                    Masuk / Login
                </a>
                <button type="button" onclick="hideLoginModal()" class="w-full border border-gray-200 text-gray-600 text-xs font-bold tracking-widest uppercase py-4 hover:bg-gray-50 transition">
                    Batal
                </button>
            </div>
        </div>
    </div>
</body>
</html>