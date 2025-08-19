<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('course.index') }}" class="hover:underline">&laquo;&nbsp;{{ __('Back') }}</a>
            <h2 class="ms-2 inline-block font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add new course') }}
            </h2>
        </div>
    </x-slot>

    <x-cover-image/>

    <div class="flex gap-4 flex-col sm:flex-row justify-center mt-2 sm:-mt-12 z-0">
        <x-card class="text-sm sm:w-1/2 w-full sm:mx-0 bg-milky-glass">
            <h5 class="text-slate-600 text-base">{{ __('New course') }}</h5>
            <p class="mb-4 mt-2 text-slate-500 leading-tight">{{ __('Create a new course. Course is a set of knowledge units that students taking this course through a group can acquire. Course connects sets of knowledge to sets of students.') }}</p>

            <form class="w-full " method="POST" action="{{ route('course.store') }}">
                @csrf

                <div class="space-y-4 w-full">
                    <div>
                        <x-input-label for="name">{{ __('Name') }}</x-input-label>
                        <x-input-text id="name" class="w-full mt-1" name="name" :value="old('name')" autofocus></x-input-text>
                        <x-input-error :messages="$errors->get('name')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="code_name">{{ __('Code') }}</x-input-label>
                        <x-input-text id="code_name" class="w-full mt-1" name="code_name" :value="old('code_name')"></x-input-text>
                        <x-input-error :messages="$errors->get('code_name')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="description">{{ __('Description') }}</x-input-label>
                        <textarea id="description" name="description" class="w-full mt-1 rounded-md border-gray-200 focus:border-rose-300 focus:ring-rose-300/50 focus:ring-2 shadow-sm text-slate-600 text-sm">{{old('description')}}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2"/>
                    </div>
                    <div>
                        <x-input-label for="grade">{{ __('Grade') }}</x-input-label>
                        <x-input-text id="grade" class="w-full mt-1" type="number" name="grade" :value="old('grade')"></x-input-text>
                        <x-input-error :messages="$errors->get('grade')" class="mt-2"/>
                        <p class="mt-2 text-xs text-slate-500 leading-tight">{{ __('Adding a grade will allow the system to connect the group with the current level of the course, even if multiple of the same course but different grade are assigned.') }}</p>
                    </div>

                    {{--<div>
                        <x-input-label for="compulsory">{{ __('Compulsory') }}</x-input-label>
                        <x-input-text id="compulsory" class="w-full mt-1" name="compulsory" :value="old('compulsory')"></x-input-text>
                        <x-input-error :messages="$errors->get('compulsory')" class="mt-2"/>
                    </div>--}}
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
