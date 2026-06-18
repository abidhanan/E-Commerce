@props(['displays' => null])

<section class="relative w-full">
    <div class="bg-gray-200 overflow-hidden relative h-[120vh]">
        @if($displays)
            <img src="{{ $displays->image_1_path ? asset('storage/' . $displays->image_1_path) : asset('images/hero-banner.png') }}" 
                 class="absolute inset-0 w-full h-full object-cover" 
                 alt="Hero Banner">
            
            <div class="absolute inset-0 bg-black/20"></div> 
            
            <div class="absolute top-1/3 left-6 md:left-20 z-10">
                <h2 class="text-4xl md:text-6xl font-light text-black bg-white/90 px-6 py-3 inline-block shadow-lg">
                    {{ $displays->image_1_title ?? 'BUILD YOUR' }} 
                    <span class="font-bold uppercase">{{ $displays->image_1_sub_title ?? 'Style' }}</span>
                </h2>
            </div>

            @if($displays->running_text)
                <div class="absolute bottom-0 w-full bg-white/90 text-black py-4 overflow-hidden border-t border-black/10 z-20">
                    <div class="whitespace-nowrap animate-marquee flex gap-10 text-sm font-bold uppercase tracking-widest">
                        <span>{{ $displays->running_text }}</span>
                        <span>•</span>
                        <span>{{ $displays->running_text }}</span>
                        <span>•</span>
                        <span>{{ $displays->running_text }}</span>
                        <span>•</span>
                        <span>{{ $displays->running_text }}</span>
                    </div>
                </div>
                <style>
                    @keyframes marquee {
                        0% { transform: translateX(100%); }
                        100% { transform: translateX(-100%); }
                    }
                    .animate-marquee {
                        display: inline-block;
                        animation: marquee 25s linear infinite;
                    }
                </style>
            @endif

        @else
            <img src="{{ asset('images/hero-banner.png') }}" class="absolute inset-0 w-full h-full object-cover" alt="Hero Banner">
            <div class="absolute inset-0 bg-black/20"></div> 
            <div class="absolute top-1/3 left-6 md:left-20 z-10">
                <h2 class="text-4xl md:text-6xl font-light text-black bg-white/90 px-6 py-3 inline-block shadow-lg">
                    BUILD YOUR <span class="font-bold uppercase">Style</span>
                </h2>
            </div>
        @endif
    </div>
</section>