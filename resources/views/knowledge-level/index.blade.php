<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('knowledge.index') }}" class="hover:underline">&laquo;&nbsp;{{ __('Back') }}</a>
            <h2 class="ms-2 inline-block font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit knowledge levels') }}
            </h2>
        </div>
    </x-slot>

    <x-cover-image/>
    <div class="flex flex-col items-center gap-4 sm:mx-4 mt-4">
        @foreach($knowledgeLevels as $level)
            <x-card class="sm:w-1/2 w-full sm:mx-0">
                <form action="{{ route('knowledge-level.update', $level) }}" method="POST" class="w-full">
                    @csrf
                    @method('PATCH')

                    <img src="{{ asset('assets/img/knowledge-icons/' . $level->icon) }}" alt="{{ $level->name }}" class="w-6 h-6 block">

                    <x-input-label class="block mb-1" for="name">{{ __('Name') }}</x-input-label>
                    <x-input-text class="w-full mb-2" name="name" id="name" :value="old('name', $level->name)" required></x-input-text>

                    <x-input-label class="block mb-1" for="description">{{ __('Description') }}</x-input-label>
                    <x-input-text class="w-full mb-2" name="description" id="description" :value="old('description', $level->description)" required></x-input-text>

                    <x-input-label class="block mb-1" for="weight">{{ __('Weight') }}</x-input-label>
                    <div class="w-full">
                        <x-input-text class="mb-2" name="weight" id="weight" :value="old('weight', $level->weight)" required></x-input-text>xp
                    </div>

                    <x-button-dark type="submit">{{ __('Save') }}</x-button-dark>
                </form>
            </x-card>
        @endforeach
    </div>

</x-app-layout>
