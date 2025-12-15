<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('user.show', $user) }}" class="hover:underline">&laquo;&nbsp;{{ __('Back') }}</a>
            <h2 class="ms-2 inline-block font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit user') }}
            </h2>
        </div>
    </x-slot>

    <x-cover-image/>

    <div class="min-h-28 max-h-28 -mt-14 sm:mx-10 mx-4 p-4 bg-white bg-milky-glass shadow-lg rounded-xl  space-x-4 mb-4 flex flex-row">
        <x-student-profile-pic class="self-stretch" :student="$user" />
        <div class="m-auto text-slate-600 flex-grow">
            <a href="{{ route('user.show', $user) }}">
                <h3 class="text-lg font-semibold">{{ $user->first_name }}&nbsp;{{ $user->last_name }}</h3>
            </a>
            <h4 class="text-slate-400">
                {{ __('Teacher') }} @if($user->isAdmin())/&nbsp;{{ __('Administrator') }}@endif
            </h4>
        </div>
    </div>

    <div class="flex sm:flex-row flex-col gap-4 sm:mx-4 mx-0">
        <x-card class="text-sm sm:mx-0 sm:w-1/4">
            <h5 class="text-slate-600 text-base mb-4">{{ __('Edit user') }}</h5>
            <form class="w-full " method="POST" action="{{ route('user.update', $user) }}">
                @csrf
                @method('patch')

                <div class="space-y-4 w-full">
                    <div>
                        <x-input-label for="first_name">{{ __('First name') }}</x-input-label>
                        <x-input-text id="first_name" class="w-full mt-1" name="first_name" :value="old('first_name', $user->first_name)" autofocus></x-input-text>
                        <x-input-error :messages="$errors->get('first_name')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="last_name">{{ __('Last name') }}</x-input-label>
                        <x-input-text id="last_name" class="w-full mt-1" name="last_name" :value="old('last_name', $user->last_name)"></x-input-text>
                        <x-input-error :messages="$errors->get('last_name')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="username">{{ __('Username') }}</x-input-label>
                        <x-input-text id="username" class="w-full mt-1" name="username" :value="old('username', $user->username)"></x-input-text>
                        <x-input-error :messages="$errors->get('username')" class="mt-2"/>
                    </div>

                    <div>
                        <input type="hidden" name="is_admin" value="0">
                        <x-input-label for="is_admin">{{ __('Administrator') }}</x-input-label>
                        <input type="checkbox" id="is_admin" name="is_admin" value="1" @checked(old('is_admin', $user->is_admin)) class="ms-2">
                        <x-input-error :messages="$errors->get('is_admin')" class="mt-2"/>
                    </div>
                </div>

                <x-button-dark class="mt-4 float-end">{{ __('Save') }}</x-button-dark>
            </form>
        </x-card>

        <x-card class="text-sm sm:mx-0 sm:w-1/4">
            <h5 class="text-slate-600 text-base mb-4">{{ __('Set new PIN') }}</h5>
            <form class="w-full " method="POST" action="{{ route('user.update', $user) }}">
                @csrf
                @method('patch')

                <div class="space-y-4 w-full">
                    <div>
                        <x-input-label for="pin">{{ __('New PIN') }}</x-input-label>
                        <x-input-text id="pin" class="w-full mt-1" name="pin" type="password" minlength="4" maxlength="20"></x-input-text>
                        <x-input-error :messages="$errors->get('pin')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="pin_confirmation">{{ __('Confirm new PIN') }}</x-input-label>
                        <x-input-text id="pin_confirmation" class="w-full mt-1" name="pin_confirmation" type="password"></x-input-text>
                        <x-input-error :messages="$errors->get('pin_confirmation')" class="mt-2"/>
                    </div>
                </div>

                <x-button-dark class="float-end mt-4">
                    {{ __('Set new PIN') }}
                </x-button-dark>
            </form>
        </x-card>

        <x-card class="text-sm sm:mx-0 sm:w-1/4">
            <h5 class="text-slate-600 text-base mb-4">{{ __('Set new password') }}</h5>
            <form class="w-full" method="POST" action="{{ route('user.update', $user) }}">
                @csrf
                @method('patch')

                <div class="space-y-4 w-full">
                    <div>
                        <x-input-label for="password">{{ __('New password') }}</x-input-label>
                        <x-input-text id="password" class="w-full mt-1" name="password" type="password" minlength="4" maxlength="20"></x-input-text>
                        <x-input-error :messages="$errors->get('password')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="password_confirmation">{{ __('Confirm new password') }}</x-input-label>
                        <x-input-text id="password_confirmation" class="w-full mt-1" name="password_confirmation" type="password"></x-input-text>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2"/>
                    </div>
                </div>

                <x-button-dark class="float-end mt-4">
                    {{ __('Set new password') }}
                </x-button-dark>
            </form>
        </x-card>

        <x-card class="text-sm sm:w-1/4 w-full sm:mx-0 bg-red-100 space-y-2 border border-red-200">
            <h5 class="text-red-600">{{ __('Danger zone') }}</h5>
            <form class="w-full " method="POST" action="{{ route('user.destroy', $user) }}">
                @csrf
                @method('delete')
                <div class="space-y-4 w-full">
                    <div>
                        <x-input-label for="password">{{ __('Your password') }}</x-input-label>
                        <x-input-text id="password" class="w-full mt-1" name="password" type="password" minlength="4" maxlength="20"></x-input-text>
                        <x-input-error :messages="$errors->get('password')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="password_confirmation">{{ __('Confirm your password') }}</x-input-label>
                        <x-input-text id="password_confirmation" class="w-full mt-1" name="password_confirmation" type="password"></x-input-text>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2"/>
                    </div>
                </div>

                <x-danger-button class="float-end mt-4">
                    {{ __('Delete this user') }}
                </x-danger-button>
            </form>
        </x-card>
    </div>

</x-app-layout>
