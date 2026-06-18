<x-layouts.app>
    <div class="max-w-screen-xl mx-auto px-6 py-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Edit Konfigurasi Display Utama</h1>
            <p class="text-sm text-gray-500 mt-1">Ubah banner, teks, dan pengumuman yang akan tampil di halaman depan pengunjung.</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <ul class="text-xs text-red-700 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.displays.update', $display->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8 bg-white p-8 border border-gray-200 shadow-sm">
            @csrf
            @method('PUT')

            <div class="border border-gray-200 p-6 bg-gray-50">
                <h2 class="text-sm font-bold uppercase tracking-widest text-black mb-6 border-b border-gray-200 pb-2">Banner 1 (Hero Layar Penuh)</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-xs font-bold tracking-wide uppercase mb-2">Gambar Banner</label>
                        
                        @if($display->image_1_path)
                            <div class="mb-4 relative w-full aspect-video bg-gray-200 overflow-hidden border border-gray-300">
                                <img src="{{ asset('storage/' . $display->image_1_path) }}" alt="Preview Image 1" class="w-full h-full object-cover">
                                <div class="absolute top-2 right-2 bg-black text-white text-[10px] font-bold px-2 py-1 uppercase tracking-widest">Aktif</div>
                            </div>
                        @endif

                        <input type="file" name="image_1_path" accept="image/jpeg,image/png,image/webp" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-bold file:bg-black file:text-white hover:file:bg-gray-800 transition">
                        <p class="text-[10px] text-gray-400 mt-2 uppercase tracking-widest">Maksimal 3MB. Format: JPG, PNG, WEBP. Biarkan kosong jika tidak ingin mengubah gambar.</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold tracking-wide uppercase mb-2">Judul Utama (Tipis)</label>
                            <input type="text" name="image_1_title" value="{{ old('image_1_title', $display->image_1_title) }}" placeholder="Contoh: BUILD YOUR" class="w-full bg-white border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold tracking-wide uppercase mb-2">Sub-Judul (Tebal)</label>
                            <input type="text" name="image_1_sub_title" value="{{ old('image_1_sub_title', $display->image_1_sub_title) }}" placeholder="Contoh: STYLE" class="w-full bg-white border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition">
                        </div>
                    </div>
                </div>
            </div>

            <div class="border border-gray-200 p-6 bg-gray-50">
                <h2 class="text-sm font-bold uppercase tracking-widest text-black mb-6 border-b border-gray-200 pb-2">Pengumuman (Running Text)</h2>
                <div>
                    <label class="block text-xs font-bold tracking-wide uppercase mb-2">Teks Berjalan</label>
                    <input type="text" name="running_text" value="{{ old('running_text', $display->running_text) }}" placeholder="Contoh: FREE SHIPPING ON ALL ORDERS OVER RP 500.000" class="w-full bg-white border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition">
                    <p class="text-[10px] text-gray-400 mt-2 uppercase tracking-widest">Teks ini akan muncul berjalan di bagian bawah Hero Banner.</p>
                </div>
            </div>

            <div class="border border-gray-200 p-6">
                <h2 class="text-sm font-bold uppercase tracking-widest text-gray-500 mb-6 border-b border-gray-200 pb-2">Banner 2 (Opsional / Slider Tambahan)</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 opacity-70 hover:opacity-100 transition-opacity">
                    <div>
                        <label class="block text-xs font-bold tracking-wide uppercase mb-2">Gambar Banner 2</label>
                        @if($display->image_2_path)
                            <div class="mb-4 relative w-full aspect-video bg-gray-200 overflow-hidden border border-gray-300">
                                <img src="{{ asset('storage/' . $display->image_2_path) }}" alt="Preview Image 2" class="w-full h-full object-cover">
                            </div>
                        @endif
                        <input type="file" name="image_2_path" accept="image/jpeg,image/png,image/webp" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-bold file:bg-gray-200 file:text-black hover:file:bg-gray-300 transition">
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold tracking-wide uppercase mb-2">Judul Utama</label>
                            <input type="text" name="image_2_title" value="{{ old('image_2_title', $display->image_2_title) }}" class="w-full bg-gray-50 border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-6 border-t border-gray-200 gap-4">
                <a href="{{ route('admin.displays.index') }}" class="px-8 py-3 bg-white border border-gray-300 text-black text-xs font-bold uppercase tracking-widest hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-10 py-3 bg-black text-white text-xs font-bold uppercase tracking-widest hover:bg-[#c4a052] transition">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</x-layouts.app>