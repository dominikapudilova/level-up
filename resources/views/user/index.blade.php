<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="ms-2 inline-block font-semibold text-xl text-gray-800 leading-tight">
                {{ __('View all users') }}
            </h2>
        </div>
    </x-slot>

    <x-cover-image/>

    <div x-data="{ searchedName: '' }">
        <div class=" -mt-14 sm:mx-10 mx-4 p-4 bg-white bg-milky-glass shadow-lg rounded-xl flex sm:flex-row flex-col sm:gap-4 gap-2 mb-4">
            <div class="sm:w-auto w-full">
                <div class="w-full flex justify-between">
                    <x-input-label class="inline-block">{{ __('Search') }}</x-input-label>
                    <span class="text-xs cursor-pointer hover:underline text-blue-700 " @click="searchedName = ''">{{ __('Clear') }}</span>
                </div>
                <x-input-text class="w-full" x-model="searchedName"/>
            </div>
        </div>

        <div class="mb-4 mx-4">
            <x-button-dark class="" :href="route('user.create')"> {{ __('Create new user') }} </x-button-dark>
        </div>

        <x-card class="sm:mx-4 mt-4">
            @if($users->isEmpty())
                <p>{{ __('No users were found! Create a new one.') }}</p>
            @else
                <table class="w-full">
                    <thead>
                    <tr class="text-slate-400 uppercase text-xs border-b border-slate-200">
                        <td class="pb-2">{{ __('Name') }}</td>
                        <td class="pb-2">{{ __('Username') }}</td>
                        <td class="pb-2">{{ __('Admin') }}</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr class="hover:bg-slate-100"
                            x-show="searchedName === '' ||
                                 '{{ $user->first_name }} {{ $user->last_name }}'
                                    .toLowerCase()
                                    .includes(searchedName.toLowerCase())">
                            <td>
                                <div class="inline-block align-middle">
                                    <x-student-profile-pic :student="$user" class="me-1 w-6 h-6 align-middle"/>
                                </div>
                                <a class="hover:underline"
                                   href="{{ route('user.show', $user) }}">{{ $user->first_name }} {{ $user->last_name }}</a>
                            </td>
                            <td>{{ $user->username }}</td>
                            <td>
                                @if($user->isAdmin())
                                    <i class="fa-solid fa-check"></i>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </x-card>
    </div>

</x-app-layout>
