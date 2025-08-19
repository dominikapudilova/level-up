@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-block sm:block sm:m-0 my-2 ms-2 sm:py-2 sm:px-2  sm:rounded sm:bg-white sm:shadow-xs border-b-2 sm:border-transparent border-rose-500 leading-5 text-slate-700 transition duration-150 ease-in-out'
            : 'inline-block sm:block sm:m-0 my-2 ms-2 sm:py-2 sm:px-2  sm:rounded hover:border-rose-300 border-b-2 sm:border-0 sm:hover:bg-rose-200 sm:hover:border-rose-300 border-transparent leading-5 text-slate-700 transition duration-150 ease-in-out';
@endphp
{{--border border-transparent--}}
<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
