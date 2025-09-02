@props(['color' => 'indigo'])

<span {{ $attributes->merge(
    ['class' => "bg-$color-100 text-$color-800 text-xs font-medium px-2.5 py-0.5 rounded-full"]
    ) }}>
        {{ $slot }}
</span>
