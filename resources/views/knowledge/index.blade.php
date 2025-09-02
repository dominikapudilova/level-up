<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="ms-2 inline-block font-semibold text-xl text-gray-800 leading-tight">
                {{ __('View all knowledge units') }}
            </h2>
        </div>
    </x-slot>

    <x-cover-image/>

    <x-card class="sm:mx-4 mt-4">
        <div class="inline-flex items-center justify-between w-full">
            <h3>{{ __('Knowledge levels') }}</h3>
            <a class="hover:underline text-blue-700" href="{{ route('knowledge-level.index') }}">{{ __('Edit') }}</a>
        </div>
        <div class="grid sm:grid-cols-3 grid-cols-2 gap-2 mt-2">
            @foreach($knowledgeLevels as $level)
                <div class="border-slate-200 border rounded-md w-full p-2 flex flex-row items-center" title="{{ $level->description }}">
                    <img src="{{ asset('assets/img/knowledge-icons/' . $level->icon) }}" alt="{{ $level->name }}" class="w-6 h-6 inline-block me-1.5">
                    {{ $level->name }}
                    <div class="ms-auto bg-slate-300 rounded-full px-1 py-1 leading-none text-xs">{{ $level->weight }}</div>
                </div>
            @endforeach
        </div>

    </x-card>

    <x-card class="sm:mx-4 mt-4">
        <x-knowledge-tree :edufields="$edufields" :mode="'edit'"/>
    </x-card>
</x-app-layout>
