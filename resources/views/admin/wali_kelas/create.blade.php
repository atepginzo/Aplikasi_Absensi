<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-100 leading-tight">
                    {{ __('Tambah Wali Kelas Baru') }}
                </h2>
                <p class="mt-1 text-sm text-slate-400">Tambahkan data wali kelas baru ke sistem</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 overflow-hidden shadow-xl">
                <div class="p-8">
                    
                    {{-- Error Validation --}}
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-500/20 border border-red-500/30 text-red-300 rounded-xl backdrop-blur-sm">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-semibold">Terdapat kesalahan pada form:</span>
                            </div>
                            <ul class="list-disc list-inside text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.wali-kelas.store') }}" class="space-y-6">
                        @csrf
                        
                        {{-- Data Diri Wali Kelas --}}
                        <div>
                            <h3 class="text-lg font-bold text-white mb-4 pb-3 border-b border-slate-800">Data Pribadi</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- NIP -->
                                <div>
                                    <label for="nip" class="block text-sm font-semibold text-slate-200 mb-2">
                                        NIP <span class="text-red-400">*</span>
                                    </label>
                                    <input id="nip" 
                                           class="block w-full rounded-xl bg-slate-800/50 border border-slate-700 text-slate-200 placeholder-slate-500 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition-all duration-200 text-sm px-4 py-2.5" 
                                           type="text" 
                                           name="nip" 
                                           value="{{ old('nip') }}" 
                                           placeholder="Masukkan NIP"
                                           required 
                                           autofocus />
                                </div>

                                <!-- Nama Lengkap -->
                                <div>
                                    <label for="nama_lengkap" class="block text-sm font-semibold text-slate-200 mb-2">
                                        Nama Lengkap <span class="text-red-400">*</span>
                                    </label>
                                    <input id="nama_lengkap" 
                                           class="block w-full rounded-xl bg-slate-800/50 border border-slate-700 text-slate-200 placeholder-slate-500 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition-all duration-200 text-sm px-4 py-2.5" 
                                           type="text" 
                                           name="nama_lengkap" 
                                           value="{{ old('nama_lengkap') }}" 
                                           placeholder="Masukkan nama lengkap"
                                           required />
                                </div>

                                <!-- Jenis Kelamin -->
                                <div>
                                    <label for="jenis_kelamin" class="block text-sm font-semibold text-slate-200 mb-2">
                                        Jenis Kelamin <span class="text-red-400">*</span>
                                    </label>
                                    <select id="jenis_kelamin" name="jenis_kelamin" class="block w-full rounded-xl bg-slate-800/50 border border-slate-700 text-slate-200 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition-all duration-200 text-sm px-4 py-2.5" required>
                                        <option value="L" @selected(old('jenis_kelamin') == 'L') class="bg-slate-800">Laki-laki</option>
                                        <option value="P" @selected(old('jenis_kelamin') == 'P') class="bg-slate-800">Perempuan</option>
                                    </select>
                                </div>

                                <!-- Tanggal Lahir -->
                                <div>
                                    <label for="tanggal_lahir" class="block text-sm font-semibold text-slate-200 mb-2">
                                        Tanggal Lahir <span class="text-red-400">*</span>
                                    </label>
                                    <input id="tanggal_lahir" 
                                           class="block w-full rounded-xl bg-slate-800/50 border border-slate-700 text-slate-200 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition-all duration-200 text-sm px-4 py-2.5" 
                                           type="date" 
                                           name="tanggal_lahir" 
                                           value="{{ old('tanggal_lahir') }}" 
                                           required />
                                </div>
                            </div>
                        </div>

                        {{-- Data Akun Login --}}
                        <div class="pt-6 border-t border-slate-800">
                            <h3 class="text-lg font-bold text-white mb-4">Detail Akun Login</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Alamat Email -->
                                <div class="md:col-span-2">
                                    <label for="email" class="block text-sm font-semibold text-slate-200 mb-2">
                                        Alamat Email <span class="text-red-400">*</span>
                                    </label>
                                    <input id="email" 
                                           class="block w-full rounded-xl bg-slate-800/50 border border-slate-700 text-slate-200 placeholder-slate-500 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition-all duration-200 text-sm px-4 py-2.5" 
                                           type="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="contoh@email.com"
                                           required />
                                </div>

                                <!-- Password -->
                                <div>
                                    <label for="password" class="block text-sm font-semibold text-slate-200 mb-2">
                                        Password <span class="text-red-400">*</span>
                                    </label>
                                    <input id="password" 
                                           class="block w-full rounded-xl bg-slate-800/50 border border-slate-700 text-slate-200 placeholder-slate-500 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition-all duration-200 text-sm px-4 py-2.5" 
                                           type="password" 
                                           name="password" 
                                           placeholder="Minimal 8 karakter"
                                           required />
                                </div>

                                <!-- Konfirmasi Password -->
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-200 mb-2">
                                        Konfirmasi Password <span class="text-red-400">*</span>
                                    </label>
                                    <input id="password_confirmation" 
                                           class="block w-full rounded-xl bg-slate-800/50 border border-slate-700 text-slate-200 placeholder-slate-500 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition-all duration-200 text-sm px-4 py-2.5" 
                                           type="password" 
                                           name="password_confirmation" 
                                           placeholder="Ulangi password"
                                           required />
                                </div>
                            </div>
                        </div>

                        <!-- Tombol -->
                        <div class="flex items-center justify-end pt-6 border-t border-slate-800 space-x-3">
                            <button type="button" 
                                    onclick="window.location.href='{{ route('admin.wali-kelas.index') }}'"
                                    class="inline-flex items-center px-5 py-2.5 bg-slate-800 border-2 border-slate-700 rounded-xl font-semibold text-sm text-slate-200 hover:border-slate-600 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Batal
                            </button>

                            <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-900 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Wali Kelas
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
