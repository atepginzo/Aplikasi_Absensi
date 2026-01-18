<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-50 leading-tight">Absensi Manual</h2>
                <p class="mt-1 text-sm text-slate-400">Pilih kelas untuk mengisi atau memperbarui status kehadiran siswa.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="GET" action="{{ route('admin.absensi.manual.index') }}" class="mb-6">
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
                    @if ($tahunAktif && request('tahun_ajaran_id') && request('tahun_ajaran_id') != $tahunAktif->id)
                        <a href="{{ route('admin.absensi.manual.index') }}" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-slate-300 rounded-xl border border-slate-700 hover:bg-slate-800 transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 shadow-2xl shadow-black/40 p-8">
                @if ($daftarKelas->isEmpty())
                    <div class="text-center py-16">
                        <div class="mx-auto w-16 h-16 flex items-center justify-center rounded-2xl bg-slate-800 text-slate-500 mb-6">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-100">Belum ada data kelas</h3>
                        <p class="mt-2 text-sm text-slate-400">Tambahkan data kelas terlebih dahulu untuk menggunakan fitur absensi manual.</p>
                    </div>
                @else
                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($daftarKelas as $kelas)
                            <a href="{{ route('admin.absensi.manual.show', $kelas->id) }}"
                               class="group relative rounded-2xl border border-slate-800 bg-slate-900/70 p-6 shadow-lg shadow-black/30 transition-all duration-200 hover:-translate-y-1 hover:border-sky-500/60 hover:shadow-black/50">
                                <div class="flex items-center justify-between">
                                    <div class="inline-flex items-center space-x-2 rounded-full border border-slate-800 bg-slate-800/60 px-3 py-1 text-xs font-medium text-slate-300">
                                        <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <span>{{ $kelas->nama_kelas }}</span>
                                    </div>
                                    <span class="rounded-full bg-sky-500/10 px-3 py-1 text-xs font-semibold text-sky-400 border border-sky-500/30">Input</span>
                                </div>

                                <div class="mt-4 space-y-1">
                                    <p class="text-xs uppercase tracking-wide text-slate-500">Tahun Ajaran</p>
                                    <p class="text-base font-semibold text-slate-50">{{ $kelas->tahunAjaran->tahun_ajaran ?? 'Belum diatur' }}</p>
                                </div>

                                <div class="mt-4 space-y-1">
                                    <p class="text-xs uppercase tracking-wide text-slate-500">Wali Kelas</p>
                                    <p class="text-sm text-slate-200">{{ $kelas->waliKelas->nama_lengkap ?? 'Belum ditetapkan' }}</p>
                                </div>

                                <div class="mt-6 flex items-center justify-between text-xs text-slate-400">
                                    <span>Kelola absensi siswa</span>
                                    <svg class="w-4 h-4 text-slate-400 group-hover:text-sky-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
