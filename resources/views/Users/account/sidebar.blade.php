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
            <li><a href="#" class="hover:text-[#c4a052] }} transition">Manage Payments</a></li>
            <li class="pt-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="hover:text-red-600 transition text-left w-full">Logout</button>
                </form>
            </li>
        </ul>
    </div>
</aside>