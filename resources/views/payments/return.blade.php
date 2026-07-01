<x-layouts.app>
    @php
        $label = match ($status) {
            'success' => 'Pembayaran Berhasil',
            'failed' => 'Pembayaran Gagal',
            default => 'Pembayaran Diproses',
        };

        $message = match ($status) {
            'success' => 'Terima kasih. Status final pesanan akan mengikuti callback resmi dari Duitku.',
            'failed' => 'Pembayaran gagal atau dibatalkan. Silakan cek status pesananmu.',
            default => 'Pembayaran sedang diproses. Tunggu callback resmi dari Duitku memperbarui status pesanan.',
        };

        $badgeClass = match ($status) {
            'success' => 'bg-green-100 text-green-800 border-green-200',
            'failed' => 'bg-red-100 text-red-800 border-red-200',
            default => 'bg-yellow-100 text-yellow-800 border-yellow-200',
        };
    @endphp

    <div class="max-w-screen-md mx-auto px-6 py-16 lg:py-24">
        <div class="bg-white border border-gray-200 shadow-2xl p-8 lg:p-14 text-center">
            <div class="inline-block border px-4 py-1 text-[10px] font-bold uppercase tracking-widest mb-6 {{ $badgeClass }}">
                {{ $label }}
            </div>

            <h1 class="text-3xl font-light uppercase tracking-widest text-gray-900 mb-4">Status Pembayaran</h1>
            <p class="text-gray-500 text-sm leading-relaxed mb-10 max-w-lg mx-auto">
                {{ $message }}
            </p>

            <div class="bg-gray-50 border border-gray-100 p-6 md:p-8 text-left mb-10 space-y-4">
                <div class="flex justify-between items-center border-b border-gray-200 pb-4">
                    <span class="text-xs font-bold uppercase tracking-widest text-gray-500">Merchant Order ID</span>
                    <span class="text-sm font-bold text-gray-900">{{ $merchantOrderId ?: '-' }}</span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Reference</span>
                    <span class="text-sm font-medium text-gray-900">{{ $reference ?: '-' }}</span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-center gap-4">
                @if ($merchantOrderId)
                    <a href="{{ route('payments.status', $merchantOrderId) }}" class="bg-black text-white px-8 py-4 text-xs font-bold uppercase tracking-widest hover:bg-[#c4a052] transition text-center">
                        Lihat Status Pesanan
                    </a>
                @endif
                <a href="{{ url('/') }}" class="border border-black text-black px-8 py-4 text-xs font-bold uppercase tracking-widest hover:bg-gray-50 transition text-center">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
