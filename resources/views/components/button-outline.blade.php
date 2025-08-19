@props(['href' => null])


@if($href)
    <a href="{{ $href }}">
@endif

    <button {{ $attributes->merge([
        'type' => 'submit',
        'class' => 'inline-flex items-center justify-center p-1 border border-slate-600 bg-transparent rounded-md font-semibold text-xs text-slate-600 uppercase text-nowrap
            hover:shadow-md hover:bg-slate-700 hover:text-slate-100 focus:outline-none focus:ring-2 focus:ring-rose-300/50 focus:ring-offset-2 transition ease-in-out duration-200'
        ]) }}>
            {{ $slot }}
    </button>

@if($href)
    </a>
@endif
