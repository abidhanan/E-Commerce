@props(['product'])
<div class="bg-gray-50 p-2 group rounded-lg hover:shadow-lg transition relative cursor-pointer overflow-hidden">
    <a href="{{ route('product.show', $product->id) }}" class="block">
        <div class="relative aspect-[3/4] bg-gray-200 mb-3 hover:-translate-y-1 transition all duration-300 rounded-lg">
            <img src="{{ asset('images/products/' . $product->image) }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
            @if(isset($product->discount))
                <span class="absolute bottom-2 left-2 bg-red-600 text-white text-[8px] px-2 py-0.5 rounded font-bold">-{{ $product->discount }}%</span>
            @endif
        </div>

        <div class="px-1 relative pb-6"> 
    
            <button type="button" onclick="showLoginModal(event)" class="absolute top-0 right-1 text-black-300 hover:text-red-500 transition focus:outline-none z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
            </button>

            <div class="flex space-x-1 mb-2 mt-1">
                <span class="w-3 h-3 rounded-full bg-gray-800 border border-gray-300"></span>
                <span class="w-3 h-3 rounded-full bg-stone-400 border border-gray-300"></span>
            </div>
            
            <h3 class="text-[17px] font-bold uppercase text-gray-900 leading-tight group-hover:text-[#c4a052] transition pr-8">{{ $product->name }}</h3>
            
            <p class="text-[13px] text-gray-500 line-clamp-2 my-1">Mantel Pendek Single Breasted Fit Rileks</p>
            
            <div class="mt-2 mb-2">
                <span class="text-red-600 font-bold text-xl">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                <span class="text-gray-500 line-through font-italic text-[15px] block">Rp {{ number_format($product->price * 1.3, 0, ',', '.') }}</span>
            </div>

            <div class="absolute bottom-0 left-1 flex items-center text-[15px] font-bold text-gray-700">
                <svg class="w-3 h-3 text-yellow-500 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                {{ $product->rating ?? '5.0' }}
            </div>
            
        </div>
    </a>
</div>