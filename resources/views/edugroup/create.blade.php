<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('edugroup.index') }}" class="hover:underline">&laquo;&nbsp;{{ __('Back') }}</a>
            <h2 class="ms-2 inline-block font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create new group') }}
            </h2>
        </div>
    </x-slot>

    <x-cover-image/>

    <div class="flex gap-4 flex-col sm:flex-row justify-center mt-2 sm:-mt-12 z-0">
        <x-card class="text-sm sm:w-1/2 w-full sm:mx-0 bg-milky-glass">
            <h5 class="text-slate-600 text-base mb-4">{{ __('New group') }}</h5>
            <form class="w-full " method="POST" action="{{ route('edugroup.store') }}">
                @csrf

                <div class="space-y-4 w-full">
                    <div>
                        <x-input-label for="name">{{ __('Name') }}</x-input-label>
                        <x-input-text id="name" class="w-full mt-1" name="name" :value="old('name')" autofocus></x-input-text>
                        <x-input-error :messages="$errors->get('name')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="year_founded">{{ __('Year founded') }}</x-input-label>
                        <x-input-text id="year_founded" class="w-full mt-1" name="year_founded" :value="old('year_founded', \Carbon\Carbon::now()->year)"></x-input-text>
                        <x-input-error :messages="$errors->get('year_founded')" class="mt-2"/>
                    </div>

                    <div>
                        <input type="hidden" name="core" value="0">
                        <x-input-label for="core">{{ __('Core') }}</x-input-label>
                        <input type="checkbox" id="core" name="core" value="1" @checked(old('core', true)) class="ms-2">
                        <x-input-error :messages="$errors->get('core')" class="mt-2"/>
                    </div>
                </div>

                <div class="mt-4 w-full flex gap-2 justify-between sm:justify-end">
                    <x-button-dark name="action" value="save_new">
                        {{ __('Save and add new') }}
                    </x-button-dark>

                    <x-button-dark>
                        {{ __('Save and return') }}
                    </x-button-dark>
                </div>
            </form>
        </x-card>
    </div>

</x-app-layout>
