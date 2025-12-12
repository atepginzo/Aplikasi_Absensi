<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 Server Error - PKBM RIDHO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-200 antialiased overflow-hidden selection:bg-rose-500 selection:text-white">

    <main class="relative min-h-screen flex flex-col items-center justify-center p-6">
        
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-rose-500/10 rounded-full blur-[120px]"></div>
        </div>

        <div class="relative z-10 text-center max-w-2xl w-full">
            
            <h1 class="text-[10rem] md:text-[14rem] font-black leading-none text-slate-900 select-none absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 -z-10 opacity-50">500</h1>
            
            <div class="mb-8 flex justify-center">
                <div class="p-4 bg-slate-900/50 rounded-3xl border border-slate-700/50 shadow-2xl shadow-rose-500/10 backdrop-blur-sm">
                    <svg class="w-16 h-16 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <h2 class="text-3xl md:text-5xl font-bold text-white mb-4 tracking-tight">Terjadi Kesalahan Server</h2>
            <p class="text-lg text-slate-400 mb-10 leading-relaxed">
                Mohon maaf, terjadi gangguan tak terduga pada server kami. Tim teknis sedang berusaha memperbaikinya. Silakan coba sesaat lagi.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <button onclick="location.reload()" class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-slate-800 text-white font-bold text-sm border border-slate-700 hover:bg-slate-700 transition-all duration-300">
                    Muat Ulang Halaman
                </button>
            </div>
        </div>

        <footer class="absolute bottom-6 text-center w-full">
            <p class="text-sm text-slate-600">PKBM RIDHO School System</p>
        </footer>
    </main>
</body>
</html>