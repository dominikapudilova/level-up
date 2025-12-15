<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage students') }}
        </h2>
    </x-slot>

    <x-cover-image/>

    <div class="m-4 flex gap-4 flex-col sm:flex-row flex-wrap">
        <x-button-dark :href="route('student.index')">{{ __('Browse') }}</x-button-dark>
{{--        <x-button-dark>{{ __('Find student') }}</x-button-dark>--}}
        <x-button-dark :href="route('student.create')">{{ __('Add new student') }}</x-button-dark>
    </div>
</x-app-layout>
