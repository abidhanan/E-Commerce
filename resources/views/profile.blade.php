<x-layouts.app>
    <div class="max-w-screen-xl mx-auto px-6 py-16 flex flex-col md:flex-row gap-12 min-h-screen">
        
        <aside class="w-full md:w-64 flex-shrink-0">
            <div class="mb-10">
                <h3 class="text-lg font-bold uppercase tracking-widest mb-4 border-b-4 border-black inline-block pb-1">Membership</h3>
                <ul class="space-y-4 mt-4 text-sm text-gray-700 font-medium">
                    <li><a href="#" class="hover:text-[#c4a052] transition">Purchase History</a></li>
                    <li><a href="#" class="hover:text-[#c4a052] transition">Wishlist</a></li>
                    <li><a href="#" class="hover:text-[#c4a052] transition">Posted Reviews</a></li>
                    <li><a href="#" class="hover:text-[#c4a052] transition">Coupon</a></li>
                    </ul>
            </div>

            <div>
                <h3 class="text-lg font-bold uppercase tracking-widest mb-4 border-b-4 border-black inline-block pb-1">Profile Settings</h3>
                <ul class="space-y-4 mt-4 text-sm text-gray-700 font-medium">
                    <li><a href="#" class="text-black font-bold">Profile</a></li>
                    <li><a href="#" class="hover:text-[#c4a052] transition">Change Password</a></li>
                    <li><a href="#" class="hover:text-[#c4a052] transition">Manage Payments</a></li>
                    <li><a href="#" class="hover:text-red-600 transition">Delete Account</a></li>
                    <li class="pt-4">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="hover:text-red-600 transition text-left w-full">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </aside>

        <main class="flex-1 border-l border-gray-200 pl-0 md:pl-12">
            
            <div class="bg-black text-white inline-block px-12 py-4 mb-8">
                <h2 class="text-2xl font-light tracking-widest uppercase">Profile</h2>
            </div>

            <div class="max-w-2xl">
                <div class="mb-12">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-normal tracking-wide uppercase">User Email</h3>
                        <button class="border border-black px-6 py-2 text-sm font-semibold rounded-full hover:bg-black hover:text-white transition">
                            Change Email
                        </button>
                    </div>
                    
                    <div class="space-y-1">
                        <p class="text-base font-medium text-gray-900">Email Address</p>
                        <p class="text-sm text-gray-600">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-normal tracking-wide uppercase">User Profile</h3>
                        <button class="border border-black px-6 py-2 text-sm font-semibold rounded-full hover:bg-black hover:text-white transition">
                            Change Profile
                        </button>
                    </div>

                    <div class="space-y-6">
                        <div class="space-y-1">
                            <p class="text-base font-medium text-gray-900">Full Name</p>
                            <p class="text-sm text-gray-600">{{ auth()->user()->name }}</p>
                        </div>

                        <div class="space-y-1">
                            <p class="text-base font-medium text-gray-900">Telephone Number</p>
                            <p class="text-sm text-gray-600">{{ auth()->user()->phone ?? 'Belum diatur' }}</p>
                        </div>

                        <div class="space-y-1">
                            <p class="text-base font-medium text-gray-900">Birth Date</p>
                            <p class="text-sm text-gray-600">
                                {{ auth()->user()->dob ? \Carbon\Carbon::parse(auth()->user()->dob)->format('d/m/Y') : 'Belum diatur' }}
                            </p>
                        </div>

                        <div class="space-y-1">
                            <p class="text-base font-medium text-gray-900">Gender</p>
                            <p class="text-sm text-gray-600 capitalize">{{ auth()->user()->gender ?? 'Belum diatur' }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</x-layouts.app>