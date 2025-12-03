<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-50 leading-tight">Rekap Bulanan Per Siswa</h2>
                <p class="mt-1 text-sm text-slate-400">Pilih kelas dan bulan untuk melihat total kehadiran setiap siswa</p>
            </div>
            <a href="{{ route('admin.laporan.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-slate-200 bg-slate-900/60 border border-slate-700 rounded-xl hover:bg-slate-800/70 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 shadow-2xl p-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="kelas_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">Pilih Kelas</label>
                        <select name="kelas_id" id="kelas_id" class="w-full rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-100 focus:border-sky-500 focus:ring-sky-500" required>
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
                        <input type="month" name="bulan" id="bulan" value="{{ $bulanDipilih }}" class="w-full rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-100 focus:border-sky-500 focus:ring-sky-500" required />
                    </div>
                    <div class="flex items-end gap-3">
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-sky-600 rounded-xl text-sm font-semibold text-white hover:bg-sky-500 transition">
                            Tampilkan Rekap
                        </button>
                        @if ($bulanDipilih || $kelasIdDipilih)
                            <a href="{{ route('admin.laporan.bulanan') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl border border-slate-700 text-sm font-semibold text-slate-200 hover:bg-slate-800 transition">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 shadow-2xl p-6">
                @if (! $bulanDipilih || ! $kelasIdDipilih)
                    <div class="text-center py-12 text-slate-400 text-sm">
                        Silakan pilih kelas dan bulan terlebih dahulu.
                    </div>
                @elseif ($rekapBulanan->isEmpty())
                    <div class="text-center py-12 text-slate-400 text-sm">
                        Belum ada data kehadiran untuk periode ini.
                    </div>
                @else
                    <div class="mb-6 flex flex-wrap gap-4 items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-400">Kelas</p>
                            <h3 class="text-xl font-semibold text-slate-50">{{ $kelasDipilih->nama_kelas }}</h3>
                        </div>
                        <div>
                            <p class="text-sm text-slate-400">Periode</p>
                            <h3 class="text-lg font-semibold text-slate-50">{{ $periodeLabel }}</h3>
                        </div>
                        <div>
                            <p class="text-sm text-slate-400">Total Siswa</p>
                            <h3 class="text-lg font-semibold text-slate-50">{{ $rekapBulanan->count() }}</h3>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-2xl border border-slate-800">
                        <table class="min-w-full divide-y divide-slate-800 text-sm">
                            <thead class="bg-slate-900/70">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-slate-400 uppercase tracking-wide">No</th>
                                    <th class="px-4 py-3 text-left font-semibold text-slate-400 uppercase tracking-wide">Nama Siswa</th>
                                    <th class="px-4 py-3 text-center font-semibold text-emerald-300 uppercase tracking-wide">H (Hadir)</th>
                                    <th class="px-4 py-3 text-center font-semibold text-amber-300 uppercase tracking-wide">S (Sakit)</th>
                                    <th class="px-4 py-3 text-center font-semibold text-sky-300 uppercase tracking-wide">I (Izin)</th>
                                    <th class="px-4 py-3 text-center font-semibold text-rose-300 uppercase tracking-wide">A (Alpha)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-slate-950/50 divide-y divide-slate-800">
                                @foreach ($rekapBulanan as $index => $rekap)
                                    <tr class="hover:bg-slate-900/40 transition-colors">
                                        <td class="px-4 py-3 text-slate-400">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 text-slate-100 font-semibold">{{ $rekap->nama_siswa }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center justify-center rounded-full bg-emerald-500/20 px-3 py-1 text-sm font-bold text-emerald-100 border border-emerald-400/40">{{ $rekap->total_hadir }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center justify-center rounded-full bg-amber-500/20 px-3 py-1 text-sm font-semibold text-amber-100 border border-amber-400/40">{{ $rekap->total_sakit }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center justify-center rounded-full bg-sky-500/20 px-3 py-1 text-sm font-semibold text-sky-100 border border-sky-400/40">{{ $rekap->total_izin }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center justify-center rounded-full bg-rose-500/20 px-3 py-1 text-sm font-semibold text-rose-100 border border-rose-400/40">{{ $rekap->total_alpha }}</span>
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
