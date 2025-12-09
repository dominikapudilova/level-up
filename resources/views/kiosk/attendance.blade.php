<x-kiosk-layout>
    {{--<x-slot name="header">
        <div>
            <a href="{{ route('dashboard') }}" class="hover:underline">&laquo;&nbsp;{{ __('Back') }}</a>
            <h2 class="ms-2 inline-block font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Start new lesson') }}
            </h2>
        </div>
    </x-slot>--}}

    <div class="max-w-full w-full absolute top-0 -z-0 sm:min-h-[50vh] min-h-dvh" style="background-image: url('{{ asset('assets/img/backgrounds/pattern'. rand(1, 9) .'.png') }}')">
        <span class="mask bg-gradient-dark opacity-80"></span>
    </div>

    <main class="z-10 text-center sm:mt-16 mt-0 w-full max-w-7xl"
          x-data="{ selectedStudents: [{{ implode(',', $presentStudentIds) }}],
          toggleStudentSelection(studentId) {
                if (this.selectedStudents.includes(studentId)) { this.selectedStudents = this.selectedStudents.filter(id => id !== studentId); }
                else { this.selectedStudents.push(studentId); }
          },
          isSelected(studentId) { return this.selectedStudents.includes(studentId); }
          }">
        <img alt="{{ __('app logo') }}" src="{{ asset('assets/img/icon-fullsize.png') }}" class="w-28 m-auto">
        <h1 class="font-bold leading-none tracking-tighter text-white text-4xl">{{ $edugroup->name }}</h1>
        <p class="text-white text-2xl">{{ $course->name }}</p>

        <section class="bg-white p-6 sm:rounded-2xl mt-6 shadow-md">
            <p class="">{{ __('Students will confirm attendance by clicking their character.') }}</p>
            <div class="whitespace-nowrap mb-2 text-slate-600 text-lg">
                <span x-text="selectedStudents.length"></span>/{{ $students->count() }}
                <i class="fa-solid fa-user w-4 h-4 text-gray-400 inline-block"></i>
            </div>

            <div class="grid sm:grid-cols-6 grid-cols-2 gap-4 mt-4">
                @foreach($students as $student)
                    @include('student.partials.student-card')
                @endforeach
            </div>

            <form class="w-full text-start" method="POST" action="{{ route('kiosk.store-attendance', $kiosk) }}">
                @csrf

                <template x-for="studentId in selectedStudents" :key="'input-'+studentId">
                    <input type="hidden" name="students[]" :value="studentId">
                </template>

                <x-button-dark class=" mt-5">
                    {{ __('Confirm') }}
                </x-button-dark>
            </form>
        </section>
    </main>


</x-kiosk-layout>
