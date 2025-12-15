<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('user.index') }}" class="hover:underline">&laquo;&nbsp;{{ __('Back') }}</a>
            <h2 class="ms-2 inline-block font-semibold text-xl text-gray-800 leading-tight">
                {{ __('View user') }}
            </h2>
        </div>
    </x-slot>

    <x-cover-image/>

    <div class="min-h-28 max-h-28 -mt-14 sm:mx-10 mx-4 p-4 bg-white bg-milky-glass shadow-lg rounded-xl  space-x-4 mb-4 flex flex-row items-center">
        <x-student-profile-pic class="self-stretch" :student="$user"/>
        <div class="m-auto text-slate-600 flex-grow">
            <h3 class="text-lg font-semibold">{{ $user->first_name }}&nbsp;{{ $user->last_name }}</h3>
            <h4 class="text-slate-400">
                {{ __('Teacher') }} @if($user->isAdmin())/&nbsp;{{ __('Administrator') }}@endif
            </h4>
        </div>
        <x-button-dark class="sm:block hidden" :href="route('user.edit', $user)">{{ __('Edit') }}</x-button-dark>
    </div>

    <div class="sm:hidden block mx-4 mb-4">
        <x-button-dark class="m-auto" :href="route('user.edit', $user)">{{ __('Edit') }}</x-button-dark>
    </div>

    <div class="flex sm:flex-row flex-col gap-4 sm:mx-4 mx-0">
        <x-card class="text-sm sm:w-1/3">
            <h5 class="text-slate-600 text-base mb-4">{{ __('Basic information') }}</h5>
            <div class="space-y-4 w-full">
                <p><span class="font-semibold">{{ __('First name') }}:</span> {{ $user->first_name }}</p>
                <p><span class="font-semibold">{{ __('Last name') }}:</span> {{ $user->last_name }}</p>
                <p><span class="font-semibold">{{ __('Username') }}:</span> {{ $user->username }}</p>
                <p><span class="font-semibold">{{ __('PIN') }}:</span> <span class="tracking-widest">••••</span></p>
                <p><span class="font-semibold">{{ __('Administrator') }}:</span> {{ $user->isAdmin() ? __('Yes') : __('No') }}</p>
            </div>
        </x-card>

    </div>


</x-app-layout>
