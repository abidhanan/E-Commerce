<x-layouts.app>
    
    <x-hero-banner />

    <section class="mt-16 w-full relative h-[300px] md:h-[450px]">
        <img src="{{ asset('images/image 45.png') }}" class="w-full h-full object-cover" alt="Clothique x Sabrina">
        <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
            <h2 class="text-white text-3xl md:text-5xl font-serif tracking-widest text-center px-4 drop-shadow-lg">Clothique x Sabrina Carpenter</h2>
        </div>
    </section>

    <section class="max-w-screen-xl mx-auto px-6 py-16">
        <h2 class="text-xl font-bold mb-8 uppercase tracking-wide">Flash Sale</h2>
        
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($trendingProducts as $item)
                <x-product-card :product="$item" />
            @endforeach
        </div>
    </section>

    <section class="w-full relative h-[400px] flex">
        <div class="w-1/3 h-full hidden md:block">
            <img src="https://images.unsplash.com/photo-1584916201218-f4242ceb4809?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover" alt="Bag">
        </div>
        <div class="w-full md:w-1/3 h-full">
            <img src="https://images.unsplash.com/photo-1559563458-527698bf5295?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover" alt="Belt">
        </div>
        <div class="w-1/3 h-full hidden md:block">
            <img src="https://images.unsplash.com/photo-1523170335258-f5ed11844a49?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover" alt="Watch">
        </div>
        
        <div class="absolute inset-0 bg-black/40 flex items-center justify-center pointer-events-none">
            <h2 class="text-white text-3xl md:text-5xl font-serif tracking-widest text-center px-4 drop-shadow-xl">Clothique Accessories Collection</h2>
        </div>
    </section>

    <section class="max-w-screen-xl mx-auto px-6 py-20 md:grid-cols-4">
        <h2 class="text-xl font-bold mb-10 uppercase tracking-wide">Category</h2>
        
        @php
            $categories = [
                ['name' => 'SHIRTS', 'img' => asset('images/category/shirt.png')],
                ['name' => 'PANTS', 'img' => asset('images/category/pants.png')],
                ['name' => 'SKIRTS', 'img' => asset('images/category/skirt.png')],
                ['name' => 'SANDALS', 'img' => asset('images/category/sandals.png')],
                ['name' => 'OUTERS', 'img' => asset('images/category/outer.png')],
                ['name' => 'DRESSES', 'img' => asset('images/category/dress.png')],
                ['name' => 'BAGS', 'img' => asset('images/category/bag.png')],
                ['name' => 'SHOES', 'img' => asset('images/category/shoes.png')],
                ['name' => 'HATS', 'img' => asset('images/category/hat.png')],
                ['name' => 'ACCESSORIES', 'img' => asset('images/category/accessories.png')],
            ];
        @endphp

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-8">
            @foreach($categories as $cat)
                <a href="#" class="flex flex-col items-center group">
                    <div class="w-24 h-24 md:w-32 md:h-32 rounded-full overflow-hidden mb-4 border border-gray-200">
                        <img src="{{ $cat['img'] }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" alt="{{ $cat['name'] }}">
                    </div>
                    <span class="text-xs font-bold tracking-widest text-gray-800">{{ $cat['name'] }}</span>
                </a>
            @endforeach
        </div>
    </section>

    <section class="w-full relative h-[450px]">
        <img src="{{ asset('images/banner.png') }}" class="w-full h-full object-cover" alt="Summer Sunset">
        <div class="absolute inset-0 bg-black/20 flex flex-col items-center justify-center">
            <h2 class="text-white text-4xl md:text-6xl font-serif tracking-widest text-center drop-shadow-md">Summer Collection</h2>
            <p class="text-white text-lg tracking-widest mt-2 drop-shadow-md">Clothique</p>
        </div>
    </section>

    <section class="max-w-screen-xl mx-auto px-6 py-16">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($trendingProducts as $item)
                <x-product-card :product="$item" />
            @endforeach
        </div>
    </section>

    <section class="w-full">
        <div class="w-full relative h-[600px]">
            <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=1920&auto=format&fit=crop" class="w-full h-full object-cover" alt="Green Hills">
            <div class="absolute inset-0 bg-black/10 flex items-center justify-center">
                <h2 class="text-white text-6xl md:text-8xl font-serif tracking-widest drop-shadow-lg">Clothique</h2>
            </div>
        </div>
        
        <div class="bg-[#9c8443] px-6 py-16 text-center text-white">
            <div class="max-w-4xl mx-auto">
                <p class="text-sm md:text-base leading-relaxed tracking-wide font-light">
                    Clothique is an exclusive brand committed to modern elegance, bringing you premium collections that blend comfort, quality, and timeless style. From high-end daily wear to statement pieces, we ensure every thread reflects luxury. Discover the art of sophistication with Clothique.
                </p>
            </div>
        </div>
    </section>

</x-layouts.app>