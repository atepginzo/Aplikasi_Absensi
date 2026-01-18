<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-50 leading-tight">Rekap Bulanan Per Siswa</h2>
                <p class="mt-1 text-sm text-slate-400">Pilih kelas dan bulan untuk melihat total kehadiran setiap siswa</p>
            </div>
            @php
                $isWaliContext = request()->routeIs('wali.*');
                $backRoute = $isWaliContext ? 'wali.laporan.index' : 'admin.laporan.index';
            @endphp
            <a href="{{ route($backRoute) }}" class="inline-flex items-center px-5 py-2.5 bg-slate-800 border-2 border-slate-700 rounded-xl font-semibold text-sm text-slate-200 hover:border-slate-600 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition-all duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 shadow-2xl shadow-black/40 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-800 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-50">Filter Rekap Bulanan</h3>
                        <p class="text-sm text-slate-400">Tentukan kelas dan bulan yang ingin direkap</p>
                    </div>
                </div>
                <div class="px-6 py-6">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="kelas_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">Pilih Kelas</label>
                            <select name="kelas_id" id="kelas_id" class="w-full rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-sm text-gray-900 dark:text-gray-100 focus:border-sky-500 focus:ring-sky-500" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach ($daftarKelas as $kelas)
                                    <option value="{{ $kelas->id }}" {{ (string) $kelas->id === (string) ($kelasIdDipilih ?? '') ? 'selected' : '' }}>
                                        {{ $kelas->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="bulan" class="block text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">Bulan</label>
                            <input type="month" name="bulan" id="bulan" value="{{ $bulanDipilih }}" class="w-full rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-sm text-gray-900 dark:text-gray-100 focus:border-sky-500 focus:ring-sky-500" required />
                        </div>
                        <div class="flex items-end gap-3">
                            <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-sky-600 rounded-xl text-sm font-semibold text-white hover:bg-sky-500 transition">
                                Tampilkan Rekap
                            </button>
                            @if ($bulanDipilih || $kelasIdDipilih)
                                @php
                                    $filterRoute = $isWaliContext ? 'wali.laporan.bulanan' : 'admin.laporan.bulanan';
                                @endphp
                                <a href="{{ route($filterRoute) }}" class="inline-flex items-center px-5 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 text-sm font-semibold text-slate-600 dark:text-slate-200 hover:bg-slate-100/40 dark:hover:bg-slate-800/60 transition">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 shadow-2xl shadow-black/40 overflow-hidden">
                @if (! $bulanDipilih || ! $kelasIdDipilih)
                    <div class="p-10 text-center text-slate-400 text-sm">
                        Silakan pilih kelas dan bulan terlebih dahulu untuk menampilkan rekap.
                    </div>
                @elseif ($rekapBulanan->isEmpty())
                    <div class="p-10 text-center text-slate-400 text-sm">
                        Belum ada data kehadiran untuk periode ini.
                    </div>
                @else
                    <div class="px-6 pt-6 space-y-6">
                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 p-5">
                                <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-300 mb-2">Kelas</p>
                                <p class="text-xl font-semibold text-slate-900 dark:text-slate-50">{{ $kelasDipilih->nama_kelas ?? '-' }}</p>
                            </div>
                            <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 p-5">
                                <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-300 mb-2">Wali Kelas</p>
                                <p class="text-xl font-semibold text-slate-900 dark:text-slate-50">{{ $kelasDipilih->waliKelas->nama_lengkap ?? '-' }}</p>
                            </div>
                            <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 p-5">
                                <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-300 mb-2">Tahun Ajaran</p>
                                <p class="text-xl font-semibold text-slate-900 dark:text-slate-50">{{ $kelasDipilih->tahunAjaran->tahun_ajaran ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-6">
                            <div>
                                <p class="text-xs uppercase tracking-wide text-slate-500">Periode</p>
                                <p class="text-lg font-semibold text-slate-50">{{ $periodeLabel }}</p>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wide text-slate-500">Total Siswa</p>
                                <p class="text-lg font-semibold text-slate-50">{{ $rekapBulanan->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-slate-900/60">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-slate-400 uppercase tracking-wide">No</th>
                                    <th class="px-4 py-3 text-left font-semibold text-slate-400 uppercase tracking-wide">Nama Siswa</th>
                                    <th class="px-4 py-3 text-center font-semibold text-emerald-300 uppercase tracking-wide">Hadir</th>
                                    <th class="px-4 py-3 text-center font-semibold text-amber-300 uppercase tracking-wide">Sakit</th>
                                    <th class="px-4 py-3 text-center font-semibold text-sky-300 uppercase tracking-wide">Izin</th>
                                    <th class="px-4 py-3 text-center font-semibold text-rose-300 uppercase tracking-wide">Alpha</th>
                                </tr>
                            </thead>
                            <tbody class="bg-slate-950/40 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($rekapBulanan as $index => $rekap)
                                    <tr class="hover:bg-slate-900/40 transition-colors">
                                        <td class="px-4 py-4 text-slate-400">{{ $index + 1 }}</td>
                                        <td class="px-4 py-4 text-slate-100 font-semibold">{{ $rekap->nama_siswa }}</td>
                                        <td class="px-4 py-4 text-center">
                                            <span class="inline-flex items-center justify-center rounded-full bg-emerald-500/20 px-3 py-1 text-sm font-bold text-emerald-100 border border-emerald-400/40 shadow-inner shadow-emerald-900/30">{{ $rekap->total_hadir }}</span>
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <span class="inline-flex items-center justify-center rounded-full bg-amber-500/15 px-3 py-1 text-sm font-semibold text-amber-200 border border-amber-400/40">{{ $rekap->total_sakit }}</span>
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <span class="inline-flex items-center justify-center rounded-full bg-sky-500/15 px-3 py-1 text-sm font-semibold text-sky-200 border border-sky-400/40">{{ $rekap->total_izin }}</span>
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <span class="inline-flex items-center justify-center rounded-full bg-rose-500/15 px-3 py-1 text-sm font-semibold text-rose-200 border border-rose-400/40">{{ $rekap->total_alpha }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
