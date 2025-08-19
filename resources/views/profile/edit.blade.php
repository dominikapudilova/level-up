<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <x-cover-image/>

    <div class="min-h-28 max-h-28 -mt-14 sm:mx-10 mx-4 p-4 bg-white bg-milky-glass shadow-lg rounded-xl  space-x-4 mb-4 flex flex-row">
        <img src="https://robohash.org/admin-istrator.png?set=set5" alt="{{ __('profile picture') }}" class="rounded-xl bg-rose-300 block h-full">
        <div class="m-auto text-slate-600">
            <h3 class="text-lg font-semibold">{{ auth()->user()->first_name }}&nbsp;{{ auth()->user()->last_name }}</h3>
            <h4 class="text-slate-400">{{ __('Teacher') }} @if(auth()->user()->is_admin) /&nbsp;{{ __('Administrator') }} @endif</h4>
        </div>
    </div>

    <div class="flex gap-4 mx-4 flex-col sm:flex-row">
        <x-card class="text-sm sm:w-1/2 w-full sm:mx-0">
            <h5 class="text-slate-600 text-base mb-4">{{ __('Profile') }}</h5>
            <form class="w-full " method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')

                <div class="space-y-4 w-full">
                    <div>
                        <x-input-label for="first_name">{{ __('First name') }}</x-input-label>
                        <x-input-text id="first_name" class="w-full mt-1" name="first_name"
                                      :value="old('first_name', auth()->user()->first_name)" autofocus></x-input-text>
                        <x-input-error :messages="$errors->get('first_name')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="last_name">{{ __('Last name') }}</x-input-label>
                        <x-input-text id="last_name" class="w-full mt-1" name="last_name"
                                      :value="old('last_name', auth()->user()->last_name)"></x-input-text>
                        <x-input-error :messages="$errors->get('last_name')" class="mt-2"/>
                    </div>
                </div>

                <x-button-dark class="float-end mt-4">
                    {{ __('Save changes') }}
                </x-button-dark>
            </form>
        </x-card>

        <x-card class="text-sm sm:w-1/2 w-full sm:mx-0">
            <h5 class="text-slate-600 text-base mb-4">{{ __('Password') }}</h5>
            <form class="w-full " method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('put')

                <div class="space-y-4 w-full">
                    <div>
                        <x-input-label for="current_password">{{ __('Old password') }}</x-input-label>
                        <x-input-text id="current_password" class="w-full mt-1" name="current_password" type="password"></x-input-text>
                        <x-input-error :messages="$errors->get('current_password')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="password">{{ __('New password') }}</x-input-label>
                        <x-input-text id="password" class="w-full mt-1" name="password" type="password"></x-input-text>
                        <x-input-error :messages="$errors->get('password')" class="mt-2"/>
                    </div>
                </div>

                <x-button-dark class="float-end mt-4">
                    {{ __('Change password') }}
                </x-button-dark>
            </form>
        </x-card>
    </div>

    {{--<form class="w-full " method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')

                <div class="grid sm:grid-cols-2 grid-cols-1 sm:gap-4 gap-2 w-full">
                    <div class="col-span-1 grid grid-cols-subgrid gap-4">
                        <div>
                            <x-input-label for="first_name">{{ __('First name') }}</x-input-label>
                            <x-input-text id="first_name" class="w-full mt-1" name="first_name" :value="old('first_name') ?? auth()->user()->first_name" autofocus></x-input-text>
                            <x-input-error :messages="$errors->get('first_name')" class="mt-2"/>
                        </div>

                        <div>
                            <x-input-label for="last_name">{{ __('Last name') }}</x-input-label>
                            <x-input-text id="last_name" class="w-full mt-1" name="last_name" :value="old('last_name') ?? auth()->user()->last_name"></x-input-text>
                            <x-input-error :messages="$errors->get('last_name')" class="mt-2"/>
                        </div>
                    </div>

                    <div class="col-span-1 grid grid-cols-subgrid gap-4">
                        <div>
                            <x-input-label for="old_pass">{{ __('Old password') }}</x-input-label>
                            <x-input-text id="old_pass" class="w-full mt-1" name="old_pass" type="password"></x-input-text>
                            <x-input-error :messages="$errors->get('old_pass')" class="mt-2"/>
                        </div>

                        <div>
                            <x-input-label for="new_pass">{{ __('New password') }}</x-input-label>
                            <x-input-text id="new_pass" class="w-full mt-1" name="new_pass" type="password"></x-input-text>
                            <x-input-error :messages="$errors->get('new_pass')" class="mt-2"/>
                        </div>
                    </div>
                </div>

                <x-button-dark class="float-end mt-4">
                    {{ __('Save changes') }}
                </x-button-dark>
            </form>--}}


</x-app-layout>
