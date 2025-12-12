<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk &mdash; PKBM RIDHO</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-950 text-slate-100 min-h-screen">
    <div class="min-h-screen flex">
        <!-- Left panel -->
        <div class="hidden lg:flex lg:w-1/2 relative">
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('/images/gedung-sekolah.jpg') }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/80 to-transparent"></div>
            <div class="relative z-10 mt-auto mb-16 px-16 space-y-0.5">
                <p class="text-sm uppercase tracking-[0.4em] text-slate-200/80">PKBM RIDHO</p>
                <h1 class="text-4xl font-semibold text-white leading-tight">Selamat Datang di </h1>
                <h1 class="text-4xl font-semibold text-white leading-tight">PKBM RIDHO</h1>
                <p class="text-lg text-slate-200/90">Sistem Absensi Digital Terpadu</p>
            </div>
        </div>

        <!-- Right panel -->
        <div class="w-full lg:w-1/2 bg-slate-950 flex items-center justify-center px-6 py-12">
            <div class="w-full max-w-md space-y-8">
                <div class="text-center space-y-3">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-slate-900 border border-white/5 mx-auto">
                        <img src="{{ asset('logo.png') }}" alt="PKBM RIDHO" class="w-10 h-10 object-contain">
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-300">PKBM RIDHO</p>
                        <h2 class="text-2xl font-semibold text-white">Masuk ke Akun Anda</h2>
                        <p class="text-sm text-slate-400">Kelola kehadiran dan administrasi sekolah dengan mudah.</p>
                    </div>
                </div>

                <x-auth-session-status class="text-sm text-center text-emerald-400" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div class="space-y-2">
                        <x-input-label for="email" :value="__('Email')" class="text-slate-300" />
                        <x-text-input
                            id="email"
                            class="block w-full bg-slate-900/80 border border-slate-800 text-slate-50 placeholder-slate-500 focus:border-sky-500 focus:ring-2 focus:ring-sky-600"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="email@example.com"
                        />
                        <x-input-error :messages="$errors->get('email')" class="text-sm text-red-500" />
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <x-input-label for="password" :value="__('Password')" class="text-slate-300" />
                            @if (Route::has('password.request'))
                                <a class="text-sm text-sky-400 hover:text-sky-300 transition" href="{{ route('password.request') }}">
                                    {{ __('Lupa password?') }}
                                </a>
                            @endif
                        </div>
                        <x-text-input
                            id="password"
                            class="block w-full bg-slate-900/80 border border-slate-800 text-slate-50 placeholder-slate-500 focus:border-sky-500 focus:ring-2 focus:ring-sky-600"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                        />
                        <x-input-error :messages="$errors->get('password')" class="text-sm text-red-500" />
                    </div>

                    <div class="flex items-center justify-between text-sm text-slate-400">
                        <label class="inline-flex items-center gap-2">
                            <input
                                id="remember_me"
                                type="checkbox"
                                class="rounded border-slate-700 bg-slate-900 text-sky-500 focus:ring-sky-600"
                                name="remember"
                            >
                            <span>{{ __('Ingat saya') }}</span>
                        </label>
                    </div>

                    <div>
                        <button type="submit" class="w-full bg-gradient-to-r from-sky-500 to-blue-600 text-white font-semibold rounded-2xl px-4 py-3 shadow-lg shadow-sky-900/30 hover:from-sky-400 hover:to-blue-500 transition">
                            {{ __('Masuk') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
