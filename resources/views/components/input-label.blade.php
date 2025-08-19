@props(['value'])

<label {{ $attributes->merge(['class' => 'text-slate-700 text-sm font-semibold']) }}>
    {{ $value ?? $slot }}
</label>
