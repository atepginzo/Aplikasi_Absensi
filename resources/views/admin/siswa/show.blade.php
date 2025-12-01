<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-100 leading-tight">
                    {{ __('Detail Siswa & QR Code') }}
                </h2>
                <p class="mt-1 text-sm text-slate-400">Informasi lengkap dan kartu QR Code siswa</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 overflow-hidden shadow-xl">
                <div class="p-8">
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        
                        {{-- Bagian KIRI: Kartu QR Code --}}
                        <div class="lg:col-span-1">
                            <div class="bg-gradient-to-br from-blue-500/10 to-purple-500/10 rounded-2xl p-8 border border-blue-500/30 shadow-lg backdrop-blur-sm">
                                <div class="text-center">
                                    <h3 class="text-xl font-bold text-white mb-6">Kartu Absensi</h3>
                                    
                                    {{-- QR Code Container --}}
                                    <div class="bg-white p-6 rounded-2xl shadow-xl mb-4 inline-block">
                                        {!! QrCode::size(220)->generate($siswa->qrcode_token) !!}
                                    </div>

                                    {{-- Token Display --}}
                                    <div class="mt-6 p-4 bg-slate-900/50 rounded-xl border border-slate-700 backdrop-blur-sm">
                                        <p class="text-xs font-semibold text-slate-400 mb-2">QR Code Token</p>
                                        <p class="text-sm font-mono text-slate-200 break-all">
                                            {{ $siswa->qrcode_token }}
                                        </p>
                                    </div>

                                    {{-- Print Button --}}
                                    <button onclick="window.print()" class="mt-6 inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-900 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                        </svg>
                                        Cetak Kartu
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Bagian KANAN: Detail Biodata --}}
                        <div class="lg:col-span-2">
                            <div class="space-y-6">
                                {{-- Header --}}
                                <div class="pb-6 border-b border-slate-800">
                                    <h3 class="text-2xl font-bold text-white mb-2">Informasi Siswa</h3>
                                    <p class="text-sm text-slate-400">Data lengkap siswa terdaftar</p>
                                </div>
                                
                                {{-- Informasi Pribadi --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="p-5 bg-slate-800/50 rounded-xl border border-slate-700 backdrop-blur-sm">
                                        <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">Nama Lengkap</span>
                                        <span class="text-lg font-bold text-white">{{ $siswa->nama_siswa }}</span>
                                    </div>

                                    <div class="p-5 bg-slate-800/50 rounded-xl border border-slate-700 backdrop-blur-sm">
                                        <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">NIS</span>
                                        <span class="text-lg font-bold text-white">{{ $siswa->nis }}</span>
                                    </div>

                                    <div class="p-5 bg-slate-800/50 rounded-xl border border-slate-700 backdrop-blur-sm">
                                        <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">Jenis Kelamin</span>
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-semibold {{ $siswa->jenis_kelamin == 'L' ? 'bg-blue-500/20 text-blue-300 border border-blue-500/30' : 'bg-pink-500/20 text-pink-300 border border-pink-500/30' }}">
                                            {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Informasi Kelas --}}
                                <div class="mt-6">
                                    <h4 class="text-lg font-bold text-white mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        Informasi Kelas
                                    </h4>
                                    <div class="bg-gradient-to-br from-blue-500/10 to-purple-500/10 rounded-xl p-6 border border-blue-500/30 backdrop-blur-sm">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">Kelas</span>
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold bg-blue-500/20 text-blue-300 border border-blue-500/30">
                                                    {{ $siswa->kelas->nama_kelas ?? 'N/A' }}
                                                </span>
                                            </div>
                                            <div>
                                                <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">Tahun Ajaran</span>
                                                <span class="text-sm font-semibold text-white">{{ $siswa->kelas->tahunAjaran->tahun_ajaran ?? '-' }}</span>
                                            </div>
                                            <div class="md:col-span-2">
                                                <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">Wali Kelas</span>
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                    <span class="text-sm font-semibold text-white">{{ $siswa->kelas->waliKelas->nama_lengkap ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="flex items-center justify-between pt-6 border-t border-slate-800">
                                    <a href="{{ route('admin.siswa.index') }}" class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-slate-200 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                        </svg>
                                        Kembali ke Daftar Siswa
                                    </a>
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('admin.siswa.edit', $siswa->id) }}" class="inline-flex items-center px-4 py-2 bg-slate-800 border-2 border-slate-700 rounded-xl font-semibold text-sm text-slate-200 hover:border-blue-500 hover:text-blue-300 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition-all duration-200">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit Data
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
