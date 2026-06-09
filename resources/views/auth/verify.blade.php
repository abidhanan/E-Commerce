<x-layouts.app>
    <div class="pt-32 pb-24 flex justify-center items-center min-h-[70vh] bg-white">
        <div class="w-full max-w-lg px-6 text-center">
            
            <h2 class="text-3xl font-normal tracking-wide mb-6 uppercase">Verify Your Email</h2>
            <div class="w-16 h-1 bg-black mx-auto mb-10"></div>

            <p class="text-sm text-gray-600 mb-8 leading-relaxed">
                Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? 
            </p>

            <p class="text-sm text-gray-600 mb-10 leading-relaxed">
                If you didn't receive the email, we will gladly send you another.
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-8 font-medium text-sm text-green-600 bg-green-50 p-4 border border-green-200">
                    A new verification link has been sent to the email address you provided during registration.
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full bg-black text-white font-bold tracking-widest uppercase py-4 hover:bg-gray-800 transition duration-300">
                    Resend Verification Email
                </button>
            </form>

            <div class="mt-8">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs font-semibold text-gray-500 hover:text-black uppercase tracking-widest underline">
                        Log Out
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-layouts.app>