@php
    use Carbon\Carbon;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-50 leading-tight">Rekap {{ $kelas->nama_kelas }}</h2>
                <p class="mt-1 text-sm text-slate-400">Tahun Ajaran {{ $kelas->tahunAjaran->tahun_ajaran ?? '-' }}</p>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row">
                <a href="{{ route('wali.laporan.index') }}" class="inline-flex items-center px-5 py-2.5 bg-slate-800 border-2 border-slate-700 rounded-xl font-semibold text-sm text-slate-200 hover:border-slate-600 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Daftar
                </a>
                <a href="{{ route('wali.laporan.bulanan', ['kelas' => $kelas->id, 'bulan' => request('bulan', now()->format('Y-m'))]) }}" class="inline-flex items-center rounded-full border border-emerald-500/60 bg-emerald-500/10 px-4 py-2 text-sm font-semibold text-emerald-200 hover:bg-emerald-500/20 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18M3 9h18M9 21V9" />
                    </svg>
                    Rekap Bulanan
                </a>
                <a href="{{ route('wali.laporan.export', $kelas->id) }}" class="inline-flex items-center rounded-full border border-sky-600 bg-sky-600/10 px-4 py-2 text-sm font-semibold text-sky-200 hover:bg-sky-600/20 transition-colors">
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
                    <p class="text-xs uppercase tracking-wide text-slate-500 mb-2">Total Tanggal</p>
                    <p class="text-3xl font-bold text-sky-400">{{ $rekapKehadiran->count() }}</p>
                </div>
                <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-5">
                    <p class="text-xs uppercase tracking-wide text-slate-500 mb-2">Update Terakhir</p>
                    <p class="text-lg font-semibold text-slate-50">
                        {{ $rekapKehadiran->first()?->tanggal ? Carbon::parse($rekapKehadiran->first()->tanggal)->translatedFormat('d M Y') : '-' }}
                    </p>
                </div>
                <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-5">
                    <p class="text-xs uppercase tracking-wide text-slate-500 mb-2">Wali Kelas</p>
                    <p class="text-lg font-semibold text-slate-50">{{ $kelas->waliKelas->nama_lengkap ?? '-' }}</p>
                </div>
                <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-5">
                    <p class="text-xs uppercase tracking-wide text-slate-500 mb-2">Tahun Ajaran</p>
                    <p class="text-lg font-semibold text-slate-50">{{ $kelas->tahunAjaran->tahun_ajaran ?? '-' }}</p>
                </div>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 shadow-2xl shadow-black/40 overflow-hidden">
                @if ($rekapKehadiran->isEmpty())
                    <div class="p-10 text-center">
                        <p class="text-slate-400">Belum ada data absensi untuk kelas ini.</p>
                    </div>
                @else
                    {{-- Desktop Table View --}}
                    <div class="hidden sm:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-slate-900/60">
                                <tr>
                                    <th class="px-4 py-3 text-center font-semibold text-slate-400 uppercase tracking-wide">Tanggal</th>
                                    <th class="px-4 py-3 text-center font-semibold text-emerald-300 uppercase tracking-wide">Hadir</th>
                                    <th class="px-4 py-3 text-center font-semibold text-slate-400 uppercase tracking-wide">Sakit</th>
                                    <th class="px-4 py-3 text-center font-semibold text-slate-400 uppercase tracking-wide">Izin</th>
                                    <th class="px-4 py-3 text-center font-semibold text-slate-400 uppercase tracking-wide">Alpha</th>
                                    <th class="px-4 py-3 text-center font-semibold text-slate-400 uppercase tracking-wide">Total</th>
                                    <th class="px-4 py-3 text-center font-semibold text-slate-400 uppercase tracking-wide">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-slate-950/40">
                                @foreach ($rekapKehadiran as $rekap)
                                    <tr class="hover:bg-slate-900/40 transition-colors">
                                        <td class="px-4 py-3 text-center text-slate-100 font-semibold">
                                            {{ Carbon::parse($rekap->tanggal)->translatedFormat('d M Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center justify-center rounded-full bg-emerald-500/10 px-3 py-1 text-sm font-semibold text-emerald-300 border border-emerald-500/30">{{ $rekap->jumlah_hadir }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center justify-center rounded-full bg-amber-500/10 px-3 py-1 text-sm font-semibold text-amber-300 border border-amber-500/30">{{ $rekap->jumlah_sakit }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center justify-center rounded-full bg-sky-500/10 px-3 py-1 text-sm font-semibold text-sky-300 border border-sky-500/30">{{ $rekap->jumlah_izin }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center justify-center rounded-full bg-rose-500/10 px-3 py-1 text-sm font-semibold text-rose-300 border border-rose-500/30">{{ $rekap->jumlah_alpha }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center justify-center rounded-full bg-slate-800/80 px-3 py-1 text-sm font-semibold text-slate-200 border border-slate-700">{{ $rekap->total_input }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <a href="{{ route('wali.laporan.detail', ['kelas' => $kelas->id, 'tanggal' => $rekap->tanggal]) }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold rounded-full border border-slate-700 text-slate-200 hover:border-sky-500 hover:text-sky-300 hover:bg-slate-900/60 transition">
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

                    {{-- Mobile Card View --}}
                    <div class="sm:hidden p-4 space-y-3">
                        @foreach ($rekapKehadiran as $rekap)
                            <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-4">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-base font-bold text-slate-50">{{ Carbon::parse($rekap->tanggal)->translatedFormat('d M Y') }}</span>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-800 text-slate-300 border border-slate-700">Total: {{ $rekap->total_input }}</span>
                                </div>
                                <div class="grid grid-cols-4 gap-2 mb-4">
                                    <div class="text-center">
                                        <p class="text-xs text-slate-500 mb-1">Hadir</p>
                                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-emerald-500/20 text-sm font-bold text-emerald-300 border border-emerald-500/30">{{ $rekap->jumlah_hadir }}</span>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs text-slate-500 mb-1">Sakit</p>
                                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-amber-500/10 text-sm font-semibold text-amber-300 border border-amber-500/30">{{ $rekap->jumlah_sakit }}</span>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs text-slate-500 mb-1">Izin</p>
                                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-sky-500/10 text-sm font-semibold text-sky-300 border border-sky-500/30">{{ $rekap->jumlah_izin }}</span>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs text-slate-500 mb-1">Alpha</p>
                                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-rose-500/10 text-sm font-semibold text-rose-300 border border-rose-500/30">{{ $rekap->jumlah_alpha }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('wali.laporan.detail', ['kelas' => $kelas->id, 'tanggal' => $rekap->tanggal]) }}" class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded-xl border border-slate-700 text-slate-200 hover:border-sky-500 hover:text-sky-300 transition">
                                    Lihat Detail
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
