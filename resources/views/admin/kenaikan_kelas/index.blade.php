<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-50 leading-tight">
                    {{ __('Kenaikan Kelas Massal') }}
                </h2>
                <p class="mt-1 text-sm text-slate-400">Pindahkan siswa ke kelas baru secara massal</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Pesan Sukses --}}
            @if (session('success'))
                <div class="p-4 bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 rounded-xl flex items-center backdrop-blur-sm">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Pesan Error --}}
            @if ($errors->any())
                <div class="p-4 bg-rose-500/10 border border-rose-500/30 text-rose-200 rounded-xl backdrop-blur-sm">
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Card Filter: Pilih Kelas Asal --}}
            <div class="bg-slate-900 backdrop-blur-sm rounded-2xl border border-slate-800 overflow-hidden shadow-xl">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-slate-50 mb-4">Langkah 1: Pilih Kelas Asal</h3>
                    <form method="GET" action="{{ route('admin.kenaikan-kelas.index') }}">
                        <div class="flex flex-col sm:flex-row sm:items-end gap-4">
                            <div class="w-full sm:w-1/3">
                                <label for="kelas_asal_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">Kelas Asal</label>
                                <select name="kelas_asal_id" id="kelas_asal_id" class="w-full rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-100 focus:border-sky-500 focus:ring-sky-500">
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach ($semuaKelas as $kelas)
                                        <option value="{{ $kelas->id }}" {{ (string) $kelas->id === (string) $kelasAsalId ? 'selected' : '' }}>
                                            {{ $kelas->nama_kelas }} ({{ $kelas->tahunAjaran->tahun_ajaran ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex gap-3">
                                <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-sky-600 to-sky-500 border border-transparent rounded-xl font-semibold text-sm text-slate-50 hover:from-sky-500 hover:to-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 focus:ring-offset-neutral-950 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    Tampilkan Siswa
                                </button>
                                @if ($kelasAsalId)
                                    <a href="{{ route('admin.kenaikan-kelas.index') }}" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-slate-300 rounded-xl border border-slate-700 hover:bg-slate-800 transition">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Card Daftar Siswa (Muncul jika kelas dipilih) --}}
            @if ($kelasAsalId && $daftarSiswa->count() > 0)
                <div class="bg-slate-900 backdrop-blur-sm rounded-2xl border border-slate-800 overflow-hidden shadow-xl" x-data="{ 
                    selectedSiswa: [],
                    selectAll: false,
                    showConfirmModal: false,
                    showWarningModal: false,
                    warningMessage: '',
                    toggleSelectAll() {
                        if (this.selectAll) {
                            this.selectedSiswa = {{ json_encode($daftarSiswa->pluck('id')->toArray()) }};
                        } else {
                            this.selectedSiswa = [];
                        }
                    },
                    updateSelectAll() {
                        this.selectAll = this.selectedSiswa.length === {{ $daftarSiswa->count() }};
                    },
                    openConfirmModal() {
                        if (this.selectedSiswa.length === 0) {
                            this.warningMessage = 'Pilih minimal satu siswa untuk dipindahkan.';
                            this.showWarningModal = true;
                            return;
                        }
                        const kelasTujuan = document.getElementById('kelas_tujuan_id');
                        if (!kelasTujuan.value) {
                            this.warningMessage = 'Pilih kelas tujuan terlebih dahulu.';
                            this.showWarningModal = true;
                            return;
                        }
                        this.showConfirmModal = true;
                    },
                    submitForm() {
                        document.getElementById('kenaikanKelasForm').submit();
                    }
                }">
                    <form id="kenaikanKelasForm" method="POST" action="{{ route('admin.kenaikan-kelas.store') }}">
                        @csrf
                        <div class="p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-50">Langkah 2: Pilih Siswa & Kelas Tujuan</h3>
                                    <p class="text-sm text-slate-400 mt-1">Ditemukan {{ $daftarSiswa->count() }} siswa di kelas ini</p>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-3 sm:items-end">
                                    <div>
                                        <label for="kelas_tujuan_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">Kelas Tujuan</label>
                                        <select name="kelas_tujuan_id" id="kelas_tujuan_id" required class="w-full sm:w-64 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-100 focus:border-sky-500 focus:ring-sky-500">
                                            <option value="">-- Pilih Kelas Tujuan --</option>
                                            @foreach ($semuaKelas as $kelas)
                                                @if ($kelas->id != $kelasAsalId)
                                                    <option value="{{ $kelas->id }}">
                                                        {{ $kelas->nama_kelas }} ({{ $kelas->tahunAjaran->tahun_ajaran ?? 'N/A' }})
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Tabel Siswa dengan Checkbox --}}
                            <div class="overflow-x-auto rounded-xl border border-slate-800">
                                <table class="min-w-full divide-y divide-slate-800">
                                    <thead class="bg-slate-800/50">
                                        <tr>
                                            <th scope="col" class="px-4 py-3 text-center">
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" 
                                                           x-model="selectAll" 
                                                           @change="toggleSelectAll()"
                                                           class="rounded border-slate-600 bg-slate-800 text-sky-500 focus:ring-sky-500 focus:ring-offset-slate-900">
                                                    <span class="ml-2 text-xs font-semibold text-slate-300 uppercase tracking-wider">Pilih Semua</span>
                                                </label>
                                            </th>
                                            <th scope="col" class="px-4 py-3 text-center text-xs font-semibold text-slate-300 uppercase tracking-wider">No.</th>
                                            <th scope="col" class="px-4 py-3 text-center text-xs font-semibold text-slate-300 uppercase tracking-wider">NIS</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Nama Siswa</th>
                                            <th scope="col" class="px-4 py-3 text-center text-xs font-semibold text-slate-300 uppercase tracking-wider">Jenis Kelamin</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-slate-900/50 divide-y divide-slate-800">
                                        @foreach ($daftarSiswa as $key => $siswa)
                                            <tr class="hover:bg-slate-800 transition-colors duration-150">
                                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                                    <input type="checkbox" 
                                                           name="siswa_ids[]" 
                                                           value="{{ $siswa->id }}"
                                                           x-model="selectedSiswa"
                                                           :value="{{ $siswa->id }}"
                                                           @change="updateSelectAll()"
                                                           class="rounded border-slate-600 bg-slate-800 text-sky-500 focus:ring-sky-500 focus:ring-offset-slate-900">
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                                    <span class="text-sm font-medium text-slate-300">{{ $key + 1 }}</span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                                    <span class="text-sm text-slate-50 font-medium">{{ $siswa->nis }}</span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-left">
                                                    <span class="text-sm text-slate-50 font-medium">{{ $siswa->nama_siswa }}</span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium {{ $siswa->jenis_kelamin == 'L' ? 'bg-slate-800 text-sky-400 border border-slate-800' : 'bg-pink-500/20 text-pink-300 border border-pink-500/30' }}">
                                                        {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Tombol Submit --}}
                            <div class="mt-6 flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <a href="{{ route('admin.siswa.index') }}" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-slate-300 rounded-xl border border-slate-700 hover:bg-slate-800 transition">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                        </svg>
                                        Kembali
                                    </a>
                                    <p class="text-sm text-slate-400">
                                        <span x-text="selectedSiswa.length"></span> siswa dipilih
                                    </p>
                                </div>
                                <button type="button" 
                                        @click="openConfirmModal()"
                                        :disabled="selectedSiswa.length === 0"
                                        :class="selectedSiswa.length === 0 ? 'opacity-50 cursor-not-allowed' : 'hover:from-emerald-500 hover:to-emerald-400 hover:shadow-xl transform hover:scale-105'"
                                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-500 border border-transparent rounded-xl font-semibold text-sm text-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-neutral-950 shadow-lg transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                    Pindahkan Siswa Terpilih
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- Modal Konfirmasi Pindah Kelas --}}
                    <div x-show="showConfirmModal" 
                         style="display: none;" 
                         @keydown.escape.window="showConfirmModal = false" 
                         class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black/70 backdrop-blur-sm"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0">

                        <div @click.away="showConfirmModal = false" 
                             class="bg-slate-900 backdrop-blur-md rounded-2xl border border-slate-800 shadow-2xl w-full max-w-md mx-4 transform transition-all"
                             x-transition:enter="ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="ease-in duration-200"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95">
                            
                            <div class="p-6">
                                <div class="flex items-center mb-4">
                                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-amber-500/20 border border-amber-500/30 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-bold text-slate-50">Konfirmasi Kenaikan Kelas</h3>
                                        <p class="text-sm text-slate-400 mt-1">Pastikan data sudah benar</p>
                                    </div>
                                </div>
                                <div class="mb-6 p-4 bg-slate-800/50 rounded-xl border border-slate-700">
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-slate-400">Jumlah Siswa:</span>
                                            <span class="text-slate-100 font-semibold" x-text="selectedSiswa.length + ' siswa'"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-slate-400">Kelas Tujuan:</span>
                                            <span class="text-sky-400 font-semibold" x-text="document.getElementById('kelas_tujuan_id')?.selectedOptions[0]?.text || '-'"></span>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-sm text-slate-300 mb-6">
                                    Apakah Anda yakin ingin memindahkan siswa terpilih ke kelas tujuan? <span class="text-amber-400">Tindakan ini akan mengubah kelas siswa yang dipilih.</span>
                                </p>

                                <div class="flex justify-end space-x-3">
                                    <button type="button" 
                                            @click="showConfirmModal = false" 
                                            class="inline-flex items-center px-4 py-2.5 bg-slate-800 border-2 border-slate-700 rounded-xl font-semibold text-sm text-slate-50 hover:border-slate-600 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 focus:ring-offset-neutral-950 transition-all duration-200">
                                        Batal
                                    </button>
                                    <button type="button" 
                                            @click="submitForm()"
                                            class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-emerald-500 border border-transparent rounded-xl font-semibold text-sm text-slate-50 hover:from-emerald-500 hover:to-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-neutral-950 transition-all duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Yakin, Pindahkan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Peringatan --}}
                    <div x-show="showWarningModal" 
                         style="display: none;" 
                         @keydown.escape.window="showWarningModal = false" 
                         class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black/70 backdrop-blur-sm"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0">

                        <div @click.away="showWarningModal = false" 
                             class="bg-slate-900 backdrop-blur-md rounded-2xl border border-slate-800 shadow-2xl w-full max-w-sm mx-4 transform transition-all"
                             x-transition:enter="ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="ease-in duration-200"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95">
                            
                            <div class="p-6">
                                <div class="flex items-center mb-4">
                                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-amber-500/20 border border-amber-500/30 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-bold text-slate-50">Peringatan</h3>
                                    </div>
                                </div>
                                <p class="text-sm text-slate-300 mb-6" x-text="warningMessage"></p>
                                <div class="flex justify-end">
                                    <button type="button" 
                                            @click="showWarningModal = false" 
                                            class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-sky-600 to-sky-500 border border-transparent rounded-xl font-semibold text-sm text-white hover:from-sky-500 hover:to-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 focus:ring-offset-neutral-950 transition-all duration-200">
                                        OK
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif ($kelasAsalId && $daftarSiswa->count() === 0)
                {{-- Jika tidak ada siswa di kelas yang dipilih --}}
                <div class="bg-slate-900 backdrop-blur-sm rounded-2xl border border-slate-800 overflow-hidden shadow-xl">
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="text-sm font-medium text-slate-400">Tidak ada siswa di kelas ini.</p>
                        <p class="text-xs text-slate-500 mt-1">Pilih kelas lain atau tambahkan siswa terlebih dahulu</p>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>

