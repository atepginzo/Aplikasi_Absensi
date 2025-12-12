<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm text-slate-400">Selamat datang kembali,</p>
            <h2 class="font-bold text-2xl text-slate-50 leading-tight">Dashboard Wali Kelas</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid gap-6 sm:grid-cols-2">
                <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6 shadow-lg shadow-black/30">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Akun</p>
                    <h3 class="text-lg font-semibold text-slate-50 mt-2">{{ $user->name }}</h3>
                    <p class="text-sm text-slate-400">{{ $user->email }}</p>
                    <div class="mt-4 text-sm text-slate-400">
                        Peran: <span class="text-sky-400 font-semibold">Wali Kelas</span>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6 shadow-lg shadow-black/30">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Tautan Cepat</p>
                    <div class="mt-4 space-y-3">
                        <a href="{{ route('admin.absensi.scan') }}" class="flex items-center justify-between rounded-xl border border-slate-800 bg-slate-950/60 px-4 py-3 text-sm font-semibold text-slate-200 hover:border-sky-500/40 hover:text-sky-300 transition">
                            <span>Mode Scan Kehadiran</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                        <a href="{{ route('admin.absensi.gun') }}" class="flex items-center justify-between rounded-xl border border-slate-800 bg-slate-950/60 px-4 py-3 text-sm font-semibold text-slate-200 hover:border-sky-500/40 hover:text-sky-300 transition">
                            <span>Gun Scanner (Alat)</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-4-4 4 4-4 4" /></svg>
                        </a>
                        <a href="{{ route('admin.absensi.manual.index') }}" class="flex items-center justify-between rounded-xl border border-slate-800 bg-slate-950/60 px-4 py-3 text-sm font-semibold text-slate-200 hover:border-sky-500/40 hover:text-sky-300 transition">
                            <span>Input Manual Kehadiran</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                        <a href="{{ route('wali.laporan.index') }}" class="flex items-center justify-between rounded-xl border border-slate-800 bg-slate-950/60 px-4 py-3 text-sm font-semibold text-slate-200 hover:border-sky-500/40 hover:text-sky-300 transition">
                            <span>Laporan Kehadiran</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6 shadow-lg shadow-black/30">
                <h3 class="text-lg font-semibold text-slate-50 mb-2">Panduan Singkat</h3>
                <ol class="list-decimal list-inside text-sm text-slate-400 space-y-2">
                    <li>Buka menu <strong>Scanner</strong> untuk merekam kehadiran otomatis.</li>
                    <li>Jika ada perubahan status, gunakan <strong>Input Manual</strong>.</li>
                    <li>Lihat rekap lengkap per tanggal melalui menu <strong>Laporan</strong>.</li>
                </ol>
            </div>
        </div>
    </div>
</x-app-layout>
