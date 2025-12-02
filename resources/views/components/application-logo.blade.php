<div {{ $attributes->merge(['class' => 'relative flex items-center justify-center w-32 h-32 bg-white/10 dark:bg-slate-800/50 backdrop-blur-md rounded-[2rem] shadow-2xl shadow-indigo-500/ border border-white/20 dark:border-slate-700/50 mt-6 group overflow']) }}>

    {{-- Gambar Logo --}}
        <img src="{{ asset('logo.png') }}" 
            alt="Logo PKBM RIDHO" 
            class="relative z-10 w-20 h-auto object-contain rounded-2xl shadow-2xl shadow-sky-500/20 ring-1 ring-white/30 transform group-hover:scale-110 group-hover:rotate-3 transition-all duration-500 ease-out" 
    />
</div>