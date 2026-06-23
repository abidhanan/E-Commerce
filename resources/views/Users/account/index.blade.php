<x-layouts.app>
    <div class="max-w-screen-xl mx-auto px-6 py-16 flex flex-col md:flex-row gap-12 min-h-screen">
        
        @include('Users.account.sidebar')

        <main class="flex-1 border-l border-gray-300 pl-0 md:pl-12">
            
            <div class="bg-black text-white inline-block px-16 py-3 mb-10">
                <h2 id="page-title" class="text-3xl font-normal tracking-wider uppercase">Profile</h2>
            </div>

            {{-- ========================================== --}}
            {{-- TAB 1: PROFILE MANAGEMENT --}}
            {{-- ========================================== --}}
            <div id="tab-profile" class="max-w-2xl account-tab">
                <div class="mb-14">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-normal tracking-wide uppercase text-gray-900">User Email</h3>
                        <button type="button" disabled class="border border-black rounded-full px-6 py-2 text-sm font-medium text-gray-400 border-gray-300 cursor-not-allowed" title="Email tidak dapat diubah">
                            Change Email
                        </button>
                    </div>
                    <div class="space-y-1">
                        <p class="text-lg font-normal text-gray-900">Email Address</p>
                        <p class="text-base text-gray-600">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-normal tracking-wide uppercase text-gray-900">User Profile</h3>
                        <button type="button" onclick="openEditProfileModal()" class="border border-black rounded-full px-6 py-2 text-sm font-medium text-black hover:bg-black hover:text-white transition-colors duration-300">
                            Edit Profile
                        </button>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="space-y-1">
                            <p class="text-lg font-normal text-gray-900">Full Name</p>
                            <p class="text-base text-gray-600">{{ auth()->user()->name }}</p>
                        </div>

                        <div class="flex justify-between items-start">
                            <div class="space-y-1">
                                <p class="text-lg font-normal text-gray-900">Address</p>
                                <p class="text-base text-gray-600 leading-relaxed" id="display-primary-address">
                                    @if(isset($address) && $address)
                                        {{ $address->full_address }}, {{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}, Indonesia
                                    @else
                                        <span class="italic text-gray-400">There is no primary address yet.</span>
                                    @endif
                                </p>
                            </div>
                            
                            @if(isset($address) && $address)
                                <button type="button" onclick="openAddressModal({{ $address->id }})" class="text-sm font-bold text-[#c4a052] uppercase tracking-widest hover:underline transition">
                                    Edit Address
                                </button>
                            @else
                                <button type="button" onclick="openAddressModal()" class="text-sm font-bold text-black uppercase tracking-widest hover:text-[#c4a052] transition border-b border-black hover:border-[#c4a052]">
                                    + Add Address
                                </button>
                            @endif
                        </div>

                        <div class="space-y-1">
                            <p class="text-lg font-normal text-gray-900">Telephone Number</p>
                            <p class="text-base text-gray-600">{{ auth()->user()->phone ?? 'Belum diatur' }}</p>
                        </div>

                        <div class="space-y-1">
                            <p class="text-lg font-normal text-gray-900">Birth Date</p>
                            <p class="text-base text-gray-600">
                                {{ auth()->user()->date_of_birth ? \Carbon\Carbon::parse(auth()->user()->date_of_birth)->format('d/m/Y') : 'Belum diatur' }}
                            </p>
                        </div>

                        <div class="space-y-1">
                            <p class="text-lg font-normal text-gray-900">Gender</p>
                            <p class="text-base text-gray-600 capitalize">
                                {{ auth()->user()->gender === 'pria' ? 'Man' : (auth()->user()->gender === 'wanita' ? 'Woman' : (auth()->user()->gender ?? 'Belum diatur')) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========================================== --}}
            {{-- TAB 2: POSTED REVIEWS --}}
            {{-- ========================================== --}}
            <div id="tab-reviews" class="w-full account-tab hidden fade-in">
                
                @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 text-sm text-green-700 font-medium">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="space-y-6">
                    @forelse($reviews ?? [] as $review)
                        <div class="bg-white border border-gray-200 p-5 md:p-6 flex flex-col md:flex-row gap-6 shadow-sm hover:shadow-md transition-shadow duration-300 relative group">

                            {{-- BAGIAN KIRI: Info Entitas Produk --}}
                            <div class="md:w-1/3 border-b md:border-b-0 md:border-r border-gray-100 pb-4 md:pb-0 md:pr-6 flex flex-col justify-center">
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-3">
                                    Order <a href="{{ route('user.orders.show', $review->order->order_code) }}" class="text-[#c4a052] hover:underline">{{ $review->order->order_code }}</a>
                                </p>
                                
                                <div class="space-y-4">
                                    @foreach($review->order->items as $item)
                                        @php
                                            $img = $item->product->images->first();
                                            $imgPath = $img ? asset('storage/' . $img->image) : asset('images/no-image.jpg');
                                        @endphp
                                        <div class="flex items-center gap-4">
                                            <div class="w-16 h-16 bg-gray-100 border border-gray-200 shrink-0">
                                                <img src="{{ $imgPath }}" class="w-full h-full object-cover" alt="{{ $item->product->name }}">
                                            </div>
                                            <div>
                                                <a href="{{ route('product.show', $item->product->slug) }}" class="text-xs font-bold uppercase tracking-wider text-black hover:text-[#c4a052] transition-colors block truncate">
                                                    {{ $item->product->name }}
                                                </a>
                                                <p class="text-[10px] text-gray-500 uppercase tracking-widest mt-1">Size: {{ $item->productVariant->size }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- BAGIAN KANAN: Rating, Komentar & Kontrol UX Hibrida --}}
                            <div class="md:w-2/3 flex flex-col justify-center mt-2 md:mt-0 relative">
                                
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-1 text-sm">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <i class="bi bi-star-fill text-[#c4a052]"></i>
                                            @else
                                                <i class="bi bi-star text-gray-300"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        {{ $review->updated_at->format('d M Y') }} 
                                        {{ $review->updated_at->diffInSeconds($review->created_at) > 0 ? '(Edited)' : '' }}
                                    </span>
                                </div>
                                
                                <div class="bg-gray-50 p-4 border border-gray-100 rounded-sm relative">
                                    <i class="bi bi-quote text-2xl text-gray-200 absolute top-2 left-2"></i>
                                    <p class="text-sm text-gray-700 italic relative z-10 pl-6">
                                        "{{ $review->comment }}"
                                    </p>
                                </div>

                                {{-- KONTROL MUTLAK UX HIBRIDA: RAMAH SENTUHAN DI MOBILE, BERSIH DI DESKTOP --}}
                                <div class="flex justify-end gap-6 md:gap-3 mt-5 md:mt-0 md:absolute md:-top-2 md:right-0 md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-300 md:bg-white md:px-2 md:py-1 md:shadow-sm md:border md:border-gray-100 rounded-md">
                                    
                                    <button type="button" 
                                            data-review-id="{{ $review->id }}"
                                            data-review-rating="{{ $review->rating }}"
                                            data-review-comment="{{ $review->comment }}"
                                            onclick="openEditReviewModal(this)" 
                                            class="flex items-center gap-1.5 text-[11px] md:text-sm font-bold uppercase tracking-widest text-gray-400 hover:text-black transition-colors" title="Edit Ulasan">
                                        <svg class="w-4 h-4 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        <span class="md:hidden text-gray-600">Edit</span>
                                    </button>
                                    
                                    <form action="{{ route('user.reviews.destroy', $review->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan ini secara permanen?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="flex items-center gap-1.5 text-[11px] md:text-sm font-bold uppercase tracking-widest text-gray-400 hover:text-red-600 transition-colors" title="Hapus Ulasan">
                                            <svg class="w-4 h-4 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            <span class="md:hidden text-gray-600">Hapus</span>
                                        </button>
                                    </form>

                                </div>
                            </div>

                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-16 px-4 border border-dashed border-gray-300 bg-gray-50">
                            <i class="bi bi-chat-square-text text-4xl text-gray-300 mb-4"></i>
                            <h3 class="text-sm font-bold uppercase tracking-widest text-black mb-2">Belum Ada Ulasan</h3>
                            <p class="text-xs text-gray-500 text-center max-w-sm">
                                Kamu belum menulis ulasan apa pun. Selesaikan pesananmu terlebih dahulu untuk mulai membagikan pendapatmu tentang produk kami.
                            </p>
                            <a href="{{ route('user.orders.index') }}" class="mt-6 inline-block border border-black px-6 py-2 text-[10px] font-bold uppercase tracking-widest text-black hover:bg-black hover:text-white transition-colors">
                                Lihat Pesanan Saya
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

        </main>
    </div>

    {{-- MODAL EDIT PROFILE --}}
    <div id="edit-profile-modal" class="fixed inset-0 bg-black/60 z-[100] hidden flex items-center justify-center backdrop-blur-sm opacity-0 transition-opacity duration-300">
        <div class="bg-white w-full max-w-lg p-10 shadow-2xl relative transform scale-95 transition-transform duration-300" id="edit-profile-content">
            
            <button type="button" onclick="closeEditProfileModal()" class="absolute top-6 right-6 text-gray-400 hover:text-black transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <h2 class="text-2xl font-light tracking-wide uppercase mb-8 text-gray-900 border-b border-gray-200 pb-4">Edit Profile</h2>

            <form action="{{ route('account.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-xs font-bold tracking-wide uppercase mb-2">Full Name</label>
                    <input type="text" name="name" value="{{ auth()->user()->name }}" required class="w-full bg-gray-50 border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition">
                </div>
                <div>
                    <label class="block text-xs font-bold tracking-wide uppercase mb-2">Telephone Number</label>
                    <input type="text" name="phone" value="{{ auth()->user()->phone }}" class="w-full bg-gray-50 border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold tracking-wide uppercase mb-2">Birth Date</label>
                        <input type="date" name="date_of_birth" value="{{ auth()->user()->date_of_birth ? \Carbon\Carbon::parse(auth()->user()->date_of_birth)->format('Y-m-d') : '' }}" max="{{ date('Y-m-d') }}" class="w-full bg-gray-50 border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition uppercase">
                    </div>
                    <div>
                        <label class="block text-xs font-bold tracking-wide uppercase mb-2">Gender</label>
                        <select name="gender" class="w-full bg-gray-50 border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition capitalize">
                            <option value="" disabled {{ !auth()->user()->gender ? 'selected' : '' }}>Select</option>
                            <option value="pria" {{ auth()->user()->gender === 'pria' ? 'selected' : '' }}>Man</option>
                            <option value="wanita" {{ auth()->user()->gender === 'wanita' ? 'selected' : '' }}>Woman</option>
                            <option value="unisex" {{ auth()->user()->gender === 'unisex' ? 'selected' : '' }}>Unisex</option>
                        </select>
                    </div>
                </div>

                <div class="pt-6 flex gap-4">
                    <button type="button" onclick="closeEditProfileModal()" class="w-1/2 border border-black text-black font-bold tracking-widest uppercase py-4 hover:bg-gray-50 transition-colors duration-300">
                        CANCEL
                    </button>
                    <button type="submit" class="w-1/2 bg-black text-white font-bold tracking-widest uppercase py-4 hover:bg-[#c4a052] transition-colors duration-300">
                        SAVE CHANGES
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL ADDRESS --}}
    <div id="address-modal" class="fixed inset-0 bg-black/60 z-[110] hidden flex items-center justify-center backdrop-blur-sm opacity-0 transition-opacity duration-300">
        <div class="bg-white w-full max-w-2xl p-10 shadow-2xl relative transform scale-95 transition-transform duration-300 h-screen sm:h-auto sm:max-h-[90vh] overflow-y-auto" id="address-content">
            
            <button type="button" onclick="closeAddressModal()" class="absolute top-6 right-6 text-gray-400 hover:text-black transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <h2 id="modal-address-title" class="text-2xl font-light tracking-wide uppercase mb-8 text-gray-900 border-b border-gray-200 pb-4">Address Management</h2>

            <form id="address-form" class="space-y-6">
                <input type="hidden" id="form-address-id" value="">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold tracking-wide uppercase mb-2">Label (ex: Rumah/Kantor)</label>
                        <input type="text" id="addr-label" required class="w-full bg-gray-50 border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold tracking-wide uppercase mb-2">Recipient Name</label>
                        <input type="text" id="addr-name" required class="w-full bg-gray-50 border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold tracking-wide uppercase mb-2">Phone Number</label>
                        <input type="text" id="addr-phone" required class="w-full bg-gray-50 border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold tracking-wide uppercase mb-2">Postal Code</label>
                        <input type="text" id="addr-postal" class="w-full bg-gray-50 border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold tracking-wide uppercase mb-2">City</label>
                        <input type="text" id="addr-city" required class="w-full bg-gray-50 border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold tracking-wide uppercase mb-2">Province</label>
                        <input type="text" id="addr-province" required class="w-full bg-gray-50 border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold tracking-wide uppercase mb-2">Full Address</label>
                    <textarea id="addr-full" rows="3" required class="w-full bg-gray-50 border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition"></textarea>
                </div>

                <div class="pt-6 flex gap-4">
                    <button type="button" onclick="closeAddressModal()" class="w-1/2 border border-black text-black font-bold tracking-widest uppercase py-4 hover:bg-gray-50 transition-colors duration-300">
                        CANCEL
                    </button>
                    <button type="submit" id="btn-save-address" class="w-1/2 bg-black text-white font-bold tracking-widest uppercase py-4 hover:bg-[#c4a052] transition-colors duration-300">
                        SAVE ADDRESS
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT REVIEW --}}
    <div id="edit-review-modal" class="fixed inset-0 bg-black/60 z-[120] hidden flex items-center justify-center backdrop-blur-sm opacity-0 transition-opacity duration-300">
        <div class="bg-white w-full max-w-lg p-10 shadow-2xl relative transform scale-95 transition-transform duration-300" id="edit-review-content">
            
            <button type="button" onclick="closeEditReviewModal()" class="absolute top-6 right-6 text-gray-400 hover:text-black transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <h2 class="text-2xl font-light tracking-wide uppercase mb-8 text-gray-900 border-b border-gray-200 pb-4">Edit Review</h2>

            <form id="edit-review-form" method="POST" action="" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="flex flex-col items-center justify-center mb-6">
                    <p class="text-xs font-bold uppercase tracking-widest mb-3">Rating</p>
                    <div class="flex gap-2 flex-row-reverse justify-end" id="star-rating-container">
                        <input type="radio" id="star5" name="rating" value="5" class="hidden peer" required />
                        <label for="star5" class="text-3xl text-gray-300 peer-checked:text-[#c4a052] hover:text-[#c4a052] cursor-pointer transition-colors"><i class="bi bi-star-fill"></i></label>
                        
                        <input type="radio" id="star4" name="rating" value="4" class="hidden peer" />
                        <label for="star4" class="text-3xl text-gray-300 peer-checked:text-[#c4a052] hover:text-[#c4a052] peer-hover:text-[#c4a052] cursor-pointer transition-colors"><i class="bi bi-star-fill"></i></label>
                        
                        <input type="radio" id="star3" name="rating" value="3" class="hidden peer" />
                        <label for="star3" class="text-3xl text-gray-300 peer-checked:text-[#c4a052] hover:text-[#c4a052] peer-hover:text-[#c4a052] cursor-pointer transition-colors"><i class="bi bi-star-fill"></i></label>
                        
                        <input type="radio" id="star2" name="rating" value="2" class="hidden peer" />
                        <label for="star2" class="text-3xl text-gray-300 peer-checked:text-[#c4a052] hover:text-[#c4a052] peer-hover:text-[#c4a052] cursor-pointer transition-colors"><i class="bi bi-star-fill"></i></label>
                        
                        <input type="radio" id="star1" name="rating" value="1" class="hidden peer" />
                        <label for="star1" class="text-3xl text-gray-300 peer-checked:text-[#c4a052] hover:text-[#c4a052] peer-hover:text-[#c4a052] cursor-pointer transition-colors"><i class="bi bi-star-fill"></i></label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold tracking-wide uppercase mb-2">Ulasan Anda</label>
                    <textarea name="comment" id="edit-review-comment" rows="4" required class="w-full bg-gray-50 border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition resize-none"></textarea>
                </div>

                <div class="pt-4 flex gap-4">
                    <button type="button" onclick="closeEditReviewModal()" class="w-1/2 border border-black text-black font-bold tracking-widest uppercase py-4 hover:bg-gray-50 transition-colors duration-300">
                        CANCEL
                    </button>
                    <button type="submit" class="w-1/2 bg-black text-white font-bold tracking-widest uppercase py-4 hover:bg-[#c4a052] transition-colors duration-300">
                        UPDATE REVIEW
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- ENGINE JAVASCRIPT GLOBAL (DENGAN PENYELARASAN DYNAMIC ROUTING) --}}
    {{-- ======================================================== --}}
    <script>
        function handleHashTabs() {
            const hash = window.location.hash || '#profile';
            
            const tabProfile = document.getElementById('tab-profile');
            const tabReviews = document.getElementById('tab-reviews');
            const pageTitle = document.getElementById('page-title');
            
            const navProfile = document.getElementById('nav-profile');
            const navReviews = document.getElementById('nav-reviews');

            if(tabProfile) tabProfile.classList.add('hidden');
            if(tabReviews) tabReviews.classList.add('hidden');
            if(navProfile) navProfile.className = 'hover:text-[#c4a052] transition account-nav-link text-gray-700';
            if(navReviews) navReviews.className = 'hover:text-[#c4a052] transition account-nav-link text-gray-700';

            if (hash === '#reviews') {
                if(tabReviews) tabReviews.classList.remove('hidden');
                if(pageTitle) pageTitle.textContent = 'Posted Reviews';
                if(navReviews) navReviews.className = 'text-black font-bold transition account-nav-link';
            } else {
                if(tabProfile) tabProfile.classList.remove('hidden');
                if(pageTitle) pageTitle.textContent = 'Profile';
                if(navProfile) navProfile.className = 'text-black font-bold transition account-nav-link';
            }
        }

        window.addEventListener('DOMContentLoaded', handleHashTabs);
        window.addEventListener('hashchange', handleHashTabs);

        // --- MODAL PROFILE ---
        function openEditProfileModal() {
            const editModal = document.getElementById('edit-profile-modal');
            const editModalContent = document.getElementById('edit-profile-content');
            
            editModal.classList.remove('hidden');
            setTimeout(() => {
                editModal.classList.remove('opacity-0');
                editModalContent.classList.remove('scale-95');
            }, 10);
        }

        function closeEditProfileModal() {
            const editModal = document.getElementById('edit-profile-modal');
            const editModalContent = document.getElementById('edit-profile-content');
            
            editModal.classList.add('opacity-0');
            editModalContent.classList.add('scale-95');
            setTimeout(() => {
                editModal.classList.add('hidden');
            }, 300);
        }

        // --- MODAL REVIEWS (MENGGUNAKAN DATA ATRIBUT AGAR KEBAL XSS) ---
        function openEditReviewModal(buttonElement) {
            const reviewId = buttonElement.getAttribute('data-review-id');
            const rating = buttonElement.getAttribute('data-review-rating');
            const comment = buttonElement.getAttribute('data-review-comment');

            const modal = document.getElementById('edit-review-modal');
            const content = document.getElementById('edit-review-content');
            const form = document.getElementById('edit-review-form');
            
            // Dynamic routing yang tahan terhadap struktur sub-folder
            form.action = `{{ url('/account/reviews') }}/${reviewId}`;
            
            document.getElementById('edit-review-comment').value = comment;
            
            const starInput = document.getElementById(`star${rating}`);
            if (starInput) starInput.checked = true;

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95');
            }, 10);
        }

        function closeEditReviewModal() {
            const modal = document.getElementById('edit-review-modal');
            const content = document.getElementById('edit-review-content');
            
            modal.classList.add('opacity-0');
            content.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // --- MODAL ADDRESS (DYNAMIC ROUTING) ---
        const addressModal = document.getElementById('address-modal');
        const addressModalContent = document.getElementById('address-content');
        
        let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        async function openAddressModal(addressId = null) {
            document.getElementById('address-form').reset();
            document.getElementById('form-address-id').value = '';
            
            document.getElementById('modal-address-title').innerText = addressId ? 'Edit Address' : 'Add New Address';
            document.getElementById('btn-save-address').innerText = addressId ? 'UPDATE ADDRESS' : 'SAVE ADDRESS';

            if (!addressId) {
                document.getElementById('addr-name').value = "{{ auth()->user()->name }}";
                document.getElementById('addr-phone').value = "{{ auth()->user()->phone ?? '' }}";
            }

            addressModal.classList.remove('hidden');
            setTimeout(() => {
                addressModal.classList.remove('opacity-0');
                addressModalContent.classList.remove('scale-95');
            }, 10);

            if (addressId) {
                try {
                    const response = await fetch(`{{ url('/address') }}/${addressId}`);
                    if (!response.ok) throw new Error('Network response was not ok');
                    const data = await response.json();
                    
                    document.getElementById('form-address-id').value = data.id;
                    document.getElementById('addr-label').value = data.label;
                    document.getElementById('addr-name').value = data.recipient_name;
                    document.getElementById('addr-phone').value = data.phone_number;
                    document.getElementById('addr-city').value = data.city;
                    document.getElementById('addr-province').value = data.province;
                    document.getElementById('addr-full').value = data.full_address;
                    document.getElementById('addr-postal').value = data.postal_code || '';
                } catch (error) {
                    console.error("Gagal mengambil data:", error);
                    alert("Gagal memuat data alamat.");
                }
            }
        }

        function closeAddressModal() {
            addressModal.classList.add('opacity-0');
            addressModalContent.classList.add('scale-95');
            setTimeout(() => {
                addressModal.classList.add('hidden');
            }, 300);
        }

        // --- EKSEKUSI DATA ADDRESS ---
        document.getElementById('address-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btnSave = document.getElementById('btn-save-address');
            btnSave.innerHTML = 'PROCESSING...';
            btnSave.disabled = true;

            const addressId = document.getElementById('form-address-id').value;
            const payload = {
                label: document.getElementById('addr-label').value,
                recipient_name: document.getElementById('addr-name').value,
                phone_number: document.getElementById('addr-phone').value,
                city: document.getElementById('addr-city').value,
                province: document.getElementById('addr-province').value,
                full_address: document.getElementById('addr-full').value,
                postal_code: document.getElementById('addr-postal').value,
            };

            const url = addressId ? `{{ url('/address') }}/${addressId}` : `{{ url('/address') }}`;
            const method = addressId ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (response.ok) {
                    window.location.reload(); 
                } else {
                    alert(result.message || 'Operasi gagal.');
                    btnSave.innerHTML = addressId ? 'UPDATE ADDRESS' : 'SAVE ADDRESS';
                    btnSave.disabled = false;
                }
            } catch (error) {
                console.error(error);
                alert('Koneksi terputus.');
                btnSave.innerHTML = addressId ? 'UPDATE ADDRESS' : 'SAVE ADDRESS';
                btnSave.disabled = false;
            }
        });
    </script>
</x-layouts.app>