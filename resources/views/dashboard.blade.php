<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-3xl text-slate-50 leading-tight">
                    {{ __('Dashboard Admin') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Welcome Banner --}}
            <div class="mb-8 rounded-2xl bg-gradient-to-r from-sky-600/20 via-sky-500/20 to-sky-600/20 border border-sky-500/30 backdrop-blur-sm overflow-hidden relative">
                <div class="absolute inset-0 bg-gradient-to-r from-sky-500/10 to-sky-400/10"></div>
                <div class="relative p-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-slate-50 mb-2">
                                Welcome back, <span class="bg-gradient-to-r from-sky-300 to-sky-400 bg-clip-text text-transparent">{{ Auth::user()->name }}</span>! 
                            </h1>
                            <p class="text-slate-400 text-lg">Kelola sistem absensi siswa dengan mudah dan efisien</p>
                        </div>
                        <div class="hidden lg:block">
                            <div class="w-32 h-32 rounded-full bg-gradient-to-br from-sky-500/30 to-sky-400/30 blur-3xl"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Grid Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                
                <!-- Card 1: Jumlah Siswa -->
                <div class="group relative bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden hover:border-sky-500/50 transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-sky-500/10">
                    <div class="absolute inset-0 bg-gradient-to-br from-sky-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-400 mb-2">Total Siswa</p>
                                <p class="text-4xl font-bold text-slate-50 mb-1">{{ $jumlahSiswa ?? 0 }}</p>
                                <p class="text-xs text-slate-500 mt-2">Semua siswa terdaftar</p>
                            </div>
                            <div class="p-4 rounded-2xl bg-sky-500/20 backdrop-blur-sm border border-sky-500/30 group-hover:bg-sky-500/30 group-hover:scale-110 transition-all duration-300">
                                <svg class="w-10 h-10 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="h-1 bg-gradient-to-r from-sky-500 to-sky-400"></div>
                </div>

                <!-- Card 2: Jumlah Kelas -->
                <div class="group relative bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden hover:border-emerald-500/50 transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-emerald-500/10">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-400 mb-2">Total Kelas</p>
                                <p class="text-4xl font-bold text-slate-50 mb-1">{{ $jumlahKelas ?? 0 }}</p>
                                <p class="text-xs text-slate-500 mt-2">Kelas aktif</p>
                            </div>
                            <div class="p-4 rounded-2xl bg-emerald-500/20 backdrop-blur-sm border border-emerald-500/30 group-hover:bg-emerald-500/30 group-hover:scale-110 transition-all duration-300">
                                <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="h-1 bg-gradient-to-r from-emerald-500 to-emerald-600"></div>
                </div>

                <!-- Card 3: Jumlah Wali Kelas -->
                <div class="group relative bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden hover:border-amber-500/50 transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-amber-500/10">
                    <div class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-400 mb-2">Wali Kelas</p>
                                <p class="text-4xl font-bold text-slate-50 mb-1">{{ $jumlahWaliKelas ?? 0 }}</p>
                                <p class="text-xs text-slate-500 mt-2">Guru wali kelas</p>
                            </div>
                            <div class="p-4 rounded-2xl bg-amber-500/20 backdrop-blur-sm border border-amber-500/30 group-hover:bg-amber-500/30 group-hover:scale-110 transition-all duration-300">
                                <svg class="w-10 h-10 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="h-1 bg-gradient-to-r from-amber-500 to-amber-600"></div>
                </div>

                <!-- Card 4: Hadir Hari Ini -->
                <div class="group relative bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden hover:border-sky-500/50 transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-sky-500/10">
                    <div class="absolute inset-0 bg-gradient-to-br from-sky-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-400 mb-2">Hadir Hari Ini</p>
                                <p class="text-4xl font-bold text-slate-50 mb-1">{{ $hadirHariIni ?? 0 }}</p>
                                <p class="text-xs text-slate-500 mt-2">Kehadiran hari ini</p>
                            </div>
                            <div class="p-4 rounded-2xl bg-sky-500/20 backdrop-blur-sm border border-sky-500/30 group-hover:bg-sky-500/30 group-hover:scale-110 transition-all duration-300">
                                <svg class="w-10 h-10 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="h-1 bg-gradient-to-r from-sky-500 to-sky-400"></div>
                </div>

            </div>

            {{-- Tombol Cepat (Quick Actions) --}}
            <div class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden shadow-xl">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-slate-50">Menu Cepat</h3>
                            <p class="text-sm text-slate-400 mt-1">Akses cepat ke fitur utama</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-4">
                        {{-- Link ke Scanner --}}
                        <a href="{{ route('admin.absensi.scan') }}" class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-sky-600 to-sky-500 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-wide hover:from-sky-500 hover:to-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 focus:ring-offset-neutral-950 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                            </svg>
                            Buka Scanner Absensi
                        </a>
                        
                        {{-- Link ke Tambah Siswa --}}
                        <a href="{{ route('admin.siswa.create') }}" class="group inline-flex items-center px-6 py-3 bg-slate-900 border-2 border-slate-700 rounded-xl font-semibold text-sm text-slate-50 uppercase tracking-wide hover:border-sky-500 hover:text-sky-300 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 focus:ring-offset-neutral-950 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Tambah Siswa Baru
                        </a>

                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
