@php
    // Warna dasar (saat tidak dipilih) - transparan dengan border tipis
    $baseColors = [
        'Hadir' => 'bg-emerald-500/10 border-emerald-500/40 text-emerald-400 hover:bg-emerald-500/20',
        'Sakit' => 'bg-amber-500/10 border-amber-500/40 text-amber-400 hover:bg-amber-500/20',
        'Izin' => 'bg-sky-500/10 border-sky-500/40 text-sky-400 hover:bg-sky-500/20',
        'Alpha' => 'bg-rose-500/10 border-rose-500/40 text-rose-400 hover:bg-rose-500/20',
    ];

    // Warna saat DIPILIH (Active State) - warna solid + ring cahaya
    // Kita gunakan 'peer-checked' agar berubah otomatis saat diklik tanpa reload
    $checkedColors = [
        'Hadir' => 'peer-checked:bg-emerald-500 peer-checked:border-emerald-500 peer-checked:text-white peer-checked:ring-emerald-400',
        'Sakit' => 'peer-checked:bg-amber-500 peer-checked:border-amber-500 peer-checked:text-white peer-checked:ring-amber-400',
        'Izin' => 'peer-checked:bg-sky-500 peer-checked:border-sky-500 peer-checked:text-white peer-checked:ring-sky-400',
        'Alpha' => 'peer-checked:bg-rose-500 peer-checked:border-rose-500 peer-checked:text-white peer-checked:ring-rose-400',
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-50 leading-tight">Absensi Manual {{ $kelas->nama_kelas }}</h2>
                <p class="mt-1 text-sm text-slate-400"> {{ $tanggalLabel }} • Wali Kelas {{ $kelas->waliKelas->nama_lengkap ?? '-' }}</p>
            </div>
            <a href="{{ route('admin.absensi.manual.index') }}" class="inline-flex items-center px-5 py-2.5 bg-slate-800 border-2 border-slate-700 rounded-xl font-semibold text-sm text-slate-200 hover:border-slate-600 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition-all duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar Kelas
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="p-4 rounded-xl border border-emerald-500/40 bg-emerald-500/10 text-emerald-200">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('info'))
                <div class="p-4 rounded-xl border border-slate-600 bg-slate-800/60 text-slate-200">
                    {{ session('info') }}
                </div>
            @endif

            {{-- Filter Tanggal --}}
            <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 shadow-2xl shadow-black/40 p-6">
                <form method="GET" action="{{ route('admin.absensi.manual.show', $kelas->id) }}" class="flex flex-col gap-4 md:flex-row md:items-end">
                    <div class="flex-1">
                        <label for="tanggal" class="block text-sm font-medium text-slate-300 mb-2">Pilih Tanggal</label>
                            <input type="date" id="tanggal" name="tanggal" value="{{ $tanggalDipilih }}"     class="w-full rounded-xl border border-slate-700 bg-slate-900 text-slate-100 px-4 py-2.5 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 [&::-webkit-calendar-picker-indicator]:filter [&::-webkit-calendar-picker-indicator]:invert [&::-webkit-calendar-picker-indicator]:cursor-pointer" 
                        />
                    </div>
                    <div class="flex gap-3">
                        <button type="submit" class="inline-flex items-center rounded-xl bg-slate-800 px-5 py-2.5 text-sm font-semibold text-slate-100 border border-slate-700 hover:border-slate-600 hover:bg-slate-700 transition">
                            Terapkan
                        </button>
                        <a href="{{ route('admin.absensi.manual.show', $kelas->id) }}" class="inline-flex items-center rounded-xl px-5 py-2.5 text-sm font-semibold text-slate-300 border border-slate-700 hover:border-slate-500">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Tabel Siswa --}}
            <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 shadow-2xl shadow-black/40">
                <div class="px-6 py-4 border-b border-slate-800 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-50">Daftar Siswa</h3>
                        <p class="text-sm text-slate-400">Klik status yang sesuai untuk setiap siswa, lalu simpan.</p>
                    </div>
                    {{-- Legenda Kecil --}}
                    <div class="flex flex-wrap gap-2 text-xs text-slate-400">
                        <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Hadir</span>
                        <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-500"></span> Sakit</span>
                        <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-sky-500"></span> Izin</span>
                        <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-rose-500"></span> Alpha</span>
                    </div>
                </div>

                @if ($siswaKelas->isEmpty())
                    <div class="p-10 text-center">
                        <p class="text-slate-400">Belum ada siswa pada kelas ini.</p>
                    </div>
                @else
                    <form method="POST" action="{{ route('admin.absensi.manual.store') }}" class="space-y-0">
                        @csrf
                        <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
                        <input type="hidden" name="tanggal" value="{{ $tanggalDipilih }}">

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                <thead class="bg-slate-900/60">
                                    <tr>
                                        <th class="px-4 py-3 text-center font-semibold text-slate-400 uppercase tracking-wide w-12">No</th>
                                        <th class="px-4 py-3 text-center font-semibold text-slate-400 uppercase tracking-wide w-32">NIS</th>
                                        <th class="px-4 py-3 text-left font-semibold text-slate-400 uppercase tracking-wide">Nama Siswa</th>
                                        <th class="px-4 py-3 text-center font-semibold text-slate-400 uppercase tracking-wide">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-slate-950/40">
                                    @foreach ($siswaKelas as $index => $siswa)
                                        @php
                                            // Ambil status saat ini (jika ada) untuk menentukan mana yang 'checked'
                                            $currentStatus = old('absensi.' . $siswa->id, optional($kehadiranMap->get($siswa->id))->status);
                                        @endphp
                                        <tr class="hover:bg-slate-900/40 transition-colors">
                                            <td class="px-4 py-4 text-center text-slate-400">{{ $loop->iteration }}</td>
                                            <td class="px-4 py-4 text-center text-slate-200 font-medium">{{ $siswa->nis }}</td>
                                            <td class="px-4 py-4 text-left text-slate-100 font-semibold">{{ $siswa->nama_siswa }}</td>
                                            <td class="px-4 py-4 text-center">
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach ($statusOptions as $status)
                                                        @php
                                                            $inputId = 'status-' . $siswa->id . '-' . strtolower($status);
                                                        @endphp
                                                        <div class="relative">
                                                            {{-- Input Radio dengan class 'peer' --}}
                                                            <input type="radio" 
                                                                   id="{{ $inputId }}" 
                                                                   name="absensi[{{ $siswa->id }}]" 
                                                                   value="{{ $status }}" 
                                                                   class="sr-only peer" 
                                                                   {{ $currentStatus === $status ? 'checked' : '' }}>
                                                            
                                                            {{-- Label dengan class 'peer-checked' --}}
                                                            <label for="{{ $inputId }}" 
                                                                   class="cursor-pointer rounded-full px-4 py-1.5 text-xs font-bold border transition-all duration-200 ease-in-out
                                                                          {{ $baseColors[$status] }} 
                                                                          {{ $checkedColors[$status] }}
                                                                          peer-checked:shadow-[0_0_15px_rgba(0,0,0,0.3)]
                                                                          peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-offset-slate-900">
                                                                {{ $status }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="px-6 py-5 border-t border-slate-800 bg-slate-900/70 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between sticky bottom-0 backdrop-blur-md z-10">
                            <p class="text-sm text-slate-400 hidden sm:block">Pastikan semua status sudah sesuai sebelum menyimpan.</p>
                            <button type="submit" class="inline-flex w-full sm:w-auto items-center justify-center rounded-xl bg-gradient-to-r from-sky-600 to-indigo-600 px-8 py-3 text-sm font-bold text-white shadow-lg shadow-sky-900/20 hover:from-sky-500 hover:to-indigo-500 hover:scale-[1.02] transition-all duration-200 focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 focus:ring-offset-slate-900">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>