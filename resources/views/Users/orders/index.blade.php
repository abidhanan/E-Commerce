<x-layouts.app>
    @php
        $statusColors = [
            'waiting_admin' => 'bg-yellow-100 text-yellow-800',
            'quoted'        => 'bg-blue-100 text-blue-800',
            'pending'       => 'bg-yellow-100 text-yellow-800',
            'paid'          => 'bg-green-100 text-green-800',
            'challenge'     => 'bg-orange-100 text-orange-800',
            'processing'    => 'bg-indigo-100 text-indigo-800',
            'shipped'       => 'bg-purple-100 text-purple-800',
            'completed'     => 'bg-green-100 text-green-800',
            'failed'        => 'bg-red-100 text-red-800',
            'refunded'      => 'bg-red-100 text-red-800',
            'cancelled'     => 'bg-red-100 text-red-800',
        ];

        $statusLabels = [
            'waiting_admin' => 'Menunggu Konfirmasi',
            'quoted'        => 'Menunggu Pembayaran',
            'pending'       => 'Menunggu Pembayaran',
            'paid'          => 'Lunas',
            'challenge'     => 'Sedang Ditinjau',
            'processing'    => 'Diproses',
            'shipped'       => 'Dikirim',
            'completed'     => 'Selesai',
            'failed'        => 'Gagal',
            'refunded'      => 'Dikembalikan',
            'cancelled'     => 'Dibatalkan',
        ];
    @endphp

    <div class="max-w-screen-xl mx-auto px-6 py-16 flex flex-col md:flex-row gap-12 min-h-screen">
        
        @include('Users.account.sidebar')

        <main class="flex-1 border-l border-gray-200 pl-0 md:pl-12">
            <div class="bg-black text-white inline-block px-12 py-4 mb-8">
                <h2 class="text-2xl font-light tracking-widest uppercase">Purchase History</h2>
            </div>

            <div class="space-y-6">
                @forelse($orders ?? [] as $order)
                    <div class="border border-gray-200 bg-white p-6 md:p-8 flex flex-col md:flex-row gap-6 justify-between items-start md:items-center hover:shadow-lg transition-shadow duration-300">
                        
                        <div class="space-y-3 w-full md:w-auto">
                            <div class="flex items-center gap-4">
                                <span class="text-lg font-bold text-gray-900 uppercase tracking-wide">{{ $order->order_code }}</span>
                                <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-widest {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                </span>
                            </div>
                            
                            <div class="text-sm text-gray-500 font-medium space-y-1">
                                <p>Tanggal: {{ $order->created_at->format('d M Y, H:i') }}</p>
                                <p>Total: <span class="text-black font-bold">Rp {{ number_format($order->gross_amount, 0, ',', '.') }}</span></p>
                            </div>
                        </div>

                        <div class="w-full md:w-auto flex gap-3">
                            @if(in_array($order->status, ['quoted', 'pending']) && $order->payment_url)
                                <a href="{{ $order->payment_url }}" target="_blank" class="flex-1 md:flex-none bg-[#c4a052] text-white px-6 py-3 text-xs font-bold uppercase tracking-widest hover:bg-black transition text-center text-nowrap">
                                    Bayar Sekarang
                                </a>
                            @endif
                            <a href="{{ route('user.orders.show', $order->order_code) }}" class="flex-1 md:flex-none border border-black text-black px-6 py-3 text-xs font-bold uppercase tracking-widest hover:bg-black hover:text-white transition text-center text-nowrap">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 bg-gray-50 border border-gray-100">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        <p class="text-gray-500 text-lg font-medium">There is no order history yet.</p>
                        <a href="{{ route('shop.index') }}" class="inline-block mt-4 text-sm font-bold uppercase tracking-widest border-b border-black pb-1 hover:text-[#c4a052] transition">Start Shopping</a>
                    </div>
                @endforelse
            </div>
            
            @if(isset($orders) && $orders->hasPages())
                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            @endif
        </main>
    </div>
</x-layouts.app>