<x-guest-layout>
    <div class="mb-6 text-sm text-slate-400">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="space-y-2">
            <x-input-label for="email" :value="__('Email')" class="text-slate-300" />
            <x-text-input id="email" class="block w-full bg-slate-900 border-slate-700 text-slate-50 placeholder-slate-500 focus:ring-2 focus:ring-sky-600 focus:border-sky-600" type="email" name="email" :value="old('email')" required autofocus placeholder="email@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-500" />
        </div>

        <div class="mt-4 space-y-3">
            <button type="submit" class="w-full bg-gradient-to-r from-sky-600 to-sky-500 text-white font-semibold rounded-full px-4 py-2.5 shadow-sm hover:from-sky-500 hover:to-sky-400 transition-colors duration-200">
                {{ __('Email Password Reset Link') }}
            </button>

            <a href="{{ route('login') }}" class="inline-flex w-full justify-center rounded-full border border-slate-700 bg-slate-900 px-4 py-2 text-sm font-medium text-slate-200 hover:bg-slate-800 transition-colors duration-200">
                {{ __('Kembali ke Login') }}
            </a>
        </div>
    </form>
</x-guest-layout>
