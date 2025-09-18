@props(['active'])

@php
$classes = 'relative inline-flex items-center px-3 py-2 text-sm font-medium transition-all duration-300 ease-out group';
$textClasses = ($active ?? false) ? 'text-gray-900' : 'text-gray-600 hover:text-gray-900';
@endphp

<a {{ $attributes->merge(['class' => $classes . ' ' . $textClasses]) }}>
    {{ $slot }}
    <span class="absolute bottom-1 left-1/2 transform -translate-x-1/2 w-1 h-px bg-gray-900 {{ ($active ?? false) ? 'opacity-100' : 'opacity-0' }} group-hover:opacity-100 transition-opacity duration-300 ease-out"></span>
</a>
