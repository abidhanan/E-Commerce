<x-layouts.app>
    
    <x-hero-banner />

    <section class="py-12 border-y border-gray-100 overflow-hidden bg-white">
        <div class="text-center mb-8">
            <span class="bg-black text-white px-10 py-2 text-sm font-bold uppercase tracking-[0.3em]">Our Brand</span>
        </div>

        @php
            $brands = [
                ['name' => 'Chanel', 'img' => 'https://upload.wikimedia.org/wikipedia/en/thumb/9/92/Chanel_logo_interlocking_cs.svg/1280px-Chanel_logo_interlocking_cs.svg.png', 'class' => 'h-16 md:h-20'],
                ['name' => 'LV', 'img' => 'https://upload.wikimedia.org/wikipedia/commons/7/76/Louis_Vuitton_logo_and_wordmark.svg', 'class' => 'h-16 md:h-20'],
                ['name' => 'Zara', 'img' => 'https://upload.wikimedia.org/wikipedia/commons/f/fd/Zara_Logo.svg', 'class' => 'h-12 md:h-16'],
                ['name' => 'Prada', 'img' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b8/Prada-Logo.svg/1280px-Prada-Logo.svg.png', 'class' => 'h-10 md:h-11'],
                ['name' => 'Versace', 'img' => 'https://www.svgrepo.com/show/303462/versace-medusa-2-logo.svg', 'class' => 'h-16 md:h-22'],
                ['name' => 'Hermes', 'img' => 'https://upload.wikimedia.org/wikipedia/en/thumb/e/e4/Herm%C3%A8s.svg/250px-Herm%C3%A8s.svg.png', 'class' => 'h-16 md:h-20'],
                ['name' => 'Dior', 'img' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/56/Dior_Logo_2022.svg/3840px-Dior_Logo_2022.svg.png', 'class' => 'h-16 md:h-12'],
                ['name' => 'Sephora', 'img' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/21/Sephora_logo.svg/1280px-Sephora_logo.svg.png', 'class' => 'h-16 md:h-7'],
                ['name' => 'Celine', 'img' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/81/Celine_logo.svg/1280px-Celine_logo.svg.png', 'class' => 'h-16 md:h-7'],
            ];
        @endphp

        <div class="flex overflow-hidden group">
            <div class="flex gap-24 pr-24 animate-infinite-scroll items-center shrink-0 group-hover:[animation-play-state:paused]">
                @for ($i = 0; $i < 4; $i++)
                    @foreach($brands as $brand)
                        <img src="{{ $brand['img'] }}" class="{{ $brand['class'] }} w-auto object-contain shrink-0 grayscale opacity-50 hover:grayscale-0 hover:opacity-100 transition duration-300" alt="{{ $brand['name'] }}">
                    @endforeach
                @endfor
            </div>

            <div class="flex gap-24 pr-24 animate-infinite-scroll items-center shrink-0 group-hover:[animation-play-state:paused]" aria-hidden="true">
                @for ($i = 0; $i < 4; $i++)
                    @foreach($brands as $brand)
                        <img src="{{ $brand['img'] }}" class="{{ $brand['class'] }} w-auto object-contain shrink-0 grayscale opacity-50 hover:grayscale-0 hover:opacity-100 transition duration-300" alt="{{ $brand['name'] }}">
                    @endforeach
                @endfor
            </div>

        </div>
    </section>

    <section class="max-w-screen-xl mx-auto px-6 pb-20">
        <h2 class="text-2xl font-bold mb-8 uppercase tracking-wide">Flash Sale</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($trendingProducts as $item)
                <x-product-card :product="$item" />
            @endforeach
        </div>
    </section>

</x-layouts.app>