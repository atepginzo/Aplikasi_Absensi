<section class="space-y-6">
    <header>
        <h2 class="text-lg font-semibold text-slate-50">
            {{ __('Informasi Profil') }}
        </h2>
        <p class="mt-1 text-sm text-slate-400">
            {{ __('Perbarui informasi profil dan alamat email akun Anda.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div class="space-y-2">
            <x-input-label for="name" :value="__('Nama')" class="text-slate-300" />
            <x-text-input 
                id="name" 
                name="name" 
                type="text" 
                class="block w-full bg-slate-900 border-slate-700 text-slate-50 placeholder-slate-500 focus:ring-2 focus:ring-sky-600 focus:border-sky-600" 
                :value="old('name', $user->name)" 
                required 
                autofocus 
                autocomplete="name"
            />
            <x-input-error :messages="$errors->get('name')" class="mt-1 text-sm text-red-500" />
        </div>

        <div class="space-y-2">
            <x-input-label for="email" :value="__('Email')" class="text-slate-300" />
            <x-text-input 
                id="email" 
                name="email" 
                type="email" 
                class="block w-full bg-slate-900 border-slate-700 text-slate-50 placeholder-slate-500 focus:ring-2 focus:ring-sky-600 focus:border-sky-600" 
                :value="old('email', $user->email)" 
                required 
                autocomplete="username"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-500" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 p-3 bg-slate-800 rounded-lg">
                    <p class="text-sm text-slate-300">
                        {{ __('Email Anda belum terverifikasi.') }}
                        <button 
                            form="send-verification" 
                            class="text-sky-400 hover:text-sky-300 text-sm font-medium"
                        >
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-medium text-green-500">
                            {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="bg-gradient-to-r from-sky-600 to-sky-500 text-white font-semibold rounded-full px-4 py-2 shadow-sm hover:from-sky-500 hover:to-sky-400 transition-colors duration-200">
                {{ __('Simpan Perubahan') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-500"
                >
                    {{ __('Tersimpan.') }}
                </p>
            @endif
        </div>
    </form>
</section>
