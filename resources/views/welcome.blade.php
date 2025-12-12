<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PKBM RIDHO &mdash; Sistem Absensi Digital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }

        .blob-gradient {
            background: radial-gradient(circle at 30% 30%, rgba(14,165,233,0.35), transparent 60%),
                        radial-grSansadient(circle at 70% 40%, rgba(37,99,235,0.3), transparent 55%),
                        radial-gradient(circle at 55% 75%, rgba(14,116,144,0.25), transparent 60%);
        }
        .floating {
            animation: float 12s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-14px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="bg-gray-950 text-slate-100 antialiased overflow-x-hidden" x-data="{ mobileMenu:false }">
    <!-- Backdrop decorative blobs -->
    <div class="fixed inset-0 -z-10 blob-gradient opacity-40"></div>
    <div class="fixed inset-0 -z-10 backdrop-blur-[120px]"></div>

    <!-- Navbar -->
    <header class="fixed top-0 w-full z-50 border-b border-white/5 bg-slate-950/60 backdrop-blur-md">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center gap-3">
                    <div class="p-1 rounded-2xl bg-slate-900/80 border border-white/10 shadow-lg shadow-sky-900/30">
                        <img src="{{ asset('logo.png') }}" alt="PKBM RIDHO" class="w-12 h-12 rounded-xl bg-slate-950/60 object-contain p-2">
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-white tracking-tight leading-tight">PKBM RIDHO</h1>
                        <p class="text-[11px] text-slate-400 font-semibold tracking-[0.2em] uppercase">School System</p>
                    </div>
                </div>
                <div class="hidden md:flex items-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-slate-700 text-sm font-semibold text-slate-100 hover:border-sky-500/70 transition">
                            Buka Dashboard
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-gradient-to-r from-sky-500 via-indigo-500 to-sky-400 text-sm font-semibold text-white shadow-lg shadow-sky-500/30 hover:shadow-xl hover:-translate-y-0.5 transition">
                            Masuk / Login
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    @endauth
                </div>
                <button class="md:hidden p-2 rounded-xl border border-white/10" @click="mobileMenu = !mobileMenu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
            <div class="md:hidden" x-show="mobileMenu" x-transition>
                <div class="pb-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="block w-full text-center px-4 py-3 rounded-2xl border border-slate-800 text-slate-200 font-semibold">Buka Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="block w-full text-center px-4 py-3 rounded-2xl bg-gradient-to-r from-sky-500 to-indigo-500 font-semibold">Masuk / Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <main>
    <section class="relative z-10 pt-24 pb-20 lg:pt-32 lg:pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 text-center">
            
            <!-- Badge -->
            <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-white/5 border border-white/15 text-slate-200 text-xs font-semibold mb-8 shadow-lg shadow-indigo-800/30">
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-sky-500"></span>
                </span>
                <span class="text-sm tracking-wide">Sistem Absensi Digital Terpadu</span>
                <a href="#fitur" class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-gradient-to-r from-sky-500 to-indigo-500 text-white text-[11px] uppercase tracking-[0.2em]">
                    Jelajahi
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <!-- Headline -->
            <h1 class="text-5xl md:text-7xl font-extrabold text-white tracking-tight mb-6 leading-tight">
                Transformasi Digital <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 via-cyan-400 to-blue-600">Administrasi Sekolah</span>
            </h1>

            <!-- Subheadline -->
            <p class="text-lg md:text-xl text-slate-400 max-w-2xl mx-auto mb-10 leading-relaxed">
                Platform manajemen sekolah modern. Tinggalkan kertas, beralih ke absensi QR Code yang cepat, akurat, dan terintegrasi dengan laporan otomatis.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-sky-500 to-blue-600 rounded-2xl text-white font-bold shadow-xl shadow-sky-500/20 hover:shadow-blue-600/30 hover:scale-105 transition-all duration-300 flex items-center justify-center gap-2">
                    Mulai Sekarang
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6"/></svg>
                </a>
                <a href="#fitur" class="w-full sm:w-auto px-8 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-semibold hover:bg-white/10 transition-all flex items-center justify-center gap-2">
                    Pelajari Fitur
                </a>
            </div>

        <!-- Bento Features -->
        </div>
    </section>

    <section id="fitur" class="max-w-6xl mx-auto px-6 pb-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="rounded-[30px] border border-white/5 bg-slate-950/70 p-8 hover:border-sky-500/30 hover:-translate-y-1 transition group">
                    <div class="w-16 h-16 rounded-2xl bg-sky-500/10 border border-sky-500/40 flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10v10H7z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h4v4H3zM17 3h4v4h-4zM3 17h4v4H3zM17 17h4v4h-4z"/></svg>
                    </div>
                    <h3 class="text-2xl font-semibold text-white mb-3">Smart Attendance</h3>
                    <p class="text-slate-400">QR Code unik per siswa, anti titip absen, serta histori yang mudah ditelusuri.</p>
                </div>
                <div class="rounded-[30px] border border-white/5 bg-slate-950/70 p-8 hover:border-indigo-500/30 hover:-translate-y-1 transition group">
                    <div class="w-16 h-16 rounded-2xl bg-indigo-500/10 border border-indigo-500/40 flex items-center justify-center mb-6">
                        <svg class="w-9 h-9 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19h16M4 15l4-8 4 6 4-4 4 6"/></svg>
                    </div>
                    <h3 class="text-2xl font-semibold text-white mb-3">Real-time Monitoring</h3>
                    <p class="text-slate-400">Dashboard admin dan wali kelas menampilkan insight detik itu juga.</p>
                </div>
                <div class="rounded-[30px] border border-white/5 bg-slate-950/70 p-8 hover:border-emerald-500/30 hover:-translate-y-1 transition group">
                    <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 border border-emerald-500/40 flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10M7 3h10M12 3v18"/></svg>
                    </div>
                    <h3 class="text-2xl font-semibold text-white mb-3">Paperless Report</h3>
                    <p class="text-slate-400">Laporan otomatis siap unduh dalam format PDF premium untuk arsip sekolah.</p>
                </div>
                <div class="rounded-[30px] border border-white/5 bg-slate-950/70 p-8 hover:border-fuchsia-500/30 hover:-translate-y-1 transition group">
                    <div class="w-16 h-16 rounded-2xl bg-fuchsia-500/10 border border-fuchsia-500/40 flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-fuchsia-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9h12M6 15h6M6 5h12c1.1 0 2 .9 2 2v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7c0-1.1.9-2 2-2z"/></svg>
                    </div>
                    <h3 class="text-2xl font-semibold text-white mb-3">Multi-Level Access</h3>
                    <p class="text-slate-400">Hak akses terpisah untuk Admin (Pengelola Penuh), Wali Kelas (Monitoring Kelas), dan Orang Tua (Pantau Anak). Data aman dan terstruktur.</p>
                </div>
        </div>
    </section>

        <!-- How It Works -->
    <section class="max-w-6xl mx-auto px-6 pb-24">
        <div class="rounded-[34px] border border-white/5 bg-slate-950/80 p-10">
            <p class="text-sm uppercase tracking-[0.4em] text-slate-300 mb-8">Alur Kerja</p>
            <div class="grid md:grid-cols-4 gap-6">
                @php($steps = [
                    ['title' => 'Input Data Siswa', 'desc' => 'Import Excel atau entry manual tanpa repot.'],
                    ['title' => 'Generate QR Code', 'desc' => 'Tiap siswa otomatis mendapat QR unik.'],
                    ['title' => 'Scan Absensi', 'desc' => 'Guru cukup scan, data langsung tersimpan.'],
                    ['title' => 'Download Laporan', 'desc' => 'PDF siap cetak, lengkap dengan rekap harian.']
                ])
                @foreach ($steps as $index => $step)
                    <div class="relative rounded-3xl border border-white/5 bg-slate-900/60 p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-sky-500 to-indigo-500 text-white flex items-center justify-center text-lg font-semibold">{{ $index + 1 }}</div>
                            <div class="h-px flex-1 bg-gradient-to-r from-slate-700 to-transparent hidden md:block"></div>
                        </div>
                        <h4 class="text-2xl font-semibold text-white mb-2">{{ $step['title'] }}</h4>
                        <p class="text-base text-slate-300 leading-relaxed">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    </main>

    <footer class="border-t border-white/5 bg-slate-950/80">
        <div class="max-w-6xl mx-auto px-6 py-12 grid gap-10 md:grid-cols-3 text-sm text-slate-400">
            <div class="space-y-1">
                <p class="text-white font-semibold text-lg">PKBM RIDHO</p>
                <p class="leading-relaxed">Jl. Kelepu Sanding Kp. Singalombang </p>
                <p class="leading-relaxed">RT/RW 001/004 Desa Sindangsari </p>
                <p class="leading-relaxed">Kec. Paseh, Kab. Bandung - 40395</p>
            </div>
            <div class="space-y-2">
                <p class="text-white font-semibold">Kontak</p>
                <p class="leading-relaxed">pkbmridho2000@gmail.com</p>
                <p class="leading-relaxed">+62 831 1161 2158</p>
            </div>
            <div class="space-y-3">
                <p class="text-white font-semibold">Sosial</p>
                <div class="space-y-2">
                    <div>
                        <a href="#" class="text-slate-300 hover:text-white transition">Instagram</a>
                        <p class="text-xs text-slate-500">@pkbmridho</p>
                    </div>
                    <div>
                        <a href="#" class="text-slate-300 hover:text-white transition">Facebook</a>
                        <p class="text-xs text-slate-500">PKBM Ridho</p>
                    </div>
                    <div>
                        <a href="#" class="text-slate-300 hover:text-white transition">Tiktok</a>
                        <p class="text-xs text-slate-500">PKBMridho</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center text-xs text-slate-500 pb-8">&copy; {{ now()->year }} PKBM RIDHO. All rights reserved.</div>
    </footer>
</body>
</html>