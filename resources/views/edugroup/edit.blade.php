<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('edugroup.index') }}" class="hover:underline">&laquo;&nbsp;{{ __('Back') }}</a>
            <h2 class="ms-2 inline-block font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit group') }}
            </h2>
        </div>
    </x-slot>

    <x-cover-image/>

    <div class="flex gap-4 flex-col sm:flex-row justify-center mt-4 sm:mx-4">
        <x-card class="text-sm sm:w-1/2 w-full sm:mx-0">
            <h5 class="text-slate-600 text-base mb-4">{{ __('Edit group') }}</h5>
            <form class="w-full " method="POST" action="{{ route('edugroup.update', $edugroup) }}">
                @csrf
                @method('patch')

                <div class="space-y-4 w-full">
                    <div>
                        <x-input-label for="name">{{ __('Name') }}</x-input-label>
                        <x-input-text id="name" class="w-full mt-1" name="name" :value="old('name', $edugroup->name)" autofocus></x-input-text>
                        <x-input-error :messages="$errors->get('name')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="year_founded">{{ __('Year founded') }}</x-input-label>
                        <x-input-text id="year_founded" class="w-full mt-1" name="year_founded" :value="old('year_founded', $edugroup->year_founded)"></x-input-text>
                        <x-input-error :messages="$errors->get('year_founded')" class="mt-2"/>
                    </div>

                    <div>
                        <input type="hidden" name="core" value="0">
                        <x-input-label for="core">{{ __('Core') }}</x-input-label>
                        <input type="checkbox" id="core" name="core" value="1" @checked(old('core', $edugroup->core)) class="ms-2">
                        <x-input-error :messages="$errors->get('core')" class="mt-2"/>
                    </div>
                </div>

                <x-button-dark class="mt-4 float-end">{{ __('Update') }}</x-button-dark>
            </form>
        </x-card>

        <x-card class="text-sm sm:w-1/2 w-full sm:mx-0">
            <h5 class="text-slate-600 text-base mb-4">{{ __('Manage students in group') }}</h5>

            <form action="{{ route('edugroup.update-students', $edugroup) }}" method="POST">
                @csrf
                @method('PUT')

                <div
                    x-data="AppHelpers.studentGroupAssignment({
                        available: {{ $students->diff($edugroup->students)->values() }},
                        selected: {{ $edugroup->students->values() }}
                    })"
                    class="grid grid-cols-2 gap-2 sm:gap-4 "
                >

                    <!-- Available Users -->
                    <div class="border p-2 rounded-md overflow-y-scroll">
                        <h3 class="font-bold mb-2">{{ __('Available students') }}</h3>
                        <template x-for="(student, index) in available" :key="student.id">
                            <div class="p-1 bg-gray-100 mb-1 cursor-pointer" @click="selectUser(index)">
                                <span x-text="student.last_name + ' ' + student.first_name"></span>
                            </div>
                        </template>
                    </div>

                    <!-- Selected Users -->
                    <div class="border p-2 rounded-md overflow-y-scroll">
                        <h3 class="font-bold inline-block mb-2 me-2">{{ __('Selected students') }}</h3>
                        <span x-text="selected.length + '/' + (available.length + selected.length)"></span>
                        <template x-for="(student, index) in selected" :key="student.id">
                            <div class="p-1 bg-green-100 mb-1 cursor-pointer" @click="removeUser(index)">
                                <span x-text="student.last_name + ' ' + student.first_name"></span>
                            </div>
                        </template>
                    </div>

                    <!-- Hidden Inputs -->
                    <template x-for="student in selected" :key="'input-'+student.id">
                        <input type="hidden" name="students[]" :value="student.id">
                    </template>
                </div>

                <x-button-dark class="float-end mt-4">{{ __('Save') }}</x-button-dark>
            </form>
        </x-card>
    </div>


</x-app-layout>
