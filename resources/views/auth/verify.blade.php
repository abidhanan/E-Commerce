<x-layouts.app>
    <div class="pt-32 pb-24 flex justify-center items-center min-h-[70vh] bg-white">
        <div class="w-full max-w-xl px-6 text-center">

            <h2 class="text-3xl font-normal tracking-wide mb-2 uppercase">Verify Your Email</h2>
            <div class="w-16 h-1 bg-black mx-auto mb-8"></div>

            <p class="text-sm text-gray-600 mb-8 leading-relaxed">
                Welcome to Clothique! Before getting started, you must verify your email address. Please click the link in the email we just sent you. If you didn't receive the email, click the button below to request another one.
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 mb-6 text-sm font-medium">
                    A new verification link has been sent to your email address.
                </div>
            @endif

            <div class="flex flex-col items-center gap-4">
                
                <form method="POST" action="{{ url('/email/verification-notification') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full bg-black text-white font-bold tracking-widest uppercase py-4 hover:bg-gray-800 transition duration-300">
                        Resend Email
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs font-bold text-gray-500 hover:text-black uppercase tracking-widest underline transition mt-4">
                        Log Out
                    </button>
                </form>

            </div>

        </div>
    </div>
</x-layouts.app>