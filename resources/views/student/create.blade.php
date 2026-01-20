<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('student.index') }}" class="hover:underline">&laquo;&nbsp;{{ __('Back') }}</a>
            <h2 class="ms-2 inline-block font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add new student') }}
            </h2>
        </div>
    </x-slot>

    <x-cover-image/>

    <div class="flex gap-4 flex-col sm:flex-row justify-center mt-2 sm:-mt-12 z-0">
        <x-card class="text-sm sm:w-1/2 w-full sm:mx-0 bg-milky-glass">
            <h5 class="text-slate-600 text-base mb-4">{{ __('New student') }}</h5>
            <form class="w-full " method="POST" action="{{ route('student.store') }}">
                @csrf

                <div class="space-y-4 w-full">
                    <div>
                        <x-input-label for="first_name">{{ __('First name') }}</x-input-label>
                        <x-input-text id="first_name" class="w-full mt-1" name="first_name" :value="old('first_name')" autofocus></x-input-text>
                        <x-input-error :messages="$errors->get('first_name')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="last_name">{{ __('Last name') }}</x-input-label>
                        <x-input-text id="last_name" class="w-full mt-1" name="last_name" :value="old('last_name')"></x-input-text>
                        <x-input-error :messages="$errors->get('last_name')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="nickname">{{ __('Nickname') }}</x-input-label>
                        <x-input-text id="nickname" class="w-full mt-1" name="nickname" :value="old('nickname')" @focus="AppHelpers.generateNickname()"></x-input-text>
                        <x-input-error :messages="$errors->get('nickname')" class="mt-2"/>
                    </div>
                    <div>
                        <x-input-label for="birth_date">{{ __('Date of birth') }}</x-input-label>
                        <x-input-text id="birth_date" class="w-full mt-1" type="date" name="birth_date" :value="old('birth_date')"></x-input-text>
                        <x-input-error :messages="$errors->get('birth_date')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="access_pin">{{ __('Access pin') }}</x-input-label>
                        <x-input-text id="access_pin" class="w-full mt-1" name="access_pin" :value="old('access_pin') ?? rand(1000, 9999)"></x-input-text>
                        <x-input-error :messages="$errors->get('access_pin')" class="mt-2"/>
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
