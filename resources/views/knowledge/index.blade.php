<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="ms-2 inline-block font-semibold text-xl text-gray-800 leading-tight">
                {{ __('View all knowledge units') }}
            </h2>
        </div>
    </x-slot>

    <x-cover-image/>

    <div class=" -mt-14 sm:mx-10 mx-4 p-4 bg-white bg-milky-glass shadow-lg rounded-xl flex sm:flex-row flex-col sm:gap-4 gap-2 mb-4">
        <div class="sm:w-auto w-full">
            <x-input-label class="block">Hledat</x-input-label>
            <x-input-text class="w-full"/>
        </div>
        <div class="sm:w-auto w-full">
            <x-input-label class="block">Třída</x-input-label>
            <x-input-text class="w-full"/>
        </div>
    </div>

    <x-card class="sm:mx-4">
        @if($edufields->isEmpty())
            <p>{{ __('No edufields were found.') }}</p>
        @else
            @include('knowledge.partials.knowledge-collapsable', ['edufields' => $edufields, 'links' => true])
        @endif
    </x-card>
</x-app-layout>
