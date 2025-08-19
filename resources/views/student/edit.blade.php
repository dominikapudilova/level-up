<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('student.show', $student) }}" class="hover:underline">&laquo;&nbsp;{{ __('Back') }}</a>
            <h2 class="ms-2 inline-block font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit student') }}
            </h2>
        </div>
    </x-slot>

    <x-cover-image/>

    <div class="min-h-28 max-h-28 -mt-14 sm:mx-10 mx-4 p-4 bg-white bg-milky-glass shadow-lg rounded-xl  space-x-4 mb-4 flex flex-row">
        <a href="{{ route('student.show', $student) }}">
            <img src="https://robohash.org/admin-istrator.png?set=set5" alt="{{ __('profile picture') }}" class="rounded-xl bg-rose-300 block h-full">
        </a>
        <div class="m-auto text-slate-600 flex-grow">
            <a href="{{ route('student.show', $student) }}">
                <h3 class="text-lg font-semibold">{{ $student->first_name }}&nbsp;{{ $student->last_name }}</h3>
            </a>
            <h4 class="text-slate-400">{{ __('Student') }}</h4>
        </div>
    </div>

    <div class="flex sm:flex-row flex-col gap-4 sm:mx-4 mx-0">
        <x-card class="text-sm sm:mx-0 sm:w-1/2 ">
            <h5 class="text-slate-600 text-base mb-4">{{ __('Edit student') }}</h5>
            <form class="w-full " method="POST" action="{{ route('student.update', $student) }}">
                @csrf
                @method('patch')

                <div class="space-y-4 w-full">
                    <div>
                        <x-input-label for="first_name">{{ __('First name') }}</x-input-label>
                        <x-input-text id="first_name" class="w-full mt-1" name="first_name" :value="old('first_name', $student->first_name)" autofocus></x-input-text>
                        <x-input-error :messages="$errors->get('first_name')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="last_name">{{ __('Last name') }}</x-input-label>
                        <x-input-text id="last_name" class="w-full mt-1" name="last_name" :value="old('last_name', $student->last_name)"></x-input-text>
                        <x-input-error :messages="$errors->get('last_name')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="nickname">{{ __('Nickname') }}</x-input-label>
                        <x-input-text id="nickname" class="w-full mt-1" name="nickname" :value="old('nickname', $student->nickname)"></x-input-text>
                        <x-input-error :messages="$errors->get('nickname')" class="mt-2"/>
                    </div>
                    <div>
                        <x-input-label for="birth_date">{{ __('Date of birth') }}</x-input-label>
                        <x-input-text id="birth_date" class="w-full mt-1" type="date" name="birth_date" :value="old('birth_date', $student->birth_date->format('Y-m-d'))"></x-input-text>
                        <x-input-error :messages="$errors->get('birth_date')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="access_pin">{{ __('Access pin') }}</x-input-label>
                        <x-input-text id="access_pin" class="w-full mt-1" name="access_pin" :value="old('access_pin', $student->access_pin)"></x-input-text>
                        <x-input-error :messages="$errors->get('access_pin')" class="mt-2"/>
                    </div>
                </div>

                <x-button-dark class="mt-4 float-end">{{ __('Save') }}</x-button-dark>
            </form>
        </x-card>

        <x-card class="text-sm sm:mx-0 sm:w-1/2 ">
            <h5 class="text-slate-600 text-base mb-4">{{ __('Přeložit do jiné třídy') }}</h5>
            <h5 class="text-slate-600 text-base mb-4">{{ __('Upravit žákovy skupiny') }}</h5>
        </x-card>
    </div>

</x-app-layout>
