@props(['active' => false, 'variant' => 'primary', 'class' => '', 'href' => ''])

@php
    $classes = [
            'py-2 px-6 rounded text-sm tracking-wide flex items-center gap-2',
            'bg-indigo-500 hover:bg-indigo-600 text-white' => $variant === 'primary',
            'border-2 bg-white border-indigo-300 hover:bg-indigo-50 text-indigo-300' => $variant === 'secondary',
            'bg-red-500 hover:bg-red-600 text-white' => $variant === 'danger',
            'ring-2 ring-indigo-900' => $active,
            $class,
        ];

@endphp

@if(filled($href))
    <a
        href="{{ $href }}"
        {{ $attributes }}
        @class($classes)
    >
        {{ $slot }}
    </a>
@else
    <button
        {{ $attributes }}
        @class($classes)
    >
        <x-heroicon-m-arrow-path class="w-5 h-5 hidden group-[.is-loading]:block animate-spin transition-all"/>

        {{ $slot }}
    </button>
@endif
