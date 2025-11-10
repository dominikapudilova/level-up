<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="ms-2 inline-block font-semibold text-xl text-gray-800 leading-tight">
                {{ __('View all groups') }}
            </h2>
        </div>
    </x-slot>

    <x-cover-image/>

    <div class=" -mt-14 sm:mx-10 mx-4 p-4 bg-white bg-milky-glass shadow-lg rounded-xl flex sm:flex-row flex-col sm:gap-4 gap-2 mb-4">
        <div class="sm:w-auto w-full">
            <x-input-label class="block">Název</x-input-label>
            <x-input-text class="w-full"/>
        </div>
        <div class="sm:w-auto w-full">
            <x-input-label class="block">Rok</x-input-label>
            <x-input-text class="w-full"/>
        </div>
    </div>

    <div class="mb-4 mx-4">
        <x-button-dark class="" :href="route('edugroup.create')"> {{ __('Add new group') }} </x-button-dark>
    </div>

    <x-card class="sm:mx-4">
        @if($edugroups->isEmpty())
            <p>{{ __('No groups were found! Create a new one.') }}</p>
            <x-button-dark class="mt-2" :href="route('edugroup.create')"> {{ __('Add new group') }} </x-button-dark>
        @else
            <table class="w-full">
                <thead>
                <tr class="text-slate-400 uppercase text-xs border-b border-slate-200">
                    <td class="pb-2">{{ __('Name') }}</td>
                    <td class="pb-2">{{ __('Core') }}</td>
                    <td class="pb-2"><i class="fa-solid fa-users"></i></td>
                    <td class="pb-2"><i class="fa-solid fa-person-chalkboard"></i></td>
                </tr>
                </thead>
                <tbody>
                @foreach($edugroups as $group)
                    <tr class="hover:bg-slate-100">
                        <td>
                            <a class="hover:underline" href="{{ route('edugroup.edit', $group) }}">
                                {{ $group->name }}&nbsp;
                                <span class="text-slate-400 text-xs">({{ $group->year_founded }})</span>
                            </a>
                        </td>
                        <td>@if($group->core) <i class="fa-solid fa-check"></i> @endif</td>
                        <td>{{ $group->students->count() }}</td>
                        <td>hh</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </x-card>
</x-app-layout>
