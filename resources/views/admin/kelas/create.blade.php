<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-50 leading-tight">
                    {{ __('Tambah Kelas Baru') }}
                </h2>
                <p class="mt-1 text-sm text-slate-400">Tambahkan kelas baru ke sistem</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-900 backdrop-blur-sm rounded-2xl border border-slate-800 overflow-hidden shadow-xl">
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

                    <form method="POST" action="{{ route('admin.kelas.store') }}" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Kelas -->
                            <div>
                                <label for="nama_kelas" class="block text-sm font-semibold text-slate-50 mb-2">
                                    Nama Kelas <span class="text-red-400">*</span>
                                </label>
                                <input id="nama_kelas" 
                                       class="block w-full rounded-xl bg-slate-800/50 border border-slate-700 text-slate-50 placeholder-slate-500 shadow-sm focus:border-sky-500 focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 focus:ring-offset-neutral-950 transition-all duration-200 text-sm px-4 py-2.5" 
                                       type="text" 
                                       name="nama_kelas" 
                                       value="{{ old('nama_kelas') }}" 
                                       placeholder="Contoh: 7A, 8B, 9C"
                                       required 
                                       autofocus />
                            </div>

                            <!-- Tahun Ajaran -->
                            <div>
                                <label for="tahun_ajaran_id" class="block text-sm font-semibold text-slate-50 mb-2">
                                    Tahun Ajaran <span class="text-red-400">*</span>
                                </label>
                                <select id="tahun_ajaran_id" name="tahun_ajaran_id" class="block w-full rounded-xl bg-slate-800/50 border border-slate-700 text-slate-50 shadow-sm focus:border-sky-500 focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 focus:ring-offset-neutral-950 transition-all duration-200 text-sm px-4 py-2.5" required>
                                    <option value="" class="bg-slate-800">-- Pilih Tahun Ajaran --</option>
                                    @foreach ($semuaTahunAjaran as $tahun)
                                        <option value="{{ $tahun->id }}" @selected(old('tahun_ajaran_id') == $tahun->id) class="bg-slate-800">
                                            {{ $tahun->tahun_ajaran }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Wali Kelas -->
                            <div class="md:col-span-2">
                                <label for="wali_kelas_id" class="block text-sm font-semibold text-slate-50 mb-2">
                                    Wali Kelas <span class="text-red-400">*</span>
                                </label>
                                <select id="wali_kelas_id" name="wali_kelas_id" class="block w-full rounded-xl bg-slate-800/50 border border-slate-700 text-slate-50 shadow-sm focus:border-sky-500 focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 focus:ring-offset-neutral-950 transition-all duration-200 text-sm px-4 py-2.5" required>
                                    <option value="" class="bg-slate-800">-- Pilih Wali Kelas --</option>
                                    @foreach ($semuaWaliKelas as $wali)
                                        <option value="{{ $wali->id }}" @selected(old('wali_kelas_id') == $wali->id) class="bg-slate-800">
                                            {{ $wali->nama_lengkap }} (NIP: {{ $wali->nip }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Tombol -->
                        <div class="flex items-center justify-end pt-6 border-t border-slate-800 space-x-3">
                            <button type="button" 
                                    onclick="window.location.href='{{ route('admin.kelas.index') }}'"
                                    class="inline-flex items-center px-5 py-2.5 bg-slate-800 border-2 border-slate-700 rounded-xl font-semibold text-sm text-slate-50 hover:border-slate-600 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 focus:ring-offset-neutral-950 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Batal
                            </button>

                            <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-sky-600 to-sky-500 border border-transparent rounded-xl font-semibold text-sm text-white hover:from-sky-500 hover:to-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 focus:ring-offset-neutral-950 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Kelas
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
