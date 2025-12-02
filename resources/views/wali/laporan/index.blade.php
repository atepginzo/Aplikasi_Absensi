<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-bold text-2xl text-slate-50 leading-tight">Laporan Kehadiran Kelas</h2>
            <p class="mt-1 text-sm text-slate-400">Pilih kelas binaan Anda untuk melihat rekap absensi.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 shadow-2xl shadow-black/40 p-8">
                @if ($daftarKelas->isEmpty())
                    <div class="text-center py-16">
                        <div class="mx-auto w-16 h-16 flex items-center justify-center rounded-2xl bg-slate-800 text-slate-500 mb-6">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-100">Belum ada kelas terhubung</h3>
                        <p class="mt-2 text-sm text-slate-400">Hubungi admin untuk menghubungkan akun Anda ke kelas tertentu.</p>
                    </div>
                @else
                    <div class="grid gap-6 sm:grid-cols-2">
                        @foreach ($daftarKelas as $kelas)
                            <a href="{{ route('wali.laporan.show', $kelas->id) }}"
                               class="group rounded-2xl border border-slate-800 bg-slate-900/70 p-6 shadow-lg shadow-black/30 transition-all duration-200 hover:-translate-y-1 hover:border-sky-500/60 hover:shadow-black/50">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm text-slate-400">{{ $kelas->tahunAjaran->tahun_ajaran ?? '-' }}</div>
                                    <span class="rounded-full bg-sky-500/10 px-3 py-1 text-xs font-semibold text-sky-400 border border-sky-500/30">Lihat</span>
                                </div>
                                <h3 class="mt-3 text-xl font-semibold text-slate-50">{{ $kelas->nama_kelas }}</h3>
                                <p class="mt-1 text-sm text-slate-400">{{ $kelas->waliKelas->nama_lengkap ?? '-' }}</p>
                                <div class="mt-4 flex items-center justify-between text-xs text-slate-500">
                                    <span>Rekap per tanggal</span>
                                    <svg class="w-4 h-4 text-slate-400 group-hover:text-sky-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
