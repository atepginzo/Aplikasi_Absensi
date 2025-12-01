@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-4 py-2 text-sm font-semibold leading-5 text-slate-50 bg-gradient-to-r from-sky-500/20 to-sky-400/20 rounded-lg border border-sky-500/30 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 focus:ring-offset-neutral-950 transition duration-150 ease-in-out shadow-lg shadow-sky-500/20'
            : 'inline-flex items-center px-4 py-2 text-sm font-medium leading-5 text-slate-400 hover:text-slate-50 hover:bg-slate-800 rounded-lg focus:outline-none focus:text-slate-50 focus:bg-slate-800 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
