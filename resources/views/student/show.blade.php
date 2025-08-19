<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('student.index') }}" class="hover:underline">&laquo;&nbsp;{{ __('Back') }}</a>
            <h2 class="ms-2 inline-block font-semibold text-xl text-gray-800 leading-tight">
                {{ __('View student') }}
            </h2>
        </div>
    </x-slot>

    <x-cover-image/>

    <div class="min-h-28 max-h-28 -mt-14 sm:mx-10 mx-4 p-4 bg-white bg-milky-glass shadow-lg rounded-xl  space-x-4 mb-4 flex flex-row">
        <img src="https://robohash.org/admin-istrator.png?set=set5" alt="{{ __('profile picture') }}" class="rounded-xl bg-rose-300 block h-full">
        <div class="m-auto text-slate-600 flex-grow">
            <h3 class="text-lg font-semibold">{{ $student->first_name }}&nbsp;{{ $student->last_name }}</h3>
            <h4 class="text-slate-400">{{ __('Student') }}</h4>
        </div>
        <x-button-dark class="m-auto sm:block hidden">Přihlásit jako</x-button-dark>
        <x-button-dark class="m-auto sm:block hidden" :href="route('student.edit', $student)">Editovat</x-button-dark>
    </div>

    <div class="sm:hidden block mx-4 mb-4">
        <x-button-dark class="m-auto">Přihlásit jako</x-button-dark>
        <x-button-dark class="m-auto" :href="route('student.edit', $student)">Editovat</x-button-dark>
    </div>

    <div class="flex sm:flex-row flex-col gap-4 sm:mx-4 mx-0">
        <x-card class="text-sm sm:w-1/3">
            <h5 class="text-slate-600 text-base mb-4">{{ __('Basic information') }}</h5>
            <div class="space-y-4 w-full">
                <p><span class="font-semibold">{{ __('First name') }}:</span> {{ $student->first_name }}</p>
                <p><span class="font-semibold">{{ __('Last name') }}:</span> {{ $student->last_name }}</p>
                <p><span class="font-semibold">{{ __('Nickname') }}:</span> {{ $student->nickname }}</p>
                <p><span class="font-semibold">{{ __('Date of birth') }}:</span> {{ $student->birth_date->format('j.n. Y') }}</p>
                <p><span class="font-semibold">{{ __('Access pin') }}:</span> {{ $student->access_pin }}</p>
            </div>
        </x-card>

        <x-card class="text-sm sm:w-1/3">
            <h5 class="text-slate-600 text-base mb-4">{{ __('Groups & courses') }}</h5>

            @forelse($student->edugroups as $group)
                <div class="">
                    <a class="hover:underline" href="{{ route('edugroup.show', $group) }}">{{ $group->name }}</a>
                    @if($group->core) <i class="fa-solid fa-circle-check text-xs ms-1"></i> @endif
                    <span class="text-slate-400 text-xs ms-2">({{ $group->year_founded }})</span>
                </div>
                @foreach($group->courses as $course)
                    <div class="text-slate-500 text-sm ms-4">
                        &bull;<a class="hover:underline" href="{{ route('course.show', $course) }}">{{ $course->name }}</a>
                        <span class="text-slate-400">({{ $course->code_name }})</span>
                    </div>
                @endforeach
            @empty
                {{ __('Not assigned to any groups.') }}
            @endforelse
        </x-card>

        <x-card class="text-sm sm:w-1/3">
            <h5 class="text-slate-600 text-base mb-4">{{ __('Achieved knowledge') }}</h5>
            nevim
        </x-card>
    </div>

</x-app-layout>
