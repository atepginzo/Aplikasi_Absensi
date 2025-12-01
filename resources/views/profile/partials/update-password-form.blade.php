<section class="space-y-6">
    <header>
        <h2 class="text-lg font-semibold text-slate-50">
            {{ __('Ubah Password') }}
        </h2>
        <p class="mt-1 text-sm text-slate-400">
            {{ __('Pastikan akun Anda menggunakan kata sandi yang kuat dan unik untuk keamanan.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="space-y-2">
            <x-input-label for="update_password_current_password" :value="__('Password Saat Ini')" class="text-slate-300" />
            <x-text-input 
                id="update_password_current_password" 
                name="current_password" 
                type="password" 
                class="block w-full bg-slate-900 border-slate-700 text-slate-50 placeholder-slate-500 focus:ring-2 focus:ring-sky-600 focus:border-sky-600" 
                autocomplete="current-password" 
                placeholder="••••••••"
            />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1 text-sm text-red-500" />
        </div>

        <div class="space-y-2">
            <x-input-label for="update_password_password" :value="__('Password Baru')" class="text-slate-300" />
            <x-text-input 
                id="update_password_password" 
                name="password" 
                type="password" 
                class="block w-full bg-slate-900 border-slate-700 text-slate-50 placeholder-slate-500 focus:ring-2 focus:ring-sky-600 focus:border-sky-600" 
                autocomplete="new-password"
                placeholder="••••••••"
            />
            <p class="mt-1 text-xs text-slate-500">
                {{ __('Minimal 8 karakter') }}
            </p>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1 text-sm text-red-500" />
        </div>

        <div class="space-y-2">
            <x-input-label for="update_password_password_confirmation" :value="__('Konfirmasi Password')" class="text-slate-300" />
            <x-text-input 
                id="update_password_password_confirmation" 
                name="password_confirmation" 
                type="password" 
                class="block w-full bg-slate-900 border-slate-700 text-slate-50 placeholder-slate-500 focus:ring-2 focus:ring-sky-600 focus:border-sky-600" 
                autocomplete="new-password"
                placeholder="••••••••"
            />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1 text-sm text-red-500" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="bg-gradient-to-r from-sky-600 to-sky-500 text-white font-semibold rounded-full px-4 py-2 shadow-sm hover:from-sky-500 hover:to-sky-400 transition-colors duration-200">
                {{ __('Simpan Password Baru') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-500"
                >
                    {{ __('Password berhasil diperbarui.') }}
                </p>
            @endif
        </div>
    </form>
</section>
