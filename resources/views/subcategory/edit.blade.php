<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ request('course') ? route('course.edit', ['course' => request('course')]) : route('knowledge.index') }}" class="hover:underline">&laquo;&nbsp;{{ __('Back') }}</a>
            <h2 class="ms-2 inline-block font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit subcategory') }}
            </h2>
        </div>
    </x-slot>

    <x-cover-image/>

    <div class="flex gap-4 flex-col justify-center items-center mt-4 sm:mx-4">
        <x-card class="text-sm sm:w-1/2 w-full sm:mx-0">
            <h5 class="text-slate-600 text-base mb-4">{{ __('Edit subcategory :name', ['name' => $subcategory->name]) }}</h5>
            <form class="w-full " method="POST" action="{{ route('subcategory.update', [ $subcategory, 'course' => request('course') ]) }}">
                @csrf
                @method('patch')

                <div class="space-y-4 w-full">
                    <div>
                        <x-input-label for="name">{{ __('Name') }}</x-input-label>
                        <x-input-text id="name" class="w-full mt-1" name="name" :value="old('name', $subcategory->name)" autofocus></x-input-text>
                        <x-input-error :messages="$errors->get('name')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="code_name">{{ __('Code name') }}</x-input-label>
                        <x-input-text id="code_name" class="w-full mt-1" name="code_name" :value="old('code_name', $subcategory->code_name)"></x-input-text>
                        <x-input-error :messages="$errors->get('code_name')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="description">{{ __('Description') }}</x-input-label>
                        <textarea id="description" name="description" class="w-full mt-1 rounded-md border-gray-200 focus:border-rose-300 focus:ring-rose-300/50 focus:ring-2 shadow-sm text-slate-600 text-sm">{{old('description', $subcategory->description)}}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="category_id">{{ __('Category') }}</x-input-label>
                        <select id="category_id" name="category_id" class="w-full mt-1 rounded-md border-gray-200 focus:border-rose-300 focus:ring-rose-300/50 focus:ring-2 shadow-sm text-slate-600 text-sm">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('edufield_id', $subcategory->category_id) == $category->id)>
                                    {{ $category->edufield->name }} > {{ $category->name }}
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
            <p class="text-red-600">{{ __('Removing this subcategory is permanent. The knowledge it is containing will be deleted as well. Deleted knowledge will be deleted from students.') }}</p>
            <p class="text-red-600">{{ __('Knowledge units count') }}: {{ $subcategory->knowledge()->count() }}</p>
            <form method="POST" action="{{ route('subcategory.destroy', [ $subcategory, 'course' => request('course') ]) }}">
                @csrf
                @method('delete')
                <x-danger-button class="float-end">{{ __('Delete subcategory') }}</x-danger-button>
            </form>
        </x-card>
    </div>


</x-app-layout>
