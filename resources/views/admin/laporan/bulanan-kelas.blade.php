<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-50">Rekap Bulanan {{ $kelas->nama_kelas }}</h2>
                <p class="mt-1 text-sm text-slate-400">Tahun Ajaran {{ $kelas->tahunAjaran->tahun_ajaran ?? '-' }} • Wali Kelas {{ $kelas->waliKelas->nama_lengkap ?? '-' }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.laporan.show', $kelas->id) }}" class="inline-flex items-center rounded-full border border-slate-700 px-4 py-2 text-sm font-medium text-slate-200 hover:bg-slate-800/70 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Rekap Harian
                </a>
                <a href="{{ route('admin.laporan.bulanan-export', ['kelas' => $kelas->id, 'bulan' => $bulanDipilih]) }}" class="inline-flex items-center rounded-full border border-sky-600 bg-sky-600/10 px-4 py-2 text-sm font-semibold text-sky-200 hover:bg-sky-600/20 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v12m0 0l-4-4m4 4l4-4m-8 8h8" />
                    </svg>
                    Download PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="rounded-2xl border border-slate-800 bg-slate-900/80 p-6 shadow-2xl shadow-black/40">
                <form method="GET" action="{{ route('admin.laporan.bulanan-kelas', $kelas->id) }}" class="grid gap-4 md:grid-cols-3">
                    <div>
                        <label for="bulan" class="block text-xs font-semibold uppercase tracking-wide text-slate-400 mb-2">Pilih Bulan</label>
                        <input type="month" id="bulan" name="bulan" value="{{ $bulanDipilih }}" class="w-full rounded-xl border border-slate-800 bg-slate-950/70 px-4 py-2 text-sm text-slate-100 focus:border-sky-500 focus:ring-sky-500 [&::-webkit-calendar-picker-indicator]:filter [&::-webkit-calendar-picker-indicator]:invert [&::-webkit-calendar-picker-indicator]:cursor-pointer" required>
                    </div>
                    <div class="md:col-span-2 flex items-end gap-3">
                        <button type="submit" class="inline-flex items-center rounded-xl bg-sky-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-sky-500 transition">
                            Tampilkan
                        </button>
                        <a href="{{ route('admin.laporan.bulanan-export', ['kelas' => $kelas->id, 'bulan' => $bulanDipilih]) }}" class="inline-flex items-center rounded-xl border border-slate-700 px-5 py-2.5 text-sm font-semibold text-slate-200 hover:bg-slate-800/70 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v12m0 0l-4-4m4 4l4-4m-8 8h8" />
                            </svg>
                            PDF Bulan Ini
                        </a>
                    </div>
                </form>
            </div>

            <div class="rounded-2xl border border-slate-800 bg-slate-900/80 p-6 shadow-2xl shadow-black/40">
                <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-800 pb-5 mb-5">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Periode</p>
                        <p class="text-lg font-semibold text-slate-300">{{ $periodeLabel }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Total Siswa</p>
                        <p class="text-lg font-semibold text-slate-300">{{ $rekapBulanan->count() }}</p>
                    </div>
                </div>

                @if ($rekapBulanan->isEmpty())
                    <div class="py-12 text-center text-slate-400 text-sm">
                        Belum ada data kehadiran pada bulan ini.
                    </div>
                @else
                    <div class="overflow-x-auto rounded-2xl border border-gray-200 dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-slate-900/70">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold uppercase tracking-wide text-slate-400">No</th>
                                    <th class="px-4 py-3 text-left font-semibold uppercase tracking-wide text-slate-400">Nama Siswa</th>
                                    <th class="px-4 py-3 text-center font-semibold uppercase tracking-wide text-emerald-300">H</th>
                                    <th class="px-4 py-3 text-center font-semibold uppercase tracking-wide text-amber-300">S</th>
                                    <th class="px-4 py-3 text-center font-semibold uppercase tracking-wide text-sky-300">I</th>
                                    <th class="px-4 py-3 text-center font-semibold uppercase tracking-wide text-rose-300">A</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-slate-950/40">
                                @foreach ($rekapBulanan as $index => $rekap)
                                    <tr class="hover:bg-slate-900/40 transition">
                                        <td class="px-4 py-3 text-slate-400">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 font-semibold text-slate-100">{{ $rekap->nama_siswa }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center justify-center rounded-full bg-emerald-50 px-3 py-1 text-sm font-semibold text-emerald-600 border border-emerald-100">{{ $rekap->total_hadir }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center justify-center rounded-full bg-amber-50 px-3 py-1 text-sm font-semibold text-amber-600 border border-amber-100">{{ $rekap->total_sakit }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center justify-center rounded-full bg-sky-50 px-3 py-1 text-sm font-semibold text-sky-600 border border-sky-100">{{ $rekap->total_izin }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center justify-center rounded-full bg-rose-50 px-3 py-1 text-sm font-semibold text-rose-600 border border-rose-100">{{ $rekap->total_alpha }}</span>
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
