<x-layouts.app>
    <div class="max-w-screen-xl mx-auto px-6 py-16 flex flex-col md:flex-row gap-12 min-h-screen">
        
        @include('Users.account.sidebar')

        <main class="flex-1 border-l border-gray-300 pl-0 md:pl-12">
            
            <div class="bg-black text-white inline-block px-16 py-3 mb-10">
                <h2 class="text-3xl font-normal tracking-wider uppercase">Profile</h2>
            </div>

            <div class="max-w-2xl">
                
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
                                        <span class="italic text-gray-400">Belum ada alamat utama.</span>
                                    @endif
                                </p>
                            </div>
                            @if(isset($address) && $address)
                                <button type="button" onclick="openEditAddressModal({{ $address->id }})" class="text-sm font-bold text-[#c4a052] uppercase tracking-widest hover:underline transition">
                                    Edit Address
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
        </main>
    </div>

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

    <div id="edit-address-modal" class="fixed inset-0 bg-black/60 z-[110] hidden flex items-center justify-center backdrop-blur-sm opacity-0 transition-opacity duration-300">
        <div class="bg-white w-full max-w-2xl p-10 shadow-2xl relative transform scale-95 transition-transform duration-300 h-screen sm:h-auto sm:max-h-[90vh] overflow-y-auto" id="edit-address-content">
            
            <button type="button" onclick="closeEditAddressModal()" class="absolute top-6 right-6 text-gray-400 hover:text-black transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <h2 class="text-2xl font-light tracking-wide uppercase mb-8 text-gray-900 border-b border-gray-200 pb-4">Edit Address</h2>

            <form id="edit-address-form" class="space-y-6">
                <input type="hidden" id="edit-address-id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold tracking-wide uppercase mb-2">Label (ex: Rumah/Kantor)</label>
                        <input type="text" id="edit-addr-label" required class="w-full bg-gray-50 border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold tracking-wide uppercase mb-2">Recipient Name</label>
                        <input type="text" id="edit-addr-name" required class="w-full bg-gray-50 border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold tracking-wide uppercase mb-2">Phone Number</label>
                        <input type="text" id="edit-addr-phone" required class="w-full bg-gray-50 border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold tracking-wide uppercase mb-2">Postal Code</label>
                        <input type="text" id="edit-addr-postal" class="w-full bg-gray-50 border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold tracking-wide uppercase mb-2">City</label>
                        <input type="text" id="edit-addr-city" required class="w-full bg-gray-50 border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold tracking-wide uppercase mb-2">Province</label>
                        <input type="text" id="edit-addr-province" required class="w-full bg-gray-50 border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold tracking-wide uppercase mb-2">Full Address</label>
                    <textarea id="edit-addr-full" rows="3" required class="w-full bg-gray-50 border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition"></textarea>
                </div>

                <div class="pt-6 flex gap-4">
                    <button type="button" onclick="closeEditAddressModal()" class="w-1/2 border border-black text-black font-bold tracking-widest uppercase py-4 hover:bg-gray-50 transition-colors duration-300">
                        CANCEL
                    </button>
                    <button type="submit" id="btn-save-address" class="w-1/2 bg-black text-white font-bold tracking-widest uppercase py-4 hover:bg-[#c4a052] transition-colors duration-300">
                        UPDATE ADDRESS
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // --- 1. SCRIPT MODAL PROFILE ---
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

        // --- 2. SCRIPT MODAL ADDRESS ---
        const addressModal = document.getElementById('edit-address-modal');
        const addressModalContent = document.getElementById('edit-address-content');
        
        // Proteksi jika token CSRF absen dari layout utama
        let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if(!csrfToken) {
            const meta = document.createElement('meta');
            meta.name = "csrf-token";
            meta.content = "{{ csrf_token() }}";
            document.head.appendChild(meta);
            csrfToken = meta.content;
        }

        async function openEditAddressModal(addressId) {
            addressModal.classList.remove('hidden');
            setTimeout(() => {
                addressModal.classList.remove('opacity-0');
                addressModalContent.classList.remove('scale-95');
            }, 10);

            try {
                const response = await fetch(`/address/${addressId}`);
                if (!response.ok) throw new Error('Network response was not ok');
                const data = await response.json();
                
                document.getElementById('edit-address-id').value = data.id;
                document.getElementById('edit-addr-label').value = data.label;
                document.getElementById('edit-addr-name').value = data.recipient_name;
                document.getElementById('edit-addr-phone').value = data.phone_number;
                document.getElementById('edit-addr-city').value = data.city;
                document.getElementById('edit-addr-province').value = data.province;
                document.getElementById('edit-addr-full').value = data.full_address;
                document.getElementById('edit-addr-postal').value = data.postal_code || '';
            } catch (error) {
                console.error("Gagal mengambil data alamat:", error);
                alert("Gagal memuat data alamat. Periksa koneksi.");
            }
        }

        function closeEditAddressModal() {
            addressModal.classList.add('opacity-0');
            addressModalContent.classList.add('scale-95');
            setTimeout(() => {
                addressModal.classList.add('hidden');
            }, 300);
        }

        // --- 3. AJAX SUBMIT ADDRESS ---
        document.getElementById('edit-address-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btnSave = document.getElementById('btn-save-address');
            btnSave.innerHTML = 'SAVING...';
            btnSave.disabled = true;

            const addressId = document.getElementById('edit-address-id').value;
            const payload = {
                label: document.getElementById('edit-addr-label').value,
                recipient_name: document.getElementById('edit-addr-name').value,
                phone_number: document.getElementById('edit-addr-phone').value,
                city: document.getElementById('edit-addr-city').value,
                province: document.getElementById('edit-addr-province').value,
                full_address: document.getElementById('edit-addr-full').value,
                postal_code: document.getElementById('edit-addr-postal').value,
            };

            try {
                const response = await fetch(`/address/${addressId}`, {
                    method: 'PUT',
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
                    alert(result.message || 'Gagal memperbarui alamat');
                    btnSave.innerHTML = 'UPDATE ADDRESS';
                    btnSave.disabled = false;
                }
            } catch (error) {
                console.error(error);
                alert('Koneksi terputus.');
                btnSave.innerHTML = 'UPDATE ADDRESS';
                btnSave.disabled = false;
            }
        });
    </script>
</x-layouts.app>