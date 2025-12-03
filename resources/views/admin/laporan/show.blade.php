@php
    use Carbon\Carbon;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-50 leading-tight">Rekap Kehadiran {{ $kelas->nama_kelas }}</h2>
                <p class="mt-1 text-sm text-slate-400">Tahun Ajaran {{ $kelas->tahunAjaran->tahun_ajaran ?? '-' }} • Wali Kelas {{ $kelas->waliKelas->nama_lengkap ?? '-' }}</p>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row">
                <a href="{{ route('admin.laporan.index') }}" class="inline-flex items-center justify-center rounded-full border border-slate-700 bg-slate-900/70 px-4 py-2 text-sm font-medium text-slate-200 hover:bg-slate-800 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
                <a href="{{ route('admin.laporan.export', $kelas->id) }}" class="inline-flex items-center justify-center rounded-full border border-sky-600 bg-sky-600/10 px-4 py-2 text-sm font-semibold text-sky-200 hover:bg-sky-600/20 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v12m0 0l-4-4m4 4l4-4m-8 8h8" />
                    </svg>
                    Download PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-5">
                    <p class="text-xs uppercase tracking-wide text-slate-500 mb-2">Tahun Ajaran</p>
                    <p class="text-xl font-semibold text-slate-50">{{ $kelas->tahunAjaran->tahun_ajaran ?? 'Belum diatur' }}</p>
                </div>
                <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-5">
                    <p class="text-xs uppercase tracking-wide text-slate-500 mb-2">Wali Kelas</p>
                    <p class="text-xl font-semibold text-slate-50">{{ $kelas->waliKelas->nama_lengkap ?? 'Belum diatur' }}</p>
                </div>
                <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-5">
                    <p class="text-xs uppercase tracking-wide text-slate-500 mb-2">Total Tanggal</p>
                    <p class="text-3xl font-bold text-sky-400">{{ $rekapKehadiran->count() }}</p>
                </div>
                <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-5">
                    <p class="text-xs uppercase tracking-wide text-slate-500 mb-2">Update Terakhir</p>
                    <p class="text-lg font-semibold text-slate-50">
                        {{ $rekapKehadiran->first()?->tanggal ? Carbon::parse($rekapKehadiran->first()->tanggal)->translatedFormat('d M Y') : '-' }}
                    </p>
                </div>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 shadow-2xl shadow-black/40 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-50">Tabel Rekap Per Tanggal</h3>
                        <p class="text-sm text-slate-400">Jumlah hadir, sakit, izin, dan alpha berdasarkan input</p>
                    </div>
                </div>

                @if ($rekapKehadiran->isEmpty())
                    <div class="p-10 text-center">
                        <p class="text-slate-400">Belum ada data absensi untuk kelas ini.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-800 text-sm">
                            <thead class="bg-slate-900/60">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold text-slate-400 uppercase tracking-wide">Tanggal</th>
                                    <th class="px-6 py-4 text-center font-semibold text-emerald-300 uppercase tracking-wide">Hadir</th>
                                    <th class="px-6 py-4 text-center font-semibold text-slate-400 uppercase tracking-wide">Sakit</th>
                                    <th class="px-6 py-4 text-center font-semibold text-slate-400 uppercase tracking-wide">Izin</th>
                                    <th class="px-6 py-4 text-center font-semibold text-slate-400 uppercase tracking-wide">Alpha</th>
                                    <th class="px-6 py-4 text-center font-semibold text-slate-400 uppercase tracking-wide">Total Input</th>
                                    <th class="px-6 py-4 text-center font-semibold text-slate-400 uppercase tracking-wide">Detail</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800 bg-slate-950/40">
                                @foreach ($rekapKehadiran as $rekap)
                                    <tr class="hover:bg-slate-900/40 transition-colors">
                                        <td class="px-6 py-4 text-slate-100 font-semibold">
                                            {{ Carbon::parse($rekap->tanggal)->translatedFormat('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center justify-center rounded-full bg-emerald-500/20 px-3 py-1 text-sm font-bold text-emerald-100 border border-emerald-400/40 shadow-inner shadow-emerald-900/30">{{ $rekap->jumlah_hadir }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center justify-center rounded-full bg-amber-500/10 px-3 py-1 text-sm font-semibold text-amber-300 border border-amber-500/30">{{ $rekap->jumlah_sakit }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center justify-center rounded-full bg-sky-500/10 px-3 py-1 text-sm font-semibold text-sky-300 border border-sky-500/30">{{ $rekap->jumlah_izin }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center justify-center rounded-full bg-rose-500/10 px-3 py-1 text-sm font-semibold text-rose-300 border border-rose-500/30">{{ $rekap->jumlah_alpha }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center justify-center rounded-full bg-slate-800/80 px-3 py-1 text-sm font-semibold text-slate-200 border border-slate-700">{{ $rekap->total_input }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('admin.laporan.detail', ['kelas' => $kelas->id, 'tanggal' => $rekap->tanggal]) }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold rounded-full border border-slate-700 text-slate-200 hover:border-sky-500 hover:text-sky-300 hover:bg-slate-900/60 transition">
                                                Detail
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
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
