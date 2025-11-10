<x-kiosk-layout>
    {{--<x-slot name="header">
        <div>
            <a href="{{ route('dashboard') }}" class="hover:underline">&laquo;&nbsp;{{ __('Back') }}</a>
            <h2 class="ms-2 inline-block font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Start new lesson') }}
            </h2>
        </div>
    </x-slot>--}}

    <div class="max-w-full w-full absolute top-0 -z-0 sm:min-h-[50vh] min-h-dvh" style="background-image: url('{{ asset('assets/img/patterns/pattern'. rand(1, 9) .'.png') }}')">
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
                    <div class="border cursor-pointer rounded-md h-36 shadow-md relative transition-all hover:scale-105 hover:shadow-lg ring-offset-1 ring-4" @click="toggleStudentSelection({{ $student->id }})" :class="isSelected({{ $student->id }}) ? 'ring-emerald-400' : 'ring-rose-400'">
                        <div class="h-1/2 rounded-t-md" style="background-image: url('{{ asset("assets/img/patterns/") }}/{{ $student->background_image ?? 'pattern5.png' }}'); background-size: cover"></div>

                        <div class="absolute top-0 left-0 right-0 bottom-0">
                            <div class="max-w-20 max-h-20 mx-auto mt-3 mb-1 aspect-square rounded-full bg-white overflow-hidden">
                                <img src="https://robohash.org/{{ $student->avatar ?? 'YOUR-TEXT' }}.png?set=set1" alt="{{ __('student avatar') }}" class="w-full h-full object-cover">
                            </div>
                            <p class="truncate"><span class="uppercase">{{ $student->first_name }}</span><span class=" text-gray-500"> {{ $student->last_name }}</span></p>
                            <p class="text-slate-400 text-xs">{{ __('Level') }} {{ $student->getLevel() }}</p>
                        </div>
                    </div>
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
