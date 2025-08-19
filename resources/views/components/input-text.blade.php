@props(['disabled' => false])

<input @disabled($disabled)
    {{ $attributes->merge([
        'class' => 'rounded-md border-gray-200 focus:border-rose-300 focus:ring-rose-300/50 focus:ring-2 shadow-sm text-slate-600 text-sm'
        ])
    }}>
