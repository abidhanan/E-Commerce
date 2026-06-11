<x-layouts.app>
    <div class="max-w-screen-xl mx-auto px-6 py-16 flex flex-col md:flex-row gap-12 min-h-screen">
        
        @include('Users.account.sidebar')

        <main class="flex-1 border-l border-gray-200 pl-0 md:pl-12">
            <div class="bg-black text-white inline-block px-12 py-4 mb-8">
                <h2 class="text-2xl font-light tracking-widest uppercase">Profile</h2>
            </div>

            <div class="max-w-2xl">
                <div class="mb-12">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-normal tracking-wide uppercase">User Email</h3>
                    </div>
                    <div class="space-y-1">
                        <p class="text-base font-medium text-gray-900">Email Address</p>
                        <p class="text-sm text-gray-600">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-normal tracking-wide uppercase">User Profile</h3>
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
                                {{ auth()->user()->date_of_birth ? \Carbon\Carbon::parse(auth()->user()->date_of_birth)->format('d/m/Y') : 'Belum diatur' }}
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