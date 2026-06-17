<x-layouts.app>
    @php
        $statusLabels = [
            'waiting_admin' => 'Menunggu Konfirmasi Admin',
            'quoted'        => 'Menunggu Pembayaran',
            'pending'       => 'Menunggu Pembayaran',
            'paid'          => 'Lunas (Menunggu Diproses)',
            'challenge'     => 'Sedang Ditinjau',
            'processing'    => 'Sedang Diproses',
            'shipped'       => 'Sedang Dikirim',
            'completed'     => 'Pesanan Selesai',
            'failed'        => 'Pesanan Gagal',
            'refunded'      => 'Dana Dikembalikan',
            'cancelled'     => 'Pesanan Dibatalkan',
        ];
    @endphp

    <div class="max-w-screen-xl mx-auto px-6 py-16 flex flex-col md:flex-row gap-12 min-h-screen">
        
        @include('Users.account.sidebar')

        <main class="flex-1 border-l border-gray-200 pl-0 md:pl-12">
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-light tracking-wide uppercase text-gray-900">Order #{{ $order->order_code }}</h2>
                    <p class="text-sm text-gray-500 mt-1 font-medium">{{ $order->created_at->format('d F Y, H:i') }}</p>
                </div>
                <div class="px-6 py-2 border border-black text-xs font-bold uppercase tracking-widest inline-block text-center bg-gray-50">
                    {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                </div>
            </div>

            @if(in_array($order->status, ['quoted', 'pending']) && $order->payment_url)
                <div class="mb-8 p-6 bg-yellow-50 border border-yellow-200 flex flex-col md:flex-row items-center justify-between gap-4">
                    <p class="text-sm font-medium text-yellow-800">Selesaikan pembayaran agar pesanan dapat segera diproses.</p>
                    <a href="{{ $order->payment_url }}" target="_blank" class="bg-black text-white px-8 py-3 text-xs font-bold uppercase tracking-widest hover:bg-[#c4a052] transition text-nowrap">
                        Bayar Sekarang
                    </a>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <div class="lg:col-span-2 space-y-6">
                    <h3 class="text-sm font-bold uppercase tracking-widest border-b border-gray-200 pb-2">Item Pesanan</h3>
                    
                    @foreach($order->items as $item)
                        @php
                            $img = $item->product->images->firstWhere('is_primary', true) ?? $item->product->images->first();
                        @endphp
                        <div class="flex gap-6 items-center border-b border-gray-100 pb-6">
                            <div class="w-20 h-24 bg-gray-100 flex-shrink-0">
                                <img src="{{ $img ? asset('storage/' . $img->image) : asset('images/no-image.jpg') }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-grow">
                                <a href="{{ route('product.show', $item->product->slug) }}" class="text-sm font-bold text-gray-900 uppercase hover:text-[#c4a052] transition">{{ $item->product->name }}</a>
                                <p class="text-xs text-gray-500 mt-1 uppercase tracking-widest">Size: {{ $item->productVariant->size ?? '-' }}</p>
                                <div class="flex justify-between items-center mt-3">
                                    <span class="text-xs font-bold text-gray-500">Qty: {{ $item->qty }}</span>
                                    <span class="text-sm font-bold text-black">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="space-y-10">
                    <div class="bg-gray-50 p-6 border border-gray-200">
                        <h3 class="text-sm font-bold uppercase tracking-widest border-b border-gray-300 pb-2 mb-4">Ringkasan Biaya</h3>
                        <div class="space-y-3 text-sm font-medium">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Ongkos Kirim</span>
                                <span>{{ is_null($order->shipping_cost) ? 'Menunggu Admin' : 'Rp ' . number_format($order->shipping_cost, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-black font-bold text-base border-t border-gray-300 pt-3 mt-3">
                                <span>Total Akhir</span>
                                <span>Rp {{ number_format($order->gross_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    @if($order->address)
                    <div>
                        <h3 class="text-sm font-bold uppercase tracking-widest border-b border-gray-200 pb-2 mb-4">Alamat Pengiriman</h3>
                        <div class="text-sm text-gray-600 leading-relaxed font-medium space-y-1">
                            <p class="font-bold text-gray-900">{{ $order->address->recipient_name }}</p>
                            <p>{{ $order->address->phone_number }}</p>
                            <p>{{ $order->address->full_address }}</p>
                            <p>{{ $order->address->city }}, {{ $order->address->province }} {{ $order->address->postal_code }}</p>
                        </div>
                    </div>
                    @endif

                    @if($order->admin_note)
                    <div class="bg-blue-50 border border-blue-200 p-4">
                        <h3 class="text-[10px] font-bold uppercase tracking-widest text-blue-900 mb-2">Catatan Admin</h3>
                        <p class="text-sm text-blue-800">{{ $order->admin_note }}</p>
                    </div>
                    @endif
                </div>
            </div>

            @if($order->status === 'completed')
                <div class="mt-12">
                    @if($order->review)
                        <div class="bg-gray-50 p-6 border border-gray-200">
                            <h3 class="text-sm font-bold uppercase tracking-widest border-b border-gray-300 pb-2 mb-4">Ulasan Anda untuk Pesanan Ini</h3>
                            <div class="flex items-center gap-2 mb-3 text-[#c4a052]">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $order->review->rating ? 'text-[#c4a052]' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                            <p class="text-sm text-gray-600 italic">"{{ $order->review->comment }}"</p>
                        </div>
                    @else
                        <div class="bg-white p-8 border border-gray-200 shadow-sm">
                            <h3 class="text-lg font-light tracking-wide uppercase text-gray-900 mb-6">Berikan Ulasan Anda</h3>
                            
                            <form action="{{ route('user.orders.review', $order->order_code) }}" method="POST" class="space-y-6">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold tracking-wide uppercase mb-3">Rating Pesanan</label>
                                    <div class="flex gap-3 flex-row-reverse justify-end">
                                        @for($i = 5; $i >= 1; $i--)
                                            <label class="cursor-pointer">
                                                <input type="radio" name="rating" value="{{ $i }}" class="peer sr-only" required>
                                                <span class="w-10 h-10 flex items-center justify-center border border-gray-300 text-sm font-bold text-gray-400 peer-checked:bg-black peer-checked:text-white peer-checked:border-black hover:bg-gray-100 transition">
                                                    {{ $i }}
                                                </span>
                                            </label>
                                        @endfor
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold tracking-wide uppercase mb-2">Komentar & Pengalaman</label>
                                    <textarea name="comment" rows="4" required class="w-full bg-gray-50 border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition" placeholder="Bagaimana kualitas produk dan layanan kami?"></textarea>
                                </div>

                                <button type="submit" class="w-full sm:w-auto bg-black text-white px-10 py-4 text-xs font-bold tracking-widest uppercase hover:bg-[#c4a052] transition">
                                    Kirim Ulasan
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endif

            <div class="mt-12">
                <a href="{{ route('user.orders.index') }}" class="text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-black transition border-b border-transparent hover:border-black pb-1">
                    &larr; Kembali ke Riwayat Pembelian
                </a>
            </div>
        </main>
    </div>
</x-layouts.app>