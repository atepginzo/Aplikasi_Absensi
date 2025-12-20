<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-50 leading-tight">
                    {{ __('Data Siswa') }}
                </h2>
                <p class="mt-1 text-sm text-slate-400">Kelola data siswa secara lengkap</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-900 backdrop-blur-sm rounded-2xl border border-slate-800 overflow-hidden shadow-xl" x-data="{ showDeleteModal: false, deleteUrl: '', showImportModal: false }">
                <div class="p-6">
                    
                    {{-- Tampilkan pesan sukses --}}
                    @if (session('success'))
                        <div class="mb-6 p-4 bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 rounded-xl flex items-center backdrop-blur-sm">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/30 text-rose-200 rounded-xl flex items-start backdrop-blur-sm">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.455 19h13.09a2 2 0 001.732-3L13.732 5a2 2 0 00-3.464 0L3.723 16a2 2 0 001.732 3z" />
                            </svg>
                            <div>
                                <p class="font-semibold">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif
                    @if (session('import_failures'))
                        <div class="mb-6 p-4 bg-amber-500/10 border border-amber-500/30 text-amber-200 rounded-xl backdrop-blur-sm">
                            <p class="font-semibold mb-2">Beberapa baris dilewati:</p>
                            <ul class="text-sm space-y-1 text-amber-100">
                                @foreach (session('import_failures') as $failure)
                                    <li>Baris {{ $failure['row'] ?? '-' }}: {{ implode(', ', $failure['errors'] ?? []) }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Header dengan Tombol Tambah --}}
                    <div class="flex items-center justify-between mb-6 flex-wrap gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-50">Daftar Siswa</h3>
                            <p class="text-sm text-slate-400 mt-1">Total: {{ $semuaSiswa->total() }} siswa</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="button" @click="showImportModal = true" class="inline-flex items-center px-5 py-2.5 rounded-xl border border-slate-700 text-sm font-semibold text-slate-100 bg-slate-900/70 hover:bg-slate-800 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Import Excel
                            </button>
                            <a href="{{ route('admin.siswa.create') }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-sky-600 to-sky-500 border border-transparent rounded-xl font-semibold text-sm text-slate-50 hover:from-sky-500 hover:to-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 focus:ring-offset-neutral-950 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah Siswa
                            </a>
                        </div>
                    </div>

                    {{-- Filter Kelas --}}
                    <form method="GET" action="{{ route('admin.siswa.index') }}" class="mb-6">
                        <div class="flex flex-col sm:flex-row sm:items-end gap-4 bg-slate-900/60 border border-slate-800 rounded-2xl p-4">
                            <div class="w-full sm:w-1/3">
                                <label for="kelas_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">Pilih Kelas</label>
                                <select name="kelas_id" id="kelas_id" class="w-full rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-100 focus:border-sky-500 focus:ring-sky-500">
                                    <option value="">Semua Kelas</option>
                                    @foreach ($semuaKelas as $kelas)
                                        <option value="{{ $kelas->id }}" {{ (string) $kelas->id === (string) ($kelasDipilih ?? '') ? 'selected' : '' }}>
                                            {{ $kelas->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex gap-3">
                                <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-sky-600 text-sm font-semibold text-white rounded-xl border border-sky-500 hover:bg-sky-500 transition">
                                    Filter
                                </button>
                                @if (request('kelas_id'))
                                    <a href="{{ route('admin.siswa.index') }}" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-slate-300 rounded-xl border border-slate-700 hover:bg-slate-800 transition">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>

                        {{-- Tabel Data untuk layar menengah ke atas --}}
                        <div class="hidden sm:block">
                            <div class="overflow-x-auto rounded-xl border border-slate-800">
                                <table class="min-w-full divide-y divide-slate-800">
                                    <thead class="bg-slate-800/50">
                                        <tr>
                                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">No.</th>
                                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Kelas</th>
                                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">NIS</th>
                                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Nama Siswa</th>
                                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Jenis Kelamin</th>
                                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Nama Wali</th>
                                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-slate-900/50 divide-y divide-slate-800">
                                        @forelse ($semuaSiswa as $key => $siswa)
                                            <tr class="hover:bg-slate-800 transition-colors duration-150">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="text-sm font-medium text-slate-300">{{ ($semuaSiswa->firstItem() ?? 0) + $key }}</span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-800 text-sky-400 border border-slate-800">
                                                        {{ $siswa->kelas->nama_kelas ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="text-sm text-slate-50 font-medium">{{ $siswa->nis }}</span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="text-sm text-slate-50 font-medium">{{ $siswa->nama_siswa }}</span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium {{ $siswa->jenis_kelamin == 'L' ? 'bg-slate-800 text-sky-400 border border-slate-800' : 'bg-pink-500/20 text-pink-300 border border-pink-500/30' }}">
                                                        {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="text-sm text-slate-400">{{ $siswa->kelas->waliKelas->nama_lengkap ?? 'N/A' }}</span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex items-center space-x-3">
                                                        <a href="{{ route('admin.siswa.show', $siswa->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-sky-400 bg-slate-800 rounded-lg hover:bg-slate-700 border border-slate-800 transition-colors">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                            </svg>
                                                            Detail
                                                        </a>
                                                        <a href="{{ route('admin.siswa.edit', $siswa->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-slate-300 bg-slate-800 rounded-lg hover:bg-slate-700 border border-slate-700 transition-colors">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                            </svg>
                                                            Edit
                                                        </a>
                                                            <button type="button" 
                                                                @click="deleteUrl = '{{ route('admin.siswa.destroy', $siswa->id) }}'; showDeleteModal = true"
                                                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-400 bg-red-500/10 rounded-lg hover:bg-red-500/20 border border-red-500/30 transition-colors">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                            Hapus
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="px-6 py-12 text-center">
                                                    <div class="flex flex-col items-center">
                                                        <svg class="w-16 h-16 text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                        </svg>
                                                        <p class="text-sm font-medium text-slate-400">Data tidak ditemukan.</p>
                                                        <p class="text-xs text-slate-500 mt-1">Mulai dengan menambahkan siswa baru</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Tampilan card untuk layar kecil --}}
                        <div class="sm:hidden space-y-4">
                            @forelse ($semuaSiswa as $key => $siswa)
                                <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-4 shadow-lg">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-xs uppercase tracking-wide text-slate-500">#{{ ($semuaSiswa->firstItem() ?? 0) + $key }}</span>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-800 text-sky-300 border border-slate-700">
                                            {{ $siswa->kelas->nama_kelas ?? 'N/A' }}
                                        </span>
                                    </div>
                                    <div class="space-y-2 text-sm text-slate-300">
                                        <div>
                                            <p class="text-slate-500 text-xs">Nama</p>
                                            <p class="text-base font-semibold text-slate-50">{{ $siswa->nama_siswa }}</p>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <p class="text-slate-500 text-xs">NIS</p>
                                                <p class="font-medium">{{ $siswa->nis }}</p>
                                            </div>
                                            <div>
                                                <p class="text-slate-500 text-xs">Jenis Kelamin</p>
                                                <p class="font-medium">{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-slate-500 text-xs">Wali Kelas</p>
                                            <p>{{ $siswa->kelas->waliKelas->nama_lengkap ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        <a href="{{ route('admin.siswa.show', $siswa->id) }}" class="flex-1 inline-flex items-center justify-center rounded-xl bg-slate-800 px-3 py-2 text-xs font-semibold text-sky-300 border border-slate-700">
                                            Detail
                                        </a>
                                        <a href="{{ route('admin.siswa.edit', $siswa->id) }}" class="flex-1 inline-flex items-center justify-center rounded-xl bg-slate-800 px-3 py-2 text-xs font-semibold text-slate-200 border border-slate-700">
                                            Edit
                                        </a>
                                        <button type="button" @click="deleteUrl = '{{ route('admin.siswa.destroy', $siswa->id) }}'; showDeleteModal = true" class="w-full inline-flex items-center justify-center rounded-xl bg-red-500/10 px-3 py-2 text-xs font-semibold text-red-300 border border-red-500/30">
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <p class="text-sm text-slate-400">Belum ada data siswa.</p>
                                </div>
                            @endforelse
                        </div>

                        @if ($semuaSiswa->hasPages())
                            <div class="px-6 pb-6">
                                <div class="mt-6">
                                    {{ $semuaSiswa->links() }}
                                </div>
                            </div>
                        @endif
                    </div>

                {{-- Modal Import Excel --}}
                <div x-show="showImportModal"
                     style="display: none;"
                     @keydown.escape.window="showImportModal = false"
                     class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black/70 backdrop-blur-sm"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0">
                    <div @click.away="showImportModal = false"
                         class="bg-slate-900 backdrop-blur-md rounded-2xl border border-slate-800 shadow-2xl w-full max-w-lg mx-4 transform transition-all"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95">
                        <div class="p-6 space-y-5">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl bg-sky-500/15 border border-sky-500/30 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-50">Import Data Siswa</h3>
                                    <p class="text-sm text-slate-400">Gunakan template agar format kolom sesuai.</p>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-slate-800 bg-slate-950/60 p-4">
                                <div class="flex items-center justify-between gap-3 flex-wrap">
                                    <div>
                                        <p class="text-sm text-slate-200 font-semibold">Template Excel</p>
                                        <p class="text-xs text-slate-400">Unduh contoh pengisian dengan header yang benar.</p>
                                    </div>
                                    <a href="{{ route('admin.siswa.template') }}" class="inline-flex items-center px-4 py-2 rounded-xl border border-slate-700 text-xs font-semibold text-slate-100 hover:bg-slate-800 transition">
                                        Download Template
                                    </a>
                                </div>
                            </div>
                            <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="file_import" class="block text-sm font-semibold text-slate-200 mb-2">Pilih File Excel</label>
                                    <input type="file" name="file_import" id="file_import" accept=".xlsx,.xls,.csv" class="w-full rounded-xl border border-slate-800 bg-slate-950 text-slate-100 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-sky-600 file:text-white file:text-sm hover:file:bg-sky-500" required>
                                    <p class="text-xs text-slate-500 mt-2">Kolom wajib: nama_siswa, nis, jenis_kelamin, kelas, email_ortu (opsional).</p>
                                    @error('file_import')
                                        <p class="text-xs text-rose-300 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="flex justify-end gap-3">
                                    <button type="button" @click="showImportModal = false" class="inline-flex items-center px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-sm font-semibold text-slate-100 hover:bg-slate-700 transition">
                                        Batal
                                    </button>
                                    <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-sky-600 rounded-xl text-sm font-semibold text-white hover:bg-sky-500 transition">
                                        Upload &amp; Proses
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Modal Konfirmasi Hapus --}}
                <div x-show="showDeleteModal" 
                     style="display: none;" 
                     @keydown.escape.window="showDeleteModal = false" 
                     class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black/70 backdrop-blur-sm"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0">

                    <div @click.away="showDeleteModal = false" 
                         class="bg-slate-900 backdrop-blur-md rounded-2xl border border-slate-800 shadow-2xl w-full max-w-md mx-4 transform transition-all"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95">
                        
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-500/20 border border-red-500/30 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-bold text-slate-50">Konfirmasi Hapus</h3>
                                    <p class="text-sm text-slate-400 mt-1">Tindakan ini tidak dapat dibatalkan</p>
                                </div>
                            </div>
                            <p class="text-sm text-slate-300 mb-6">
                                Apakah Anda yakin ingin menghapus data siswa ini? Data yang dihapus tidak dapat dikembalikan.
                            </p>

                            <form :action="deleteUrl" method="POST" class="mt-4">
                                @csrf
                                @method('DELETE')
                                <div class="flex justify-end space-x-3">
                                        <button type="button" 
                                            @click="showDeleteModal = false" 
                                            class="inline-flex items-center px-4 py-2.5 bg-slate-800 border-2 border-slate-700 rounded-xl font-semibold text-sm text-slate-50 hover:border-slate-600 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 focus:ring-offset-neutral-950 transition-all duration-200">
                                        Batal
                                    </button>
                                    <button type="submit" 
                                            class="inline-flex items-center px-4 py-2.5 bg-red-600 border border-transparent rounded-xl font-semibold text-sm text-slate-50 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-neutral-950 transition-all duration-200">
                                        Yakin, Hapus
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
