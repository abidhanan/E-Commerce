<aside class="w-full md:w-64 flex-shrink-0 sticky top-28 h-fit">
    <div class="mb-10">
        <h3 class="text-lg font-bold uppercase tracking-widest mb-4 border-b-4 border-black inline-block pb-1">Membership</h3>
        <ul class="space-y-4 mt-4 text-sm text-gray-700 font-medium">
            <li><a href="{{ route('user.orders.index') }}" class="{{ request()->routeIs('user.orders.*') ? 'text-black font-bold' : 'hover:text-[#c4a052]' }} transition">Purchase History</a></li>
            <li><a href="{{ route('wishlist.index') }}" class="{{ request()->routeIs('wishlist.*') ? 'text-black font-bold' : 'hover:text-[#c4a052]' }} transition">Wishlist</a></li>
            <li><a href="#" class="hover:text-[#c4a052] transition">Posted Reviews</a></li>
        </ul>
    </div>

    <div>
        <h3 class="text-lg font-bold uppercase tracking-widest mb-4 border-b-4 border-black inline-block pb-1">Profile Settings</h3>
        <ul class="space-y-4 mt-4 text-sm text-gray-700 font-medium">
            <li><a href="{{ url('/account') }}" class="{{ request()->is('account') ? 'text-black font-bold' : 'hover:text-[#c4a052]' }} transition">Profile</a></li>
            <li><a href="{{ url('/password/reset') }}" class="{{ request()->is('password/reset') ? 'text-black font-bold' : 'hover:text-[#c4a052]' }} transition">Change Password</a></li>
            <li><a href="#" class="hover:text-[#c4a052] transition">Manage Payments</a></li>
            <li class="pt-4 border-t border-gray-200 mt-4">
                <form id="logout-form" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="button" onclick="openLogoutModal()" class="hover:text-red-600 font-bold transition text-left w-full uppercase tracking-widest text-sm">
                        Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</aside> <div id="logout-modal" class="fixed inset-0 bg-black/60 z-[1000] hidden flex items-center justify-center backdrop-blur-sm opacity-0 transition-opacity duration-300">
    <div class="bg-white w-full max-w-sm p-8 shadow-2xl relative transform scale-95 transition-transform duration-300 text-center" id="logout-modal-content">
        
        <svg class="w-16 h-16 text-red-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
        
        <h2 class="text-xl font-bold uppercase tracking-widest mb-2 text-gray-900">Log Out?</h2>
        <p class="text-sm text-gray-500 mb-8 font-medium">Are you sure you want to sign out of your account?</p>
        
        <div class="flex gap-4">
            <button type="button" onclick="closeLogoutModal()" class="w-1/2 border border-black text-black font-bold tracking-widest uppercase py-3 hover:bg-gray-50 transition-colors duration-300 text-sm">
                CANCEL
            </button>
            <button type="button" onclick="document.getElementById('logout-form').submit();" class="w-1/2 bg-red-600 text-white font-bold tracking-widest uppercase py-3 hover:bg-red-700 transition-colors duration-300 text-sm">
                LOG OUT
            </button>
        </div>
    </div>
</div>

<script>
    function openLogoutModal() {
        const modal = document.getElementById('logout-modal');
        const content = document.getElementById('logout-modal-content');
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
        }, 10);
    }

    function closeLogoutModal() {
        const modal = document.getElementById('logout-modal');
        const content = document.getElementById('logout-modal-content');
        
        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>