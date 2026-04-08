@props(['product'])
<div class="bg-gray-50 p-2 group">
    <a href="{{ route('product.show', $product->id) }}" class="block">
        <div class="relative aspect-[3/4] bg-gray-200 mb-3">
            <img src="{{ asset('images/products/' . $product->image) }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
            @if(isset($product->discount))
                <span class="absolute bottom-2 left-2 bg-red-600 text-white text-[10px] px-2 py-0.5 rounded font-bold">-{{ $product->discount }}%</span>
            @endif
        </div>
        
        <div class="px-1">
            <div class="flex space-x-1 mb-2">
                <span class="w-3 h-3 rounded-full bg-gray-800 border border-gray-300"></span>
                <span class="w-3 h-3 rounded-full bg-stone-400 border border-gray-300"></span>
            </div>
            <h3 class="text-[17px] font-bold uppercase text-gray-900 leading-tight group-hover:text-[#c4a052] transition">{{ $product->name }}</h3>
            <p class="text-[13px] text-gray-500 line-clamp-2 my-1">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.</p>
            <div class="mt-2">
                <span class="text-red-600 font-bold text-xl">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                <span class="text-gray-500 line-through font-italic text-[15px] block">Rp {{ number_format($product->price * 1.3, 0, ',', '.') }}</span>
            </div>
        </div>
    </a>
</div>