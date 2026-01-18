<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-bold text-2xl text-slate-50 leading-tight">Laporan Kehadiran Kelas</h2>
            <p class="mt-1 text-sm text-slate-400">Pilih kelas binaan Anda untuk melihat rekap absensi.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            {{-- Filter Tahun Ajaran --}}
            @if(isset($semuaTahunAjaran) && $semuaTahunAjaran->count() > 0)
            <form method="GET" action="{{ route('wali.laporan.index') }}" class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-end gap-4 bg-slate-900/80 backdrop-blur-sm border border-slate-800 rounded-2xl p-4">
                    <div class="w-full sm:w-1/4">
                        <label for="tahun_ajaran_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">Tahun Ajaran</label>
                        <select name="tahun_ajaran_id" id="tahun_ajaran_id" onchange="this.form.submit()" class="w-full rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-100 focus:border-sky-500 focus:ring-sky-500">
                            @foreach ($semuaTahunAjaran as $tahun)
                                <option value="{{ $tahun->id }}" {{ (string) $tahun->id === (string) $tahunPilihanId ? 'selected' : '' }}>
                                    {{ $tahun->tahun_ajaran }} {{ $tahun->is_active ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if (isset($tahunAktif) && request('tahun_ajaran_id') && request('tahun_ajaran_id') != $tahunAktif->id)
                        <a href="{{ route('wali.laporan.index') }}" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-slate-300 rounded-xl border border-slate-700 hover:bg-slate-800 transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
            @endif

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
                            <div class="group rounded-2xl border border-slate-800 bg-slate-900/70 p-6 shadow-lg shadow-black/30 transition-all duration-200 hover:-translate-y-1 hover:border-sky-500/60 hover:shadow-black/50">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm text-slate-400">{{ $kelas->tahunAjaran->tahun_ajaran ?? '-' }}</div>
                                    <span class="rounded-full bg-sky-500/10 px-3 py-1 text-xs font-semibold text-sky-400 border border-sky-500/30">Kelas</span>
                                </div>
                                <h3 class="mt-3 text-xl font-semibold text-slate-50">{{ $kelas->nama_kelas }}</h3>
                                <p class="mt-1 text-sm text-slate-400">{{ $kelas->waliKelas->nama_lengkap ?? '-' }}</p>
                                <div class="mt-5 flex flex-col gap-2">
                                    <a href="{{ route('wali.laporan.show', $kelas->id) }}" class="inline-flex items-center justify-between rounded-xl border border-slate-800 bg-slate-800/60 px-4 py-2 text-sm font-semibold text-slate-200 hover:border-sky-500 hover:text-sky-300 transition">
                                        Rekap Harian
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('wali.laporan.bulanan', ['kelas' => $kelas->id, 'bulan' => now()->format('Y-m')]) }}" class="inline-flex items-center justify-between rounded-xl border border-emerald-500/60 bg-emerald-500/10 px-4 py-2 text-sm font-semibold text-emerald-100 hover:bg-emerald-500/20 transition">
                                        Rekap Bulanan
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18M3 9h18M9 21V9" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
