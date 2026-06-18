@props(['product'])

@php
    $inWishlist = auth()->check() && \Illuminate\Support\Facades\DB::table('wishlists')
        ->where('user_id', auth()->id())
        ->where('product_id', $product->id)
        ->exists();
@endphp

<div class="wishlist-wrapper absolute top-2 right-2 z-20">
    @auth
        <button type="button" 
            data-url="{{ route('wishlist.toggle', $product->id) }}"
            class="wishlist-btn p-1 transition focus:outline-none drop-shadow-md cursor-pointer {{ $inWishlist ? 'text-red-500' : 'text-gray-300 hover:text-red-500' }}">
            <svg class="w-6 h-6 heart-icon" fill="{{ $inWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
        </button>
    @else
        <a href="{{ route('login') }}" class="block p-1 text-gray-300 hover:text-red-500 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
        </a>
    @endauth
</div>