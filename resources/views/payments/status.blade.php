<x-layouts.app>
    @php
        $labelMap = [
            'waiting_admin' => 'Menunggu Konfirmasi Admin',
            'quoted' => 'Menunggu Pembayaran',
            'paid' => 'Lunas',
            'pending' => 'Menunggu Pembayaran',
            'challenge' => 'Perlu Review',
            'failed' => 'Gagal',
            'refunded' => 'Dikembalikan',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        $messageMap = [
            'waiting_admin' => 'Pesanan berhasil dibuat. Admin kami akan segera menghitung ongkos kirim ke alamatmu sebelum menerbitkan tautan pembayaran.',
            'quoted' => 'Admin telah mengonfirmasi total akhir pesananmu. Silakan gunakan tombol pembayaran di bawah untuk menyelesaikan transaksi.',
            'paid' => 'Pembayaran berhasil diterima. Pesananmu sudah masuk antrean untuk diproses.',
            'pending' => 'Transaksi telah dibuat, tetapi pembayaran belum diselesaikan. Jika kamu baru saja membayar, tunggu beberapa saat dan segarkan halaman ini.',
            'challenge' => 'Transaksi sedang ditinjau oleh penyedia pembayaran. Pantau statusnya secara berkala.',
            'failed' => 'Pembayaran gagal atau transaksi telah dibatalkan oleh sistem.',
            'refunded' => 'Dana transaksi ini telah dikembalikan.',
            'processing' => 'Pesananmu sedang dikemas dan dipersiapkan untuk pengiriman.',
            'shipped' => 'Pesananmu sedang dalam perjalanan menuju alamat pengiriman.',
            'completed' => 'Pesanan telah selesai. Terima kasih telah berbelanja di Clothique.',
            'cancelled' => 'Pesanan ini telah dibatalkan.',
        ];

        // Penentuan Warna Badge Berdasarkan Status
        $badgeClass = match($order->status) {
            'paid', 'completed' => 'bg-green-100 text-green-800 border-green-200',
            'failed', 'refunded', 'cancelled' => 'bg-red-100 text-red-800 border-red-200',
            default => 'bg-yellow-100 text-yellow-800 border-yellow-200'
        };
    @endphp

    <div class="max-w-screen-md mx-auto px-6 py-16 lg:py-24">
        
        @if (session('notify.message'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 mb-8 text-sm font-medium">
                {{ session('notify.message') }}
            </div>
        @endif

        <div class="bg-white border border-gray-200 shadow-2xl p-8 lg:p-14 text-center">
            
            <div class="inline-block border px-4 py-1 text-[10px] font-bold uppercase tracking-widest mb-6 {{ $badgeClass }}">
                {{ $labelMap[$order->status] ?? ucfirst($order->status) }}
            </div>

            <h1 class="text-3xl font-light uppercase tracking-widest text-gray-900 mb-4">Status Pesanan</h1>
            <p class="text-gray-500 text-sm leading-relaxed mb-10 max-w-lg mx-auto">
                {{ $messageMap[$order->status] ?? 'Status transaksi sedang diproses.' }}
            </p>

            <div class="bg-gray-50 border border-gray-100 p-6 md:p-8 text-left mb-10 space-y-4">
                <div class="flex justify-between items-center border-b border-gray-200 pb-4">
                    <span class="text-xs font-bold uppercase tracking-widest text-gray-500">Nomor Order</span>
                    <span class="text-sm font-bold text-gray-900">{{ $order->order_code }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Subtotal Produk</span>
                    <span class="text-sm font-medium text-gray-900">Rp {{ number_format($order->subtotal ?: $order->items->sum(fn($item) => $item->price * $item->qty), 0, ',', '.') }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Ongkos Kirim</span>
                    <span class="text-sm font-medium text-gray-900">
                        {{ is_null($order->shipping_cost) ? 'Menunggu Kalkulasi' : 'Rp ' . number_format($order->shipping_cost, 0, ',', '.') }}
                    </span>
                </div>

                <div class="flex justify-between items-center border-t border-gray-200 pt-4 mt-4">
                    <span class="text-base font-bold uppercase tracking-widest text-gray-900">Total Akhir</span>
                    <span class="text-lg font-bold text-black">Rp {{ number_format($order->gross_amount, 0, ',', '.') }}</span>
                </div>

                @if ($order->admin_note)
                    <div class="mt-6 p-4 bg-blue-50 border border-blue-100 text-blue-800 text-xs leading-relaxed">
                        <strong>Catatan Admin:</strong> {{ $order->admin_note }}
                    </div>
                @endif
            </div>

            <div class="flex flex-col sm:flex-row justify-center gap-4">
                @if ($order->payment_url && in_array($order->status, ['quoted', 'pending']))
                    <a href="{{ $order->payment_url }}" target="_blank" rel="noopener" class="bg-black text-white px-8 py-4 text-xs font-bold uppercase tracking-widest hover:bg-[#c4a052] transition text-center">
                        Selesaikan Pembayaran
                    </a>
                @endif
                <a href="{{ route('user.orders.show', $order->order_code) }}" class="border border-black text-black px-8 py-4 text-xs font-bold uppercase tracking-widest hover:bg-gray-50 transition text-center">
                    Detail Pesanan
                </a>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-100 flex justify-center gap-6">
                <a href="{{ route('payments.status', $order->order_code) }}" class="text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-black transition">
                    Refresh Status
                </a>
                <a href="{{ url('/') }}" class="text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-black transition">
                    Kembali ke Beranda
                </a>
            </div>

        </div>
    </div>
</x-layouts.app>