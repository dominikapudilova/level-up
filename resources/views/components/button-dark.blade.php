@props(['href' => null])

{{--<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex items-center justify-center px-4 py-3 border border-transparent rounded-md font-semibold text-xs text-white uppercase text-nowrap bg-gradient-dark
        hover:scale-105 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-rose-300/50 focus:ring-offset-2 transition ease-in-out duration-200'
    ]) }}>
    @if($href)
        <a href="{{ $href }}" class="w-full h-full flex items-center justify-center">
            {{ $slot }}
        </a>
    @else
        {{ $slot }}
    @endif
</button>--}}

@if($href)
<a href="{{ $href }}" class="">
@endif
    <button {{ $attributes->merge([
        'type' => 'submit',
        'class' => 'inline-flex items-center justify-center px-4 py-3 border border-transparent rounded-md font-semibold text-xs text-white uppercase text-nowrap tracking-wide bg-gradient-dark
            hover:scale-105 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-rose-300/50 focus:ring-offset-2 transition ease-in-out duration-200'
        ]) }}>
            {{ $slot }}
    </button>
@if($href)
</a>
@endif
