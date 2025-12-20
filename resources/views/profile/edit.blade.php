<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <h2 class="text-2xl font-semibold text-slate-50">
                {{ __('Pengaturan Akun') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Profile Information -->
            <div class="bg-slate-900 rounded-2xl shadow-lg border border-slate-800 overflow-hidden">
                <div class="p-6">
                    @php
                        $photoUrl = Auth::user()->photo_url;
                        $initial = strtoupper(substr(Auth::user()->name, 0, 1));
                    @endphp
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="w-12 h-12 rounded-full overflow-hidden bg-slate-800 flex items-center justify-center text-slate-50 text-xl font-semibold">
                            @if ($photoUrl)
                                <img src="{{ $photoUrl }}" alt="Foto Profil" class="w-full h-full object-cover">
                            @else
                                {{ $initial }}
                            @endif
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-50">{{ Auth::user()->name }}</h3>
                            <p class="text-sm text-slate-400">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-6">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <!-- Update Password -->
            <div class="bg-slate-900 rounded-2xl shadow-lg border border-slate-800 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-slate-50 mb-6">{{ __('Ubah Password') }}</h3>
                    <div class="space-y-6">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <!-- Delete Account -->
            <div class="bg-slate-900 rounded-2xl shadow-lg border border-slate-800 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-slate-50">{{ __('Hapus Akun') }}</h3>
                    <p class="mt-1 text-sm text-slate-400">
                        {{ __('Setelah akun Anda dihapus, semua data akan dihapus secara permanen.') }}
                    </p>
                    <div class="mt-6">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
