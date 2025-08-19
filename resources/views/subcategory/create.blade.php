<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ request('course') ? route('course.show', ['course' => request('course')]) : route('course.index') }}" class="hover:underline">&laquo;&nbsp;{{ __('Back') }}</a>
            <h2 class="ms-2 inline-block font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create new subcategory') }}
            </h2>
        </div>
    </x-slot>

    <x-cover-image/>

    <div class="flex gap-4 flex-col sm:flex-row justify-center mt-2 sm:-mt-12 z-0">
        <x-card class="text-sm sm:w-1/2 w-full sm:mx-0 bg-milky-glass">
            <h5 class="text-slate-600 text-base ">{{ __('New subcategory in the :name category', ['name' => $category->name]) }}</h5>
            <div class="mb-4">
                <p>{{ __('Other subcategories in this category') }}: @forelse($subcategories as $subcategory) {{ $subcategory->name }} @empty -- @endforelse</p>
            </div>
            <form class="w-full " method="POST" action="{{ route('subcategory.store', ['course' => request('course')]) }}">
                @csrf

                <div class="space-y-4 w-full">
                    <input type="hidden" name="category_id" value="{{ $category->id }}">
                    <div>
                        <x-input-label for="name">{{ __('Name') }}</x-input-label>
                        <x-input-text id="name" class="w-full mt-1" name="name" :value="old('name')" autofocus></x-input-text>
                        <x-input-error :messages="$errors->get('name')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="code_name">{{ __('Code name') }}</x-input-label>
                        <x-input-text id="code_name" class="w-full mt-1" name="code_name" :value="old('code_name')"></x-input-text>
                        <x-input-error :messages="$errors->get('code_name')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="description">{{ __('Description') }}</x-input-label>
                        <textarea id="description" name="description" class="w-full mt-1 rounded-md border-gray-200 focus:border-rose-300 focus:ring-rose-300/50 focus:ring-2 shadow-sm text-slate-600 text-sm">{{old('description')}}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2"/>
                    </div>
                </div>

                <x-button-dark class="mt-4 float-end">
                    {{ __('Save and return') }}
                </x-button-dark>
            </form>
        </x-card>
    </div>

</x-app-layout>
