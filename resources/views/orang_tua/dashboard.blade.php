<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm text-slate-400">Dashboard Orang Tua</p>
            <h2 class="font-bold text-2xl text-slate-50 leading-tight">
                {{ $siswa && $kelas ? $siswa->nama_siswa . ' · ' . $kelas->nama_kelas : 'Pantau Kehadiran Anak' }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-950 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            @if ($pesanError)
                <div class="rounded-2xl border border-amber-500/40 bg-amber-500/10 px-4 py-3 text-sm text-amber-200">
                    {{ $pesanError }}
                </div>
            @endif

            {{-- Filter Tahun Ajaran --}}
            @if(isset($semuaTahun) && $semuaTahun->count() > 0)
            <form method="GET" action="{{ route('orang-tua.dashboard') }}">
                <div class="flex flex-col sm:flex-row sm:items-end gap-4 bg-slate-900/80 backdrop-blur-sm border border-slate-800 rounded-2xl p-4">
                    <div class="w-full sm:w-1/3">
                        <label for="tahun_ajaran_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">Tahun Ajaran</label>
                        <select name="tahun_ajaran_id" id="tahun_ajaran_id" onchange="this.form.submit()" class="w-full rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-100 focus:border-sky-500 focus:ring-sky-500">
                            @foreach ($semuaTahun as $tahun)
                                <option value="{{ $tahun->id }}" {{ isset($tahunDipilih) && $tahun->id === $tahunDipilih->id ? 'selected' : '' }}>
                                    {{ $tahun->tahun_ajaran }} {{ $tahun->is_active ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if (isset($tahunAktif) && request('tahun_ajaran_id') && request('tahun_ajaran_id') != $tahunAktif->id)
                        <a href="{{ route('orang-tua.dashboard') }}" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-slate-300 rounded-xl border border-slate-700 hover:bg-slate-800 transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
            @endif

            @if ($siswa && $kelas)
                @php
                    $todayDate = \Carbon\Carbon::today()->toDateString();
                    $kehadiranHariIni = $riwayatKehadiran->firstWhere('tanggal', $todayDate);
                    $statusStyles = [
                        'Hadir' => [
                            'badge' => 'bg-emerald-500/15 text-emerald-200 border border-emerald-500/40',
                            'iconWrapper' => 'bg-emerald-500/10 text-emerald-300 border-emerald-500/30',
                        ],
                        'Sakit' => [
                            'badge' => 'bg-amber-500/15 text-amber-200 border border-amber-500/40',
                            'iconWrapper' => 'bg-amber-500/10 text-amber-300 border-amber-500/30',
                        ],
                        'Izin' => [
                            'badge' => 'bg-sky-500/15 text-sky-200 border border-sky-500/40',
                            'iconWrapper' => 'bg-sky-500/10 text-sky-200 border-sky-500/30',
                        ],
                        'Alpha' => [
                            'badge' => 'bg-red-500/15 text-red-200 border border-red-500/40',
                            'iconWrapper' => 'bg-red-500/10 text-red-300 border-red-500/30',
                        ],
                    ];
                @endphp

                {{-- Profil Siswa --}}
                <div class="relative overflow-hidden rounded-3xl border border-slate-800 bg-gradient-to-br from-slate-900 via-slate-900 to-slate-950 p-6 sm:p-8 shadow-2xl shadow-indigo-900/40">
                    <div class="absolute inset-0 opacity-20" aria-hidden="true">
                        <div class="w-full h-full bg-[radial-gradient(circle_at_top,_rgba(79,70,229,0.25),_transparent_60%)]"></div>
                    </div>
                    <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                        <div class="space-y-4">
                            <p class="text-xs uppercase tracking-[0.35em] text-slate-400">Profil Anak</p>
                            <h3 class="text-3xl font-bold text-slate-400">{{ $siswa->nama_siswa }}</h3>
                            <div class="flex flex-wrap gap-3">
                                <span class="inline-flex items-center rounded-full border border-slate-700/60 bg-slate-900/50 px-4 py-1 text-sm font-semibold text-slate-400">
                                    {{ $kelas->nama_kelas }}
                                </span>
                                <span class="inline-flex items-center rounded-full border border-slate-700/60 bg-slate-900/50 px-4 py-1 text-sm font-semibold text-slate-400">
                                    NIS {{ $siswa->nis }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="relative w-24 h-24 rounded-full border-4 border-white/20 bg-slate-900/70 shadow-xl shadow-black/50 overflow-hidden flex items-center justify-center">
                                @if ($siswa->user?->photo_url)
                                    <img src="{{ $siswa->user->photo_url }}" alt="{{ $siswa->nama_siswa }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-3xl font-bold text-white">{{ strtoupper(substr($siswa->nama_siswa, 0, 1)) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Status Hari Ini --}}
                @if ($kehadiranHariIni)
                    @php
                        $style = $statusStyles[$kehadiranHariIni->status] ?? ['badge' => 'bg-slate-800 text-slate-200 border border-slate-700', 'iconWrapper' => 'bg-slate-800 text-slate-200 border-slate-700'];
                        $jamMasukHariIni = $kehadiranHariIni->jam_masuk ? \Carbon\Carbon::parse($kehadiranHariIni->jam_masuk)->format('H:i') : '-';
                    @endphp
                    <div class="rounded-2xl border {{ $style['iconWrapper'] }} px-5 py-4 flex items-start gap-4 shadow-lg">
                        <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-2xl border {{ $style['iconWrapper'] }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h11z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-widest text-slate-300">Status Hari Ini</p>
                            <p class="text-base sm:text-lg font-semibold text-white mt-1">
                                Halo, {{ $siswa->nama_siswa }} tercatat {{ strtolower($kehadiranHariIni->status) }} hari ini pada pukul {{ $jamMasukHariIni }}.
                            </p>
                            <p class="text-sm text-slate-400">Pantau terus progres kehadiran anak Anda secara real-time.</p>
                        </div>
                    </div>
                @endif

                {{-- Ringkasan Statistik --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="rounded-2xl border border-emerald-500/30 bg-slate-900/80 p-4 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-wide text-emerald-200">Hadir</p>
                                <p class="text-3xl font-bold text-white">{{ $totalHadir }}</p>
                            </div>
                            <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-emerald-500/40 bg-emerald-500/10 text-emerald-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-amber-500/30 bg-slate-900/80 p-4 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-wide text-amber-200">Sakit</p>
                                <p class="text-3xl font-bold text-white">{{ $totalSakit }}</p>
                            </div>
                            <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-amber-500/40 bg-amber-500/10 text-amber-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3 0 2 3 5 3 5s3-3 3-5c0-1.657-1.343-3-3-3z" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-sky-500/30 bg-slate-900/80 p-4 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-wide text-sky-200">Izin</p>
                                <p class="text-3xl font-bold text-white">{{ $totalIzin }}</p>
                            </div>
                            <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-sky-500/40 bg-sky-500/10 text-sky-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M12 5l7 7-7 7" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-red-500/30 bg-slate-900/80 p-4 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-wide text-red-200">Alpha</p>
                                <p class="text-3xl font-bold text-white">{{ $totalAlpha }}</p>
                            </div>
                            <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-red-500/40 bg-red-500/10 text-red-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Riwayat Kehadiran --}}
                <div class="rounded-3xl border border-slate-800 bg-slate-900/80 p-6 shadow-2xl shadow-black/30">
                    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
                        <div>
                            <h3 class="text-xl font-semibold text-white">Riwayat Kehadiran</h3>
                            <p class="text-sm text-slate-400">Maksimal 30 catatan terakhir ditampilkan otomatis.</p>
                        </div>
                        <span class="inline-flex items-center rounded-full border border-slate-700 px-3 py-1 text-xs font-semibold text-slate-300">Total {{ $totalSemua }} catatan</span>
                    </div>

                    @if ($riwayatKehadiran->isEmpty())
                        <div class="text-center py-10">
                            <p class="text-sm text-slate-400">Belum ada data kehadiran untuk anak Anda.</p>
                        </div>
                    @else
                        <div class="hidden sm:block">
                            <div class="overflow-hidden rounded-2xl border border-slate-800">
                                <table class="min-w-full divide-y divide-slate-800 text-sm">
                                    <thead class="bg-slate-900/90">
                                        <tr>
                                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-widest text-slate-400">Tanggal</th>
                                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-widest text-slate-400">Status</th>
                                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-widest text-slate-400">Jam Masuk</th>
                                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-widest text-slate-400">Jam Pulang</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-slate-950/40 divide-y divide-slate-900">
                                        @foreach ($riwayatKehadiran as $kehadiran)
                                            @php
                                                $status = $kehadiran->status;
                                                $style = $statusStyles[$status] ?? ['badge' => 'bg-slate-800 text-slate-300 border border-slate-700', 'iconWrapper' => 'bg-slate-800 text-slate-200 border-slate-700'];
                                            @endphp
                                            <tr class="hover:bg-slate-900/70 transition">
                                                <td class="px-5 py-4 text-slate-100">{{ \Carbon\Carbon::parse($kehadiran->tanggal)->translatedFormat('d F Y') }}</td>
                                                <td class="px-5 py-4">
                                                    <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold {{ $style['badge'] }}">
                                                        {{ $status }}
                                                    </span>
                                                </td>
                                                <td class="px-5 py-4 text-slate-300">
                                                    {{ $kehadiran->jam_masuk ? \Carbon\Carbon::parse($kehadiran->jam_masuk)->format('H:i') : '-' }}
                                                </td>
                                                <td class="px-5 py-4 text-slate-300">
                                                    {{ $kehadiran->jam_pulang ? \Carbon\Carbon::parse($kehadiran->jam_pulang)->format('H:i') : '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="sm:hidden space-y-3">
                            @foreach ($riwayatKehadiran as $kehadiran)
                                @php
                                    $status = $kehadiran->status;
                                    $style = $statusStyles[$status] ?? ['badge' => 'bg-slate-800 text-slate-300 border border-slate-700', 'iconWrapper' => 'bg-slate-800 text-slate-200 border-slate-700'];
                                @endphp
                                <div class="rounded-2xl border border-slate-800 bg-slate-800/60 p-4 shadow-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <p class="text-sm font-semibold text-white">{{ \Carbon\Carbon::parse($kehadiran->tanggal)->translatedFormat('d M Y') }}</p>
                                            <p class="text-xs text-slate-400">Jam Masuk: {{ $kehadiran->jam_masuk ? \Carbon\Carbon::parse($kehadiran->jam_masuk)->format('H:i') : '-' }}</p>
                                        </div>
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $style['badge'] }}">{{ $status }}</span>
                                    </div>
                                    <p class="text-xs text-slate-400">Jam Pulang: {{ $kehadiran->jam_pulang ? \Carbon\Carbon::parse($kehadiran->jam_pulang)->format('H:i') : '-' }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
