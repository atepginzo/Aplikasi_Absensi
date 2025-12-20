@php
    $statusStyles = [
        'Hadir' => 'bg-emerald-500/20 text-emerald-100 border border-emerald-400/40',
        'Sakit' => 'bg-amber-500/20 text-amber-100 border border-amber-400/40',
        'Izin' => 'bg-sky-500/20 text-sky-100 border border-sky-400/40',
        'Alpha' => 'bg-rose-500/20 text-rose-100 border border-rose-400/40',
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm text-slate-400">Laporan Kehadiran Tanggal</p>
                <h2 class="font-bold text-2xl text-slate-50 leading-tight">{{ $tanggalLabel }}</h2>
                <p class="mt-1 text-sm text-slate-400">Kelas {{ $kelas->nama_kelas }} • Tahun Ajaran {{ $kelas->tahunAjaran->tahun_ajaran ?? '-' }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.laporan.show', $kelas->id) }}" class="inline-flex items-center rounded-xl border border-slate-700 bg-slate-900/70 px-4 py-2 text-sm font-semibold text-slate-200 hover:bg-slate-800 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Rekap
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid gap-4 md:grid-cols-3">
                <div class="rounded-2xl border border-slate-800 bg-slate-900/80 p-5">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Total Siswa</p>
                    <p class="text-3xl font-semibold text-slate-50 mt-1">{{ $daftarSiswa->count() }}</p>
                </div>
                <div class="rounded-2xl border border-slate-800 bg-slate-900/80 p-5">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Wali Kelas</p>
                    <p class="text-lg font-semibold text-slate-50 mt-1">{{ $kelas->waliKelas->nama_lengkap ?? '-' }}</p>
                </div>
                <div class="rounded-2xl border border-slate-800 bg-slate-900/80 p-5">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Tanggal Input</p>
                    <p class="text-lg font-semibold text-slate-50 mt-1">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d M Y') }}</p>
                </div>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 shadow-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-800">
                    <h3 class="text-lg font-semibold text-slate-50">Detail Kehadiran Per Siswa</h3>
                    <p class="text-sm text-slate-400">Status kehadiran hanya untuk tampilan (read only).</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-slate-900/70">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-slate-400 uppercase tracking-wide">No</th>
                                <th class="px-4 py-3 text-left font-semibold text-slate-400 uppercase tracking-wide">Nama Siswa</th>
                                <th class="px-4 py-3 text-left font-semibold text-slate-400 uppercase tracking-wide">Jenis Kelamin</th>
                                <th class="px-4 py-3 text-center font-semibold text-slate-400 uppercase tracking-wide">Status Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-slate-950/50">
                            @forelse ($daftarSiswa as $index => $siswa)
                                @php
                                    $status = optional($kehadiranPadaTanggal->get($siswa->id))->status;
                                    $badgeClass = $statusStyles[$status] ?? 'bg-slate-800/70 text-slate-300 border border-slate-700';
                                    $label = $status ?? 'Belum Ada Data';
                                @endphp
                                <tr class="hover:bg-slate-900/40 transition-colors">
                                    <td class="px-4 py-3 text-slate-400">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 text-slate-100 font-semibold">{{ $siswa->nama_siswa }}</td>
                                    <td class="px-4 py-3 text-slate-300">
                                        @if ($siswa->jenis_kelamin === 'L')
                                            Laki-laki
                                        @elseif ($siswa->jenis_kelamin === 'P')
                                            Perempuan
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                            {{ $label }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-slate-400">Belum ada siswa pada kelas ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
