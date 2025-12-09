<x-kiosk-layout>

    <div class="max-w-full w-full absolute top-0 -z-0 sm:min-h-[50vh] min-h-dvh" style="background-image: url('{{ asset('assets/img/backgrounds/pattern'. rand(1, 9) .'.png') }}')">
        <span class="mask bg-gradient-dark opacity-80"></span>
    </div>

    <main class="z-10 text-center sm:pt-12 pt-10 w-full max-w-7xl relative" x-data="{ selectedStudentRoute: '', selectedStudentName: '' }">
        <img alt="{{ __('app logo') }}" src="{{ asset('assets/img/icon-fullsize.png') }}" class="w-28 m-auto">
        <h1 class="font-bold leading-none tracking-tighter text-white text-4xl">{{ __('Select your account') }}</h1>
        <p class="text-white text-2xl">{{ __('and log in with PIN') }}</p>

        <div class="absolute top-2 right-auto sm:right-0 flex sm:flex-col flex-row gap-2 sm:ms-auto ms-2">
            <x-button-dark href="/" class="w-full">
                {{ __('Leave kiosk') }}
            </x-button-dark>
        </div>

        <section class="bg-white p-6 sm:rounded-2xl mt-6 shadow-md">

            <div class="grid sm:grid-cols-6 grid-cols-2 gap-4 mt-4">
                @foreach($students as $student)
                    <div class="cursor-pointer"
                         x-on:click.prevent="$dispatch('open-modal', 'confirm-student-pin'); selectedStudentRoute = '{{ route('kiosk.student.edit', [$kiosk, $student]) }}'; selectedStudentName = '{{ $student->nickname }}'">
                        @include('student.partials.student-card', ['showNickname' => true])
                    </div>
                @endforeach
            </div>

        </section>

        <x-modal name="confirm-student-pin" focusable>
            <form :action="selectedStudentRoute" method="POST" class="w-full text-center sm:text-start p-6">
                @csrf
                <h2 class=" text-lg mb-1">{{ __('Confirm student selection') }}</h2>

                <div>
                    <x-input-label for="confirm-student-pin">{!! __('Student\'s PIN (:name)', ['name' => '<span x-text="selectedStudentName"></span>']) !!}</x-input-label>
                    <x-input-text id="confirm-student-pin" class="w-full mt-1" name="pin" type="password" minlength="4" maxlength="20"></x-input-text>
                </div>

                <div class="w-full flex justify-end mt-4">
                    <x-button-dark class="sm:w-auto w-full">
                        {{ __('Confirm') }}
                    </x-button-dark>
                </div>
            </form>
        </x-modal>

    </main>

</x-kiosk-layout>
