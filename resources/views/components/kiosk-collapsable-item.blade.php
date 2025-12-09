@props([
    'title' => '',
    'subtitle' => '',
])

<section class="bg-white p-3 sm:p-4 sm:rounded-2xl mt-6 shadow-md w-full text-left" x-data="{ expanded: false }">
    <div @click="expanded = !expanded" class="cursor-pointer flex items-center gap-3 hover:opacity-80">
        <i class="fa-solid fa-caret-right inline-block text-slate-500" x-show=!expanded></i>
        <i class="fa-solid fa-caret-down inline-block text-slate-500" x-cloak x-show=expanded></i>
        <div class="inline-block">
            <h3 class="font-semibold text-slate-700">{{ $title }}</h3>
            <h4 class="text-sm text-slate-500">{!! $subtitle !!}</h4>
        </div>
    </div>

    <div x-cloak x-show="expanded" x-collapse>
        {{ $slot }}
    </div>
</section>
