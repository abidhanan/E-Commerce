<x-layouts.app>
    <div class="max-w-screen-xl mx-auto px-6 py-16">
        <h1 class="text-3xl font-bold uppercase tracking-widest mb-10 text-gray-900">Checkout</h1>

        <form action="{{ route('checkout.order') }}" method="POST" id="checkout-form" class="flex flex-col lg:flex-row gap-12">
            @csrf
            
            <input type="hidden" name="source" value="{{ $source }}">
            @if($variantId)
                <input type="hidden" name="variant_id" value="{{ $variantId }}">
            @endif

            <div class="flex-1 space-y-12">
                
                <section>
                    <h2 class="text-sm font-bold mb-6 uppercase tracking-widest text-gray-400">1. Alamat Pengiriman</h2>
                    
                    @if($addresses->isEmpty())
                        <div class="bg-gray-50 p-8 text-center border border-gray-200">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <p class="text-sm text-gray-600 mb-4">Kamu belum mendaftarkan alamat pengiriman.</p>
                            <a href="#" class="inline-block bg-black text-white text-xs font-bold uppercase tracking-widest px-6 py-3 hover:bg-[#c4a052] transition">
                                Tambah Alamat
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($addresses as $address)
                                <label class="border p-5 cursor-pointer transition-all duration-300 relative block group {{ $address->is_primary ? 'border-black bg-gray-50' : 'border-gray-200 hover:border-black' }}">
                                    <input type="radio" name="address_id" value="{{ $address->id }}" class="absolute top-5 right-5 accent-black" {{ $address->is_primary ? 'checked' : '' }} required>
                                    
                                    <h3 class="font-bold text-xs uppercase tracking-wider mb-2 text-gray-900">{{ $address->label }}</h3>
                                    <p class="text-sm font-semibold text-gray-900">{{ $address->recipient_name }}</p>
                                    <p class="text-xs text-gray-500 mb-2">{{ $address->phone_number }}</p>
                                    <p class="text-xs text-gray-600 leading-relaxed">{{ $address->full_address }}, {{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}</p>
                                </label>
                            @endforeach
                        </div>
                    @endif
                </section>

                <section>
                    <h2 class="text-sm font-bold mb-6 uppercase tracking-widest text-gray-400">2. Catatan Pesanan (Opsional)</h2>
                    <textarea name="customer_note" rows="3" class="w-full border border-gray-200 p-4 text-sm focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition" placeholder="Tinggalkan instruksi khusus untuk penjual di sini..."></textarea>
                </section>
            </div>

            <div class="w-full lg:w-[400px] flex-shrink-0">
                <div class="border border-gray-100 bg-gray-50 p-8 sticky top-28 shadow-sm">
                    <h2 class="text-sm font-bold mb-6 uppercase tracking-widest text-black border-b border-gray-200 pb-4">Ringkasan Pesanan</h2>

                    <div class="space-y-6 mb-8 border-b border-gray-200 pb-8">
                        @foreach($items as $item)
                            <div class="flex gap-4 items-start">
                                <div class="w-20 h-24 bg-white flex-shrink-0 overflow-hidden border border-gray-100">
                                    @php
                                        $img = $item['product']->images->firstWhere('is_primary', true) ?? $item['product']->images->first();
                                    @endphp
                                    <img src="{{ $img ? asset('storage/' . $img->image) : asset('images/no-image.jpg') }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-grow">
                                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900 leading-tight">{{ $item['product']->name }}</h3>
                                    <p class="text-[10px] text-gray-500 uppercase tracking-widest mt-1">Size: {{ $item['variant']->size }}</p>
                                    <p class="text-[10px] text-gray-500 uppercase tracking-widest mt-1">Qty: {{ $item['qty'] }}</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-2">Rp {{ number_format($item['line_total'], 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-between items-end mb-8">
                        <span class="text-xs font-bold uppercase tracking-widest text-gray-500">Total Pembayaran</span>
                        <span class="text-2xl font-bold text-black">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>

                    <button type="submit" class="w-full bg-black text-white text-xs font-bold tracking-widest uppercase py-5 hover:bg-[#c4a052] transition-colors disabled:opacity-50 disabled:cursor-not-allowed" {{ $addresses->isEmpty() ? 'disabled title="Tambahkan alamat terlebih dahulu"' : '' }}>
                        BUAT PESANAN
                    </button>
                    
                    <p class="text-[10px] text-gray-400 text-center mt-4 uppercase tracking-wider">
                        Ongkos kirim akan dihitung oleh admin
                    </p>
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>