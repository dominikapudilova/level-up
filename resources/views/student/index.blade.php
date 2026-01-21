<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="ms-2 inline-block font-semibold text-xl text-gray-800 leading-tight">
                {{ __('View all students') }}
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

        @can('admin')
        <div class="mb-4 mx-4">
            <x-button-dark :href="route('student.create')">{{ __('Add new student') }}</x-button-dark>
        </div>
        @endcan

        <x-card class="sm:mx-4 mt-4">
            @if($students->isEmpty())
                <p>{{ __('No students were found! Create a new one.') }}</p>
            @else
                <table class="w-full">
                    <thead>
                    <tr class="text-slate-400 uppercase text-xs border-b border-slate-200">
                        <td class="pb-2">{{ __('Name') }}</td>
                        <td class="pb-2">{{ __('Core class') }}</td>
                        <td class="pb-2">{{ __('Nickname') }}</td>
                        <td class="pb-2">{{ __('Birth date') }}</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($students as $student)
                        <tr class="hover:bg-slate-100"
                            x-show="searchedName === '' ||
                                 '{{ $student->first_name }} {{ $student->last_name }}'
                                    .toLowerCase()
                                    .includes(searchedName.toLowerCase())">
                            <td>
                                <div class="inline-block align-middle">
                                    <x-student-profile-pic :student="$student" :showPhoto="auth()->user()->showPhotos()" class="me-1 max-w-8 max-h-8 align-middle rounded-t rounded-b"/>
                                </div>
                                <a class="hover:underline"
                                   href="{{ route('student.show', $student) }}">{{ $student->first_name }} {{ $student->last_name }}</a>
                            </td>
                            <td>{{ $student->getCoreEdugroup() ? $student->getCoreEdugroup()->name : '--' }}</td>
                            <td>{{ $student->nickname }}</td>
                            <td>{{ \Carbon\Carbon::create($student->birth_date)->format('j.n. Y') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </x-card>
    </div>

</x-app-layout>
