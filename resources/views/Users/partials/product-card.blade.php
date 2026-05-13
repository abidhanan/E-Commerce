@php
    $primaryImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
    $hoverImage = $product->images->firstWhere('is_hover', true) ?? $primaryImage;
    $lowestPrice = $product->variants->sortBy('price')->first()?->price ?? 0;
@endphp

<a href="{{ route('product.show', $product->slug) }}" class="listing-product-card">
    <span class="listing-product-image">
        <img src="{{ $primaryImage ? asset('storage/' . $primaryImage->image) : 'https://via.placeholder.com/600x750' }}"
            alt="{{ $product->name }}" class="listing-product-main">
        <img src="{{ $hoverImage ? asset('storage/' . $hoverImage->image) : ($primaryImage ? asset('storage/' . $primaryImage->image) : 'https://via.placeholder.com/600x750') }}"
            alt="{{ $product->name }}" class="listing-product-hover">
    </span>

    <span class="listing-product-copy">
        <span class="listing-product-name">{{ $product->name }}</span>
        <span class="listing-product-price">Rp {{ number_format($lowestPrice, 0, ',', '.') }}</span>
    </span>
</a>
