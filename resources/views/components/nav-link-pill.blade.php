{{-- File: resources/views/components/nav-link-pill.blade.php --}}

@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-4 py-2 border-b-2 border-transparent text-sm font-semibold leading-5 text-indigo-700 bg-indigo-100 focus:outline-none focus:bg-indigo-200 transition duration-150 ease-in-out rounded-t-md'
            : 'inline-flex items-center px-4 py-2 border-b-2 border-transparent text-sm font-semibold leading-5 text-gray-500 hover:text-gray-700 hover:bg-gray-50 focus:outline-none focus:text-gray-700 focus:bg-gray-100 transition duration-150 ease-in-out rounded-t-md';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>