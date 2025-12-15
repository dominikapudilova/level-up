<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('user.index') }}" class="hover:underline">&laquo;&nbsp;{{ __('Back') }}</a>
            <h2 class="ms-2 inline-block font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create new user') }}
            </h2>
        </div>
    </x-slot>

    <x-cover-image/>

    <div class="flex gap-4 flex-col sm:flex-row justify-center mt-2 sm:-mt-12 z-0">
        <x-card class="text-sm sm:w-1/2 w-full sm:mx-0 bg-milky-glass">
            <h5 class="text-slate-600 text-base mb-4">{{ __('New user') }}</h5>
            <form class="w-full " method="POST" action="{{ route('user.store') }}">
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
                        <x-input-label for="username">{{ __('Username') }}</x-input-label>
                        <x-input-text id="username" class="w-full mt-1" name="username" :value="old('username')" ></x-input-text>
                        <x-input-error :messages="$errors->get('username')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="pin">{{ __('PIN') }}</x-input-label>
                        <x-input-text id="pin" class="w-full mt-1" name="pin" :value="old('pin') ?? rand(1000, 9999)"></x-input-text>
                        <x-input-error :messages="$errors->get('pin')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="password">{{ __('Password') }}</x-input-label>
                        <x-input-text id="password" type="password" class="w-full mt-1" name="password"></x-input-text>
                        <x-input-error :messages="$errors->get('password')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="password_confirmation">{{ __('Confirm password') }}</x-input-label>
                        <x-input-text id="password_confirmation" type="password" class="w-full mt-1" name="password_confirmation"></x-input-text>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2"/>
                    </div>

                    <div>
                        <input type="hidden" name="is_admin" value="0">
                        <x-input-label for="is_admin">{{ __('Administrator') }}</x-input-label>
                        <input type="checkbox" id="is_admin" name="is_admin" value="1" @checked(old('is_admin')) class="ms-2">
                        <x-input-error :messages="$errors->get('is_admin')" class="mt-2"/>
                    </div>
                </div>

                <div class="mt-4 w-full flex sm:justify-end">
                    <x-button-dark>
                        {{ __('Create') }}
                    </x-button-dark>
                </div>
            </form>
        </x-card>
    </div>

</x-app-layout>
