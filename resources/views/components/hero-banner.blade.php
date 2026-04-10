<section class="relative w-full">
    <div class="bg-gray-200 overflow-hidden">
        <img src="{{ asset('images/hero-banner.png') }}" class="w-full h-auto" alt="Hero Banner">
        <div class="absolute top-1/3 left-20">
            <h2 class="text-4xl font-light text-black bg-white/80 px-4 py-2 inline-block">BUILD YOUR <span class="font-bold uppercase">Style</span></h2>
        </div>
    </div>

    <div class="max-w-5xl mx-auto -mt-32 relative z-20 grid grid-cols-3 gap-6 px-6">
        @php
            // Ini adalah fondasi datamu.
            // Saat ini statis karena kategori utama jarang berubah. 
            // Jika suatu saat dipindah ke Database, struktur array-nya akan persis seperti ini.
            $categories = [
                [
                    'name' => 'TOPS',
                    'image' => asset('images/tops.png')
                ],
                [
                    'name' => 'BOTTOMS',
                    'image' => asset('images/bottoms.png')
                ],
                [
                    'name' => 'OUTER',
                    'image' => asset('images/outer.png')
                ]
            ];
        @endphp

        @foreach($categories as $cat)
        <div class="relative group cursor-pointer border-4 border-white shadow-xl overflow-hidden bg-gray-100">
            <img src="{{ $cat['image'] }}" class="w-full h-[350px] object-cover group-hover:scale-105 transition duration-500" alt="{{ $cat['name'] }}">
            
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-10">
                <span class="bg-white px-6 py-1 text-[17px] font-bold tracking-tighter uppercase shadow-sm">{{ $cat['name'] }}</span>
            </div>
            
            <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition duration-300"></div>
        </div>
        @endforeach
    </div>
</section>