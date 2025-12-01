<x-guest-layout>
    <div class="text-center">
        <!-- Icon -->
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-sky-500/10 mb-6">
            <svg class="h-8 w-8 text-sky-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
            </svg>
        </div>

        <!-- Title & Description -->
        <h2 class="text-2xl font-semibold text-slate-50 mb-2">{{ __('Keluar dari Akun') }}</h2>
        <p class="text-slate-400 mb-8">
            {{ __('Apakah Anda yakin ingin keluar dari akun Anda?') }}
        </p>

        <!-- Buttons -->
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a 
                href="{{ route('dashboard') }}" 
                class="px-6 py-2.5 text-sm font-medium text-slate-300 bg-slate-800 border border-slate-700 rounded-full hover:bg-slate-700 transition-colors duration-200"
            >
                {{ __('Kembali ke Dashboard') }}
            </a>
            
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button 
                    type="submit" 
                    class="w-full px-6 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-sky-600 to-sky-500 rounded-full hover:from-sky-500 hover:to-sky-400 transition-colors duration-200"
                >
                    {{ __('Ya, Keluar') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
