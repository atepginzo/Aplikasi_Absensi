<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PKBM RIDHO') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-neutral-950 text-slate-50">
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="w-full max-w-md">
                <div class="text-center mb-8">
                    <a href="/" class="inline-block">
                        <x-application-logo class="w-20 h-20 mx-auto" />
                    </a>
                    <h1 class="mt-4 text-2xl font-extrabold tracking-[0.3em] text-slate-50 uppercase">{{ config('app.name', 'PKBM RIDHO') }}</h1>
                    <p class="mt-2 text-slate-400">Sistem Absensi Digital PKBM</p>
                </div>

                <div class="bg-slate-900 rounded-2xl shadow-lg border border-slate-800 p-8">
                    {{ $slot }}
                </div>
        </div>
    </body>
</html>
