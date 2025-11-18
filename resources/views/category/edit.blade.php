<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ request('course') ? route('course.edit', ['course' => request('course')]) : route('knowledge.index') }}" class="hover:underline">&laquo;&nbsp;{{ __('Back') }}</a>
            <h2 class="ms-2 inline-block font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit category') }}
            </h2>
        </div>
    </x-slot>

    <x-cover-image/>

    <div class="flex gap-4 flex-col justify-center items-center mt-4 sm:mx-4">
        <x-card class="text-sm sm:w-1/2 w-full sm:mx-0">
            <h5 class="text-slate-600 text-base mb-4">{{ __('Edit category :name', ['name' => $category->name]) }}</h5>
            <form class="w-full " method="POST" action="{{ route('category.update', [ $category, 'course' => request('course') ]) }}">
                @csrf
                @method('patch')

                <div class="space-y-4 w-full">
                    <div>
                        <x-input-label for="name">{{ __('Name') }}</x-input-label>
                        <x-input-text id="name" class="w-full mt-1" name="name" :value="old('name', $category->name)" autofocus></x-input-text>
                        <x-input-error :messages="$errors->get('name')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="code_name">{{ __('Code name') }}</x-input-label>
                        <x-input-text id="code_name" class="w-full mt-1" name="code_name" :value="old('code_name', $category->code_name)"></x-input-text>
                        <x-input-error :messages="$errors->get('code_name')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="description">{{ __('Description') }}</x-input-label>
                        <textarea id="description" name="description" class="w-full mt-1 rounded-md border-gray-200 focus:border-rose-300 focus:ring-rose-300/50 focus:ring-2 shadow-sm text-slate-600 text-sm">{{old('description', $category->description)}}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="edufield_id">{{ __('Education field') }}</x-input-label>
                        <select id="edufield_id" name="edufield_id" class="w-full mt-1 rounded-md border-gray-200 focus:border-rose-300 focus:ring-rose-300/50 focus:ring-2 shadow-sm text-slate-600 text-sm">
                            @foreach($edufields as $edufield)
                                <option value="{{ $edufield->id }}" @selected(old('edufield_id', $category->edufield_id) == $edufield->id)>
                                    {{ $edufield->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <x-button-dark class="mt-4 float-end">{{ __('Update') }}</x-button-dark>
            </form>
        </x-card>

        <x-card class="text-sm sm:w-1/2 w-full sm:mx-0 bg-red-100 space-y-2">
            <h5 class="text-red-600 text-base">{{ __('Danger zone') }}</h5>
            <p class="text-red-600">{{ __('Removing this category is permanent. The subcategories and knowledge it is containing will be deleted as well. Deleted knowledge will be deleted from students.') }}</p>
            <p class="text-red-600">{{ __('Subcategories count') }}: {{ $category->subcategories()->count() }}</p>
            <form method="POST" action="{{ route('category.destroy', [ $category, 'course' => request('course') ]) }}">
                @csrf
                @method('delete')
                <x-danger-button class="float-end">{{ __('Delete category') }}</x-danger-button>
            </form>
        </x-card>

    </div>


</x-app-layout>
