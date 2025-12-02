<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-50 leading-tight">Laporan Kehadiran</h2>
                <p class="mt-1 text-sm text-slate-400">Pilih kelas untuk melihat rekap absensi per tanggal</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 shadow-2xl shadow-black/40 p-8">
                @if ($daftarKelas->isEmpty())
                    <div class="text-center py-16">
                        <div class="mx-auto w-16 h-16 flex items-center justify-center rounded-2xl bg-slate-800 text-slate-500 mb-6">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-100">Belum ada data kelas</h3>
                        <p class="mt-2 text-sm text-slate-400">Tambahkan data kelas terlebih dahulu untuk melihat laporan.</p>
                    </div>
                @else
                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($daftarKelas as $kelas)
                            <a href="{{ route('admin.laporan.show', $kelas->id) }}"
                               class="group relative rounded-2xl border border-slate-800 bg-slate-900/70 p-6 shadow-lg shadow-black/30 transition-all duration-200 hover:-translate-y-1 hover:border-sky-500/60 hover:shadow-black/50">
                                <div class="flex items-center justify-between">
                                    <div class="inline-flex items-center space-x-2 rounded-full border border-slate-800 bg-slate-800/60 px-3 py-1 text-xs font-medium text-slate-300">
                                        <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>{{ $kelas->tahunAjaran->tahun_ajaran ?? 'Tahun ajaran belum diatur' }}</span>
                                    </div>
                                    <span class="rounded-full bg-sky-500/10 px-3 py-1 text-xs font-semibold text-sky-400 border border-sky-500/30">Rekap</span>
                                </div>

                                <div class="mt-4">
                                    <p class="text-sm text-slate-400">Nama Kelas</p>
                                    <h3 class="text-xl font-semibold text-slate-50">{{ $kelas->nama_kelas }}</h3>
                                </div>

                                <div class="mt-6 flex items-center justify-between text-sm">
                                    <div>
                                        <p class="text-slate-500 text-xs uppercase tracking-wide">Wali Kelas</p>
                                        <p class="text-slate-200 font-medium">{{ $kelas->waliKelas->nama_lengkap ?? 'Belum ditetapkan' }}</p>
                                    </div>
                                    <div class="text-slate-400 text-xs flex items-center">
                                        <span class="mr-1">Lihat Rekap</span>
                                        <svg class="w-4 h-4 text-slate-400 group-hover:text-sky-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
