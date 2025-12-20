<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-100 leading-tight">
                    {{ __('Data Tahun Ajaran') }}
                </h2>
                <p class="mt-1 text-sm text-slate-400">Kelola data tahun ajaran secara lengkap</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 overflow-hidden shadow-xl" x-data="{ showModal: false, deleteUrl: '' }">
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

                    {{-- Header dengan Tombol Tambah --}}
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-white">Daftar Tahun Ajaran</h3>
                            <p class="text-sm text-slate-400 mt-1">Total: {{ $semuaTahunAjaran->total() }} tahun ajaran</p>
                        </div>
                        <a href="{{ route('admin.tahun-ajaran.create') }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-sky-600 to-sky-500 border border-transparent rounded-xl font-semibold text-sm text-slate-50 hover:from-sky-500 hover:to-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 focus:ring-offset-neutral-950 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Tahun Ajaran
                        </a>
                    </div>

                    {{-- Tabel Data --}}
                    <div class="hidden sm:block">
                        <div class="overflow-x-auto rounded-xl border border-slate-800">
                            <table class="min-w-full divide-y divide-slate-800">
                                <thead class="bg-slate-800/50">
                                    <tr>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">No.</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Tahun Ajaran</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-slate-900/50 divide-y divide-slate-800">
                                    @forelse ($semuaTahunAjaran as $key => $tahun)
                                        <tr class="hover:bg-slate-800/50 transition-colors duration-150 {{ $key % 2 == 0 ? 'bg-slate-900/30' : 'bg-slate-900/50' }}">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-sm font-medium text-slate-300">{{ ($semuaTahunAjaran->firstItem() ?? 0) + $key }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-sm font-semibold text-slate-200">{{ $tahun->tahun_ajaran }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($tahun->is_active)
                                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Aktif
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-700/50 text-slate-400 border border-slate-700">
                                                        Non-Aktif
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex items-center space-x-3">
                                                    <a href="{{ route('admin.tahun-ajaran.show', $tahun->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-slate-200 bg-slate-800/70 rounded-lg border border-slate-700 hover:bg-slate-700/70 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        Detail
                                                    </a>
                                                    <a href="{{ route('admin.tahun-ajaran.edit', $tahun->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-slate-300 bg-slate-800/50 rounded-lg hover:bg-slate-700/50 border border-slate-700 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        Edit
                                                    </a>
                                                    <button type="button" 
                                                            @click="deleteUrl = '{{ route('admin.tahun-ajaran.destroy', $tahun->id) }}'; showModal = true"
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
                                            <td colspan="4" class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center">
                                                    <svg class="w-16 h-16 text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <p class="text-sm font-medium text-slate-400">Data tidak ditemukan.</p>
                                                    <p class="text-xs text-slate-500 mt-1">Mulai dengan menambahkan tahun ajaran baru</p>
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
                        @forelse ($semuaTahunAjaran as $key => $tahun)
                            <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-4 shadow-lg">
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <p class="text-xs uppercase tracking-wide text-slate-500">#{{ ($semuaTahunAjaran->firstItem() ?? 0) + $key }}</p>
                                        <p class="text-lg font-semibold text-slate-50">{{ $tahun->tahun_ajaran }}</p>
                                    </div>
                                    @if ($tahun->is_active)
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Aktif</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-slate-800 text-slate-300 border border-slate-700">Non-Aktif</span>
                                    @endif
                                </div>
                                <div class="mt-4 flex flex-col gap-2">
                                    <a href="{{ route('admin.tahun-ajaran.edit', $tahun->id) }}" class="inline-flex items-center justify-center rounded-xl bg-slate-800 px-3 py-2 text-xs font-semibold text-slate-200 border border-slate-700">
                                        Edit Tahun Ajaran
                                    </a>
                                    <button type="button" @click="deleteUrl = '{{ route('admin.tahun-ajaran.destroy', $tahun->id) }}'; showModal = true" class="inline-flex items-center justify-center rounded-xl bg-red-500/10 px-3 py-2 text-xs font-semibold text-red-300 border border-red-500/30">
                                        Hapus Data
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-sm text-slate-400">Belum ada data tahun ajaran.</p>
                            </div>
                        @endforelse
                    </div>

                    @if ($semuaTahunAjaran->hasPages())
                        <div class="px-6 pb-6">
                            <div class="mt-6">
                                {{ $semuaTahunAjaran->links() }}
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Modal Konfirmasi Hapus --}}
                <div x-show="showModal" 
                     style="display: none;" 
                     @keydown.escape.window="showModal = false" 
                     class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black/70 backdrop-blur-sm"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0">

                    <div @click.away="showModal = false" 
                         class="bg-slate-900/95 backdrop-blur-md rounded-2xl border border-slate-800 shadow-2xl w-full max-w-md mx-4 transform transition-all"
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
                                    <h3 class="text-lg font-bold text-white">Konfirmasi Hapus</h3>
                                    <p class="text-sm text-slate-400 mt-1">Tindakan ini tidak dapat dibatalkan</p>
                                </div>
                            </div>
                            <p class="text-sm text-slate-300 mb-6">
                                Apakah Anda yakin ingin menghapus data tahun ajaran ini? Data yang dihapus tidak dapat dikembalikan.
                            </p>

                            <form :action="deleteUrl" method="POST" class="mt-4">
                                @csrf
                                @method('DELETE')
                                <div class="flex justify-end space-x-3">
                                    <button type="button" 
                                            @click="showModal = false" 
                                            class="inline-flex items-center px-4 py-2.5 bg-slate-800 border-2 border-slate-700 rounded-xl font-semibold text-sm text-slate-200 hover:border-slate-600 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition-all duration-200">
                                        Batal
                                    </button>
                                    <button type="submit" 
                                            class="inline-flex items-center px-4 py-2.5 bg-red-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition-all duration-200">
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
