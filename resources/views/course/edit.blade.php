<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('course.index') }}" class="hover:underline">&laquo;&nbsp;{{ __('Back') }}</a>
            <h2 class="ms-2 inline-block font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit course') }}
            </h2>
        </div>
    </x-slot>

    <x-cover-image/>

    <div class="flex gap-4 flex-col sm:flex-row justify-center mt-4 sm:mx-4">
        <x-card class="text-sm sm:w-1/2 w-full sm:mx-0">
            <h5 class="text-slate-600 text-base mb-4">{{ __('Edit course') }} {{ $course->name }}</h5>
            <form class="w-full " method="POST" action="{{ route('course.update', $course) }}">
                @csrf
                @method('patch')

                <div class="space-y-4 w-full">
                    <div>
                        <x-input-label for="name">{{ __('Name') }}</x-input-label>
                        <x-input-text id="name" class="w-full mt-1" name="name" :value="old('name', $course->name)" autofocus></x-input-text>
                        <x-input-error :messages="$errors->get('name')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="code_name">{{ __('Code') }}</x-input-label>
                        <x-input-text id="code_name" class="w-full mt-1" name="code_name" :value="old('code_name', $course->code_name)"></x-input-text>
                        <x-input-error :messages="$errors->get('code_name')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="description">{{ __('Description') }}</x-input-label>
                        <textarea id="description" name="description" class="w-full mt-1 rounded-md border-gray-200 focus:border-rose-300 focus:ring-rose-300/50 focus:ring-2 shadow-sm text-slate-600 text-sm">{{old('description', $course->description)}}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2"/>
                    </div>
                    <div>
                        <x-input-label for="grade">{{ __('Grade') }}</x-input-label>
                        <x-input-text id="grade" class="w-full mt-1" type="number" name="grade" :value="old('grade', $course->grade)"></x-input-text>
                        <x-input-error :messages="$errors->get('grade')" class="mt-2"/>
                    </div>

                    {{--<div>
                        <x-input-label for="compulsory">{{ __('Compulsory') }}</x-input-label>
                        <x-input-text id="compulsory" class="w-full mt-1" name="compulsory" :value="old('compulsory', $course->compulsory)"></x-input-text>
                        <x-input-error :messages="$errors->get('compulsory')" class="mt-2"/>
                    </div>--}}
                </div>

                <x-button-dark class="mt-4 float-end">{{ __('Save') }}</x-button-dark>
            </form>
        </x-card>

        <x-card class="text-sm sm:w-1/2 w-full sm:mx-0">
            <h5 class="text-slate-600 text-base mb-4">{{ __('Manage groups taking this course') }}</h5>

            <form action="{{ route('course.update-edugroups', $course) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="overflow-y-scroll p-1">
                    @foreach($edugroups as $group)
                        <div>
                            <label>
                                <input type="checkbox" name="edugroups[]" value="{{ $group->id }}"
                                    @checked($course->edugroups->contains($group))>
                                {{ $group->name }}&nbsp;@if($group->core)<i class="ms-1 text-xs fa-solid fa-circle-check"></i>@endif
                            </label>
                        </div>
                    @endforeach
                </div>
                <x-button-dark class="float-end mt-4">{{ __('Save') }}</x-button-dark>
            </form>
        </x-card>
    </div>

    <x-card class="mx-4 mt-4">
        <h5 class="text-slate-600 text-base">{{ __('Knowledge overview') }} <span class="text-slate-400">({{ $course->knowledge->count() }})</span></h5>

        @forelse($course->knowledge as $knowledge)
            <div class="flex items-center gap-2 p-2 rounded-md">
                <i class="fa-solid fa-graduation-cap"></i>
                <div class="shrink-0">
                    <span class="text-xs text-slate-400">{{ $knowledge->subcategory->category->edufield->code_name }} > {{ $knowledge->subcategory->category->code_name }} > {{ $knowledge->subcategory->code_name }} > {{ $knowledge->code_name }}</span>
                    <h6 class="text-slate-600 text-lg sm:text-nowrap leading-none">{{ $knowledge->name }}</h6>
                </div>
                <div class="truncate shrink grow-0 ms-2 text-slate-400" title="{{ $knowledge->description }}">
                    {{ Str::limit($knowledge->description, 120) }}
                </div>
                <div class="grow"></div>
                <form method="POST" action="{{ route('course.remove-knowledge', ['course' => $course]) }}">
                    @csrf
                    @method('DELETE')
                    <x-button-outline name="knowledge_id" value="{{ $knowledge->id }}"><i class="fa-solid fa-xmark"></i></x-button-outline>
                </form>
            </div>
        @empty
            <p class="text-slate-400">{{ __('This course has no knowledge assigned. Assign knowledge below.') }}</p>
        @endforelse

    </x-card>

    <x-card class="m-4">
        <div class="flex items-center">
            <h5 class="text-slate-600 text-base">{{ __('Manage knowledge') }}</h5>
            <div class="grow"></div>
            <x-button-outline :href="route('edufield.create', ['course' => $course])"><i class="fa-solid fa-plus"></i></x-button-outline>
        </div>
        <p class="text-slate-400 text-sm mb-2">{{ __('Numbers next to each of the category show amount of items inside. Numbers next to knowledge show how many courses it is used in.') }}</p>
        <x-knowledge-tree :edufields="$edufields" :mode="'edit'" :course="$course" :formName="'update-knowledge'"/>

        <form method="POST" id="update-knowledge" action="{{ route('course.update-knowledge', $course) }}">
            @csrf
            @method('PUT')
            <x-button-dark class="mt-4 float-end">{{ __('Save') }}</x-button-dark>
        </form>
    </x-card>

</x-app-layout>
