<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 Sesi Berakhir - PKBM RIDHO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-200 antialiased overflow-hidden selection:bg-amber-500 selection:text-white">

    <main class="relative min-h-screen flex flex-col items-center justify-center p-6">
        
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-amber-500/10 rounded-full blur-[120px]"></div>
        </div>

        <div class="relative z-10 text-center max-w-2xl w-full">
            
            <h1 class="text-[10rem] md:text-[14rem] font-black leading-none text-slate-900 select-none absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 -z-10 opacity-50">419</h1>
            
            <div class="mb-8 flex justify-center">
                <div class="p-4 bg-slate-900/50 rounded-3xl border border-slate-700/50 shadow-2xl shadow-amber-500/10 backdrop-blur-sm">
                    <svg class="w-16 h-16 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <h2 class="text-3xl md:text-5xl font-bold text-white mb-4 tracking-tight">Sesi Telah Berakhir</h2>
            <p class="text-lg text-slate-400 mb-10 leading-relaxed">
                Halaman kedaluwarsa karena tidak ada aktivitas dalam waktu lama. Demi keamanan data sekolah, silakan login kembali.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-gradient-to-r from-amber-500 to-orange-600 text-white font-bold text-sm hover:scale-105 hover:shadow-lg hover:shadow-amber-500/25 transition-all duration-300">
                    Login Ulang
                </a>
            </div>
        </div>

        <footer class="absolute bottom-6 text-center w-full">
            <p class="text-sm text-slate-600">PKBM RIDHO School System</p>
        </footer>
    </main>
</body>
</html>