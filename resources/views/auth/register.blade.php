<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <h2 class="text-2xl font-semibold text-slate-50 text-center mb-2">{{ __('Buat Akun Baru') }}</h2>
        <p class="text-center text-slate-400 text-sm mb-6">
            {{ __('Isi data diri Anda untuk membuat akun baru') }}
        </p>

        <!-- Name -->
        <div class="space-y-2">
            <x-input-label for="name" :value="__('Nama Lengkap')" class="text-slate-300" />
            <x-text-input 
                id="name" 
                class="block w-full bg-slate-900 border-slate-700 text-slate-50 placeholder-slate-500 focus:ring-2 focus:ring-sky-600 focus:border-sky-600" 
                type="text" 
                name="name" 
                :value="old('name')" 
                required 
                autofocus 
                autocomplete="name"
                placeholder="Nama lengkap"
            />
            <x-input-error :messages="$errors->get('name')" class="mt-1 text-sm text-red-500" />
        </div>

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
                autocomplete="email"
                placeholder="email@example.com"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-500" />
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <x-input-label for="password" :value="__('Password')" class="text-slate-300" />
            <x-text-input 
                id="password" 
                class="block w-full bg-slate-900 border-slate-700 text-slate-50 placeholder-slate-500 focus:ring-2 focus:ring-sky-600 focus:border-sky-600"
                type="password"
                name="password"
                required 
                autocomplete="new-password"
                placeholder="••••••••"
            />
            <p class="mt-1 text-xs text-slate-500">
                {{ __('Minimal 8 karakter') }}
            </p>
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-sm text-red-500" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-2">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="text-slate-300" />
            <x-text-input 
                id="password_confirmation" 
                class="block w-full bg-slate-900 border-slate-700 text-slate-50 placeholder-slate-500 focus:ring-2 focus:ring-sky-600 focus:border-sky-600"
                type="password"
                name="password_confirmation" 
                required 
                autocomplete="new-password"
                placeholder="••••••••"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-sm text-red-500" />
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" class="w-full bg-gradient-to-r from-sky-600 to-sky-500 text-white font-semibold rounded-full px-4 py-2.5 shadow-sm hover:from-sky-500 hover:to-sky-400 transition-colors duration-200">
                {{ __('Daftar Sekarang') }}
            </button>
        </div>

        <!-- Login Link -->
        <div class="text-center text-sm text-slate-400 mt-6">
            {{ __('Sudah punya akun?') }}
            <a href="{{ route('login') }}" class="text-sky-400 hover:text-sky-300 font-medium">
                {{ __('Masuk di sini') }}
            </a>
        </div>
    </form>
</x-guest-layout>
