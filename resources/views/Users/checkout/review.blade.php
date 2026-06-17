<x-layouts.app>
    <div class="max-w-screen-xl mx-auto px-6 py-12 lg:py-20 text-gray-900">
        
        <form action="{{ route('checkout.order') }}" method="POST" id="checkout-form" class="flex flex-col lg:flex-row gap-16 lg:gap-24">
            @csrf
            
            <input type="hidden" name="source" value="{{ $source }}">
            @if($source === 'buy_now' && isset($variantId))
                <input type="hidden" name="variant_id" value="{{ $variantId }}">
            @endif

            @if($source === 'cart')
                @foreach($items as $item)
                    <input type="hidden" name="selected_items[]" value="{{ $item['id'] ?? $item['variant']->id }}">
                @endforeach
            @endif

            @php
                $primaryAddress = $addresses->firstWhere('is_primary', true) ?? $addresses->first();
            @endphp
            
            @if($primaryAddress)
                <input type="hidden" name="address_id" value="{{ $primaryAddress->id }}">
            @endif

            <div class="w-full lg:w-7/12 flex flex-col gap-12">
                
                <section>
                    <div class="border-b-[3px] border-black inline-block pb-1 mb-6">
                        <h2 class="text-3xl font-normal tracking-wide uppercase">Shipping Address</h2>
                    </div>

                    @if($primaryAddress)
                        <div class="border border-black rounded-xl overflow-hidden">
                            <div class="flex justify-between items-center border-b border-gray-200 px-6 py-4 bg-white">
                                <span class="font-semibold text-sm">Saved Information</span>
                                <a href="{{ route('account.index') }}" class="text-[#c4a052] font-bold text-sm uppercase tracking-widest hover:underline">Change</a>
                            </div>
                            <div class="p-6 bg-white space-y-4">
                                <ul class="list-disc list-outside ml-5 space-y-4 text-sm font-medium">
                                    <li>
                                        <span class="block text-gray-900">Name</span>
                                        <span class="block font-normal text-gray-600 mt-1">{{ $primaryAddress->recipient_name }}</span>
                                    </li>
                                    <li>
                                        <span class="block text-gray-900">Phone Number</span>
                                        <span class="block font-normal text-gray-600 mt-1">{{ $primaryAddress->phone_number }}</span>
                                    </li>
                                    <li>
                                        <span class="block text-gray-900">Address</span>
                                        <span class="block font-normal text-gray-600 mt-1 leading-relaxed">
                                            {{ $primaryAddress->full_address }}, {{ $primaryAddress->city }}, {{ $primaryAddress->province }} {{ $primaryAddress->postal_code }}
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @else
                        <div class="border border-red-500 bg-red-50 p-8 text-center rounded-xl">
                            <p class="text-red-700 font-medium mb-4">You don't have any saved address.</p>
                            <a href="{{ route('account.index') }}" class="inline-block bg-black text-white text-sm font-bold uppercase tracking-widest px-8 py-4 hover:bg-[#c4a052] transition">
                                Add Address
                            </a>
                        </div>
                    @endif
                </section>

                <section>
                    <div class="border-b-[3px] border-black inline-block pb-1 mb-6">
                        <h2 class="text-3xl font-normal tracking-wide uppercase">Shipping Method</h2>
                    </div>

                    <div class="space-y-4">
                        <label class="shipping-option flex justify-between items-center border border-black rounded-xl p-5 cursor-pointer bg-white transition">
                            <div class="flex items-center gap-4">
                                <input type="radio" name="shipping_method" value="standard" data-cost="20000" checked class="w-5 h-5 text-black accent-black bg-gray-100 border-gray-300 focus:ring-black">
                                <span class="font-medium text-sm">Standard Delivery (3-5 days)</span>
                            </div>
                            <span class="font-medium text-sm">Rp 20.000</span>
                        </label>

                        <label class="shipping-option flex justify-between items-center border border-gray-300 rounded-xl p-5 cursor-pointer hover:border-black bg-white transition">
                            <div class="flex items-center gap-4">
                                <input type="radio" name="shipping_method" value="express" data-cost="50000" class="w-5 h-5 text-black accent-black bg-gray-100 border-gray-300 focus:ring-black">
                                <span class="font-medium text-sm">Express Delivery (1-2 days)</span>
                            </div>
                            <span class="font-medium text-sm">Rp 50.000</span>
                        </label>
                    </div>
                </section>
            </div>

            <div class="w-full lg:w-5/12">
                <div class="sticky top-28">
                    <div class="border-b-[3px] border-black inline-block pb-1 mb-8">
                        <h2 class="text-3xl font-normal tracking-wide uppercase">Order Summary</h2>
                    </div>

                    <div class="space-y-6 mb-8 max-h-[400px] overflow-y-auto pr-2">
                        @foreach($items as $item)
                            @php
                                // Pemetaan Array Asosiatif (Karena controllermu mengirimkan array, bukan objek)
                                $variant = $item['variant'];
                                $product = $item['product'];
                                $qty = $item['qty'];
                                
                                // Jika controller tidak mengirimkan line_total, hitung otomatis
                                $lineTotal = $item['line_total'] ?? ($variant->price * $qty);
                                
                                $img = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
                            @endphp
                            
                            <div class="flex gap-6 items-center">
                                <div class="w-24 h-28 bg-gray-100 flex-shrink-0">
                                    <img src="{{ $img ? asset('storage/' . $img->image) : asset('images/no-image.jpg') }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-grow">
                                    <h3 class="text-sm font-bold text-gray-900 leading-tight mb-2">{{ $product->name }}</h3>
                                    
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="bg-black text-white text-[10px] font-bold px-2 py-1 uppercase rounded-sm">
                                            Size: {{ $variant->size }}
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between mt-4">
                                        <div class="bg-gray-200 text-gray-800 text-xs font-semibold px-4 py-1 rounded-full">
                                            Qty: {{ $qty }}
                                        </div>
                                        <span class="text-base font-normal italic">Rp {{ number_format($lineTotal, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-gray-300 pt-6 space-y-4">
                        <div class="flex justify-between items-center text-sm font-medium">
                            <span class="text-gray-600">Subtotal</span>
                            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm font-medium">
                            <span class="text-gray-600">Shipping</span>
                            <span id="shipping-cost-display">Rp 20.000</span> 
                        </div>
                    </div>

                    <div class="border-t border-black mt-4 pt-4 mb-6">
                        <div class="flex justify-between items-center text-xl font-bold">
                            <span>Total</span>
                            <span id="total-cost-display">Rp {{ number_format($subtotal + 20000, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <p class="text-xs text-gray-500 mb-6 font-medium">
                        Taxes and discount codes are calculated at Payment.
                    </p>

                    <button type="submit" class="w-full bg-black text-white text-lg font-normal tracking-wide uppercase py-5 hover:bg-gray-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" {{ !$primaryAddress ? 'disabled' : '' }}>
                        CONTINUE TO PAYMENT
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const subtotal = {{ $subtotal }};
            const shippingRadios = document.querySelectorAll('input[name="shipping_method"]');
            const shippingDisplay = document.getElementById('shipping-cost-display');
            const totalDisplay = document.getElementById('total-cost-display');
            const shippingOptions = document.querySelectorAll('.shipping-option');

            function updatePricing(radio) {
                const shippingCost = parseInt(radio.getAttribute('data-cost'), 10);
                const grandTotal = subtotal + shippingCost;

                // Update teks di antarmuka
                shippingDisplay.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(shippingCost);
                totalDisplay.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(grandTotal);

                // Perbarui estetika label radio button agar jelas mana yang terpilih
                shippingOptions.forEach(opt => {
                    opt.classList.remove('border-black');
                    opt.classList.add('border-gray-300');
                });
                radio.closest('.shipping-option').classList.remove('border-gray-300');
                radio.closest('.shipping-option').classList.add('border-black');
            }

            shippingRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    updatePricing(this);
                });
            });
        });
    </script>
</x-layouts.app>