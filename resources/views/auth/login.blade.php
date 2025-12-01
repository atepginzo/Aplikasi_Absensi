<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-6 text-center text-sm" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email -->
        <div class="space-y-2">
            <x-input-label for="email" :value="__('Email')" class="text-slate-300" />
            <x-text-input 
                id="email" 
                class="block w-full bg-slate-900 border-slate-700 text-slate-50 placeholder-slate-500 focus:ring-2 focus:ring-sky-600 focus:border-sky-600" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autofocus 
                autocomplete="email" 
                placeholder="email@example.com"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-500" />
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Password')" class="text-slate-300" />
                @if (Route::has('password.request'))
                    <a class="text-sm text-sky-400 hover:text-sky-300 transition-colors" href="{{ route('password.request') }}">
                        {{ __('Lupa password?') }}
                    </a>
                @endif
            </div>
            <x-text-input 
                id="password" 
                class="block w-full bg-slate-900 border-slate-700 text-slate-50 placeholder-slate-500 focus:ring-2 focus:ring-sky-600 focus:border-sky-600"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="••••••••"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-sm text-red-500" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input 
                id="remember_me" 
                type="checkbox" 
                class="rounded border-slate-700 bg-slate-800 text-sky-600 shadow-sm focus:ring-sky-600" 
                name="remember"
            >
            <label for="remember_me" class="ms-2 text-sm text-slate-400">
                {{ __('Ingat saya') }}
            </label>
        </div>

        <!-- Submit Button -->
        <div class="pt-4">
            <button type="submit" class="w-full bg-gradient-to-r from-sky-600 to-sky-500 text-white font-semibold rounded-full px-4 py-2.5 shadow-sm hover:from-sky-500 hover:to-sky-400 transition-colors duration-200">
                {{ __('Masuk') }}
            </button>
        </div>

        <!-- Register Link -->
        @if (Route::has('register'))
            <div class="text-center text-sm text-slate-400 mt-6">
                {{ __('Belum punya akun?') }}
                <a href="{{ route('register') }}" class="text-sky-400 hover:text-sky-300 font-medium">
                    {{ __('Daftar sekarang') }}
                </a>
            </div>
        @endif
    </form>
</x-guest-layout>
