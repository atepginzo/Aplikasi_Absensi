<x-app-layout>
    <x-slot name="header">
        <div>
            @if ($siswa && $kelas)
                <p class="text-sm text-slate-400">Dashboard Orang Tua</p>
                <h2 class="font-bold text-2xl text-slate-50 leading-tight">
                    Dashboard Orang Tua - {{ $siswa->nama_siswa }} - {{ $kelas->nama_kelas }}
                </h2>
            @else
                <h2 class="font-bold text-2xl text-slate-50 leading-tight">
                    Dashboard Orang Tua
                </h2>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if ($pesanError)
                <div class="rounded-2xl border border-amber-500/40 bg-amber-500/10 px-4 py-3 text-sm text-amber-200">
                    {{ $pesanError }}
                </div>
            @endif

            @if ($siswa && $kelas)
                <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6 shadow-lg shadow-black/30">
                    <h3 class="text-lg font-semibold text-slate-50 mb-4">Informasi Anak</h3>
                    <dl class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm text-slate-300">
                        <div>
                            <dt class="text-slate-500">Nama Anak</dt>
                            <dd class="font-semibold text-slate-50">{{ $siswa->nama_siswa }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">NIS</dt>
                            <dd class="font-semibold text-slate-50">{{ $siswa->nis }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Kelas</dt>
                            <dd class="font-semibold text-sky-400">{{ $kelas->nama_kelas }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="col-span-1 md:col-span-4 md:col-span-1 rounded-2xl border border-slate-800 bg-slate-900/80 p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500 mb-1">Total Data</p>
                        <p class="text-3xl font-bold text-slate-50">{{ $totalSemua }}</p>
                        <p class="text-xs text-slate-400 mt-1">Seluruh catatan kehadiran</p>
                    </div>
                    <div class="rounded-2xl border border-emerald-600/40 bg-emerald-600/10 p-4">
                        <p class="text-xs uppercase tracking-wide text-emerald-300 mb-1">Hadir</p>
                        <p class="text-3xl font-bold text-emerald-300">{{ $totalHadir }}</p>
                    </div>
                    <div class="rounded-2xl border border-amber-500/40 bg-amber-500/10 p-4">
                        <p class="text-xs uppercase tracking-wide text-amber-200 mb-1">Sakit</p>
                        <p class="text-3xl font-bold text-amber-200">{{ $totalSakit }}</p>
                    </div>
                    <div class="rounded-2xl border border-sky-500/40 bg-sky-500/10 p-4">
                        <p class="text-xs uppercase tracking-wide text-sky-200 mb-1">Izin</p>
                        <p class="text-3xl font-bold text-sky-200">{{ $totalIzin }}</p>
                    </div>
                    <div class="rounded-2xl border border-red-500/40 bg-red-500/10 p-4">
                        <p class="text-xs uppercase tracking-wide text-red-200 mb-1">Alpha</p>
                        <p class="text-3xl font-bold text-red-200">{{ $totalAlpha }}</p>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6 shadow-lg shadow-black/30">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-50">Riwayat Kehadiran Anak</h3>
                            <p class="text-xs text-slate-400 mt-1">Menampilkan maksimal 30 catatan terakhir.</p>
                        </div>
                    </div>

                    @if ($riwayatKehadiran->isEmpty())
                        <p class="text-sm text-slate-400">Belum ada data kehadiran untuk anak Anda.</p>
                    @else
                        <div class="overflow-x-auto rounded-xl border border-slate-800 bg-slate-950/60">
                            <table class="min-w-full divide-y divide-slate-800 text-sm">
                                <thead class="bg-slate-900/80">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Jam Masuk</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Jam Pulang</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-slate-950/40 divide-y divide-slate-800">
                                    @foreach ($riwayatKehadiran as $kehadiran)
                                        @php
                                            $status = $kehadiran->status;
                                            $badgeClasses = match ($status) {
                                                'Hadir' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40',
                                                'Sakit' => 'bg-amber-500/20 text-amber-200 border-amber-500/40',
                                                'Izin' => 'bg-sky-500/20 text-sky-200 border-sky-500/40',
                                                'Alpha' => 'bg-red-500/20 text-red-300 border-red-500/40',
                                                default => 'bg-slate-700/40 text-slate-200 border-slate-600/60',
                                            };
                                        @endphp
                                        <tr class="hover:bg-slate-900/70 transition-colors">
                                            <td class="px-4 py-3 text-slate-100 text-sm">
                                                {{ \Carbon\Carbon::parse($kehadiran->tanggal)->translatedFormat('d F Y') }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $badgeClasses }}">
                                                    {{ $status }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-slate-300 text-xs">
                                                {{ $kehadiran->jam_masuk ? \Carbon\Carbon::parse($kehadiran->jam_masuk)->format('H:i') : '-' }}
                                            </td>
                                            <td class="px-4 py-3 text-slate-300 text-xs">
                                                {{ $kehadiran->jam_pulang ? \Carbon\Carbon::parse($kehadiran->jam_pulang)->format('H:i') : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
