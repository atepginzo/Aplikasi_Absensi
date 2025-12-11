<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-100 leading-tight">
                    {{ __('Tambah Siswa Baru') }}
                </h2>
                <p class="mt-1 text-sm text-slate-400">Tambahkan data siswa baru ke sistem</p>
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

                    <form method="POST" action="{{ route('admin.siswa.store') }}" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Pilih Kelas -->
                            <div class="col-span-1 md:col-span-2">
                                <label for="kelas_id" class="block text-sm font-semibold text-slate-200 mb-2">
                                    Kelas <span class="text-red-400">*</span>
                                </label>
                                <select id="kelas_id" name="kelas_id" class="block w-full rounded-xl bg-slate-800/50 border border-slate-700 text-slate-200 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition-all duration-200 text-sm px-4 py-2.5" required>
                                    <option value="" class="bg-slate-800">-- Pilih Kelas --</option>
                                    @foreach ($semuaKelas as $kelas)
                                        <option value="{{ $kelas->id }}" @selected(old('kelas_id') == $kelas->id) class="bg-slate-800">
                                            {{ $kelas->nama_kelas }} - ({{ $kelas->tahunAjaran->tahun_ajaran ?? '-' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- NIS -->
                            <div>
                                <label for="nis" class="block text-sm font-semibold text-slate-200 mb-2">
                                    NIS (Nomor Induk Siswa) <span class="text-red-400">*</span>
                                </label>
                                <input id="nis" 
                                       class="block w-full rounded-xl bg-slate-800/50 border border-slate-700 text-slate-200 placeholder-slate-500 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition-all duration-200 text-sm px-4 py-2.5" 
                                       type="text" 
                                       name="nis" 
                                       value="{{ old('nis') }}" 
                                       placeholder="Masukkan NIS"
                                       required />
                            </div>

                            <!-- Nama Siswa -->
                            <div>
                                <label for="nama_siswa" class="block text-sm font-semibold text-slate-200 mb-2">
                                    Nama Siswa <span class="text-red-400">*</span>
                                </label>
                                <input id="nama_siswa" 
                                       class="block w-full rounded-xl bg-slate-800/50 border border-slate-700 text-slate-200 placeholder-slate-500 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition-all duration-200 text-sm px-4 py-2.5" 
                                       type="text" 
                                       name="nama_siswa" 
                                       value="{{ old('nama_siswa') }}" 
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
                        </div>

                        <!-- Akun Orang Tua -->
                        <div class="mt-8 rounded-2xl border border-slate-800 bg-slate-950/60 p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-100">Akun Orang Tua</h3>
                                    <p class="text-xs text-slate-400 mt-1">Opsional. Isi jika ingin langsung membuat akun login untuk orang tua siswa ini.</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="parent_email" class="block text-sm font-semibold text-slate-200 mb-2">
                                        Email Orang Tua
                                    </label>
                                    <input id="parent_email"
                                           class="block w-full rounded-xl bg-slate-800/50 border border-slate-700 text-slate-200 placeholder-slate-500 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition-all duration-200 text-sm px-4 py-2.5"
                                           type="email"
                                           name="parent_email"
                                           value="{{ old('parent_email') }}"
                                           placeholder="contoh: ortu.siswa@example.com" />
                                </div>

                                <div>
                                    <label for="parent_password" class="block text-sm font-semibold text-slate-200 mb-2">
                                        Password Akun Orang Tua
                                    </label>
                                    <input id="parent_password"
                                           class="block w-full rounded-xl bg-slate-800/50 border border-slate-700 text-slate-200 placeholder-slate-500 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition-all duration-200 text-sm px-4 py-2.5"
                                           type="password"
                                           name="parent_password"
                                           placeholder="Minimal 8 karakter" />
                                </div>
                            </div>
                        </div>

                        <!-- Info QR Code (Otomatis) -->
                        <div class="mt-6 p-4 bg-gradient-to-r from-blue-500/10 to-purple-500/10 border border-blue-500/30 rounded-xl backdrop-blur-sm">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-blue-300 mb-1">Info QR Code</p>
                                    <p class="text-sm text-slate-300">
                                        QR Code Token akan digenerate secara otomatis oleh sistem saat data disimpan.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol -->
                        <div class="flex items-center justify-end pt-6 border-t border-slate-800 space-x-3">
                            <button type="button" 
                                    onclick="window.location.href='{{ route('admin.siswa.index') }}'"
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
                                Simpan Siswa
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
