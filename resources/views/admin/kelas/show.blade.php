<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-400">Detail Kelas</p>
                <h2 class="font-bold text-2xl text-slate-100 leading-tight">
                    {{ $kelas->nama_kelas }}
                </h2>
            </div>
            <a href="{{ route('admin.kelas.index') }}" class="inline-flex items-center px-5 py-2.5 bg-slate-800 border-2 border-slate-700 rounded-xl font-semibold text-sm text-slate-200 hover:border-slate-600 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition-all duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 shadow-xl">
                <div class="p-6 border-b border-slate-800">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div class="p-4 rounded-2xl bg-slate-900/70 border border-slate-800">
                            <p class="text-xs uppercase tracking-wide text-slate-500">Nama Kelas</p>
                            <p class="mt-2 text-xl font-bold text-white">{{ $kelas->nama_kelas }}</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-900/70 border border-slate-800">
                            <p class="text-xs uppercase tracking-wide text-slate-500">Wali Kelas</p>
                            <p class="mt-2 text-lg font-semibold text-white">{{ $kelas->waliKelas->nama_lengkap ?? '-' }}</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-900/70 border border-slate-800">
                            <p class="text-xs uppercase tracking-wide text-slate-500">Tahun Ajaran</p>
                            <p class="mt-2 text-lg font-semibold text-white">{{ $kelas->tahunAjaran->tahun_ajaran ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-white">Daftar Siswa</h3>
                            <p class="text-sm text-slate-400">{{ $daftarSiswa->count() }} siswa terdaftar dalam kelas ini</p>
                        </div>
                    </div>

                    <div class="hidden sm:block">
                        <div class="overflow-x-auto rounded-2xl border border-slate-800">
                            <table class="min-w-full divide-y divide-slate-800">
                                <thead class="bg-slate-800/60">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wide">NIS</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wide">Nama Siswa</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wide">Jenis Kelamin</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-slate-900/60 divide-y divide-slate-800">
                                    @forelse ($daftarSiswa as $siswa)
                                        <tr class="hover:bg-slate-800/40 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-white">{{ $siswa->nis }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">{{ $siswa->nama_siswa }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">{{ $siswa->jenis_kelamin }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-10 text-center text-slate-500">Belum ada siswa di kelas ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="sm:hidden space-y-4">
                        @forelse ($daftarSiswa as $siswa)
                            <div class="p-4 rounded-2xl border border-slate-800 bg-slate-900/70">
                                <p class="text-base font-semibold text-white">{{ $siswa->nama_siswa }}</p>
                                <p class="text-sm text-slate-400">NIS: {{ $siswa->nis }}</p>
                                <p class="text-sm text-slate-400">Jenis Kelamin: {{ $siswa->jenis_kelamin }}</p>
                            </div>
                        @empty
                            <div class="p-6 rounded-2xl border border-slate-800 bg-slate-900/70 text-center text-slate-400">
                                Belum ada siswa di kelas ini.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
