@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full px-4 py-3 rounded-lg text-base font-semibold text-slate-50 bg-gradient-to-r from-sky-500/20 to-sky-400/20 border border-sky-500/30 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 focus:ring-offset-neutral-950 transition duration-150 ease-in-out'
            : 'block w-full px-4 py-3 rounded-lg text-base font-medium text-slate-400 hover:text-slate-50 hover:bg-slate-800 focus:outline-none focus:text-slate-50 focus:bg-slate-800 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
