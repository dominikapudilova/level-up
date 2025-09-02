<x-app-layout>

    <x-slot name="header" >
        <h2 class="font-semibold text-xl text-slate-700 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>


    <x-cover-image/>

    <div class="m-4 space-x-4">
        <x-button-dark :href="route('kiosk.create')">{{ __('Start lesson') }}</x-button-dark>
        <x-button-dark :href="route('kiosk.index')">{{ __('Continue lesson') }}</x-button-dark>
    </div>

    <x-card class="mx-4">

    </x-card>

</x-app-layout>
