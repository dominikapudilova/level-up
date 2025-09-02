<x-kiosk-layout>

    <div class="max-w-full w-full absolute top-0 -z-0 sm:min-h-[50vh] min-h-dvh" style="background-image: url('{{ asset('assets/img/patterns/pattern'. rand(1, 8) .'.png') }}')">
        <span class="mask bg-gradient-dark opacity-80"></span>
    </div>

    <main class="z-10 sm:pt-12 pt-10 w-full max-w-7xl flex flex-col gap-2 sm:gap-4 relative"
          x-data="{ selectedStudents: [],
          toggleStudentSelection(studentId) {
                if (this.selectedStudents.includes(studentId)) { this.selectedStudents = this.selectedStudents.filter(id => id !== studentId); }
                else { this.selectedStudents.push(studentId); }
          },
          isSelected(studentId) { return this.selectedStudents.includes(studentId); },
          selectAll() { this.selectedStudents = [{{ $students->pluck('id')->implode(',') }}]; }
          }">

        <div class="text-center">
            <img alt="{{ __('app logo') }}" src="{{ asset('assets/img/icon-fullsize.png') }}" class="w-28 m-auto">
            <h1 class="font-bold leading-none tracking-tighter text-white text-4xl">{{ $edugroup->name }}</h1>
            <p class="text-white text-2xl">{{ $course->name }}</p>
            <p class="text-white">{{ $kiosk->started_at->format('d.m. H:i') }}</p>

            <div class="absolute top-2 right-auto sm:right-0 flex sm:flex-col flex-row gap-2 sm:ms-auto ms-2">
                <x-button-dark class="w-full">{{ __('Version for students') }}</x-button-dark>
                <form action="{{ route('kiosk.end', $kiosk) }}" method="POST">
                    @csrf @method('PATCH')
                    <x-button-dark class="w-full">{{ __('End session') }}</x-button-dark>
                </form>
            </div>
        </div>

        <x-card class="w-full">
            <p class="text-center mb-4">
                <x-button-outline class="float-start" @click="selectAll()"><i class="fa-solid fa-user-check"></i></x-button-outline>
                {{ __('Select students') }}
                <span x-text="selectedStudents.length"></span>/{{ $students->count() }}
                <i class="fa-solid fa-user w-4 h-4 text-gray-400 inline-block"></i>
                <a href="{{ route('kiosk.attendance', $kiosk) }}" class="hover:underline text-blue-700 float-end">{{ __('Edit attendance') }}</a>
            </p>
            <div class="grid grid-cols-2 sm:grid-cols-6 gap-4">
                @foreach($students as $student)
                    @include('student.partials.student-card')
                @endforeach
            </div>
        </x-card>

        <x-card class="w-full">
            <p class="text-center mb-2">{{ __('Select knowledge') }}</p>
            <x-knowledge-tree :edufields="$edufields" :mode="'kiosk'" :formName="'give-knowledge'"/>

        </x-card>

        <x-card>
            <p class="text-center mb-2">{{ __('Select knowledge level') }}</p>

            <div class="grid sm:grid-cols-3 grid-cols-2 gap-2 mt-2">
                @foreach($knowledgeLevels as $level)
                    <label>
                        <span class="border-slate-200 border rounded-md w-full p-2 flex flex-row items-center" title="{{ $level->description }}">
                            <img src="{{ asset('assets/img/knowledge-icons/' . $level->icon) }}" alt="{{ $level->name }}" class="w-6 h-6 inline-block me-1.5">
                            {{ $level->name }}
                            <input type="radio" name="level_id" value="{{ $level->id }}" form="give-knowledge" class="ms-auto" @checked(old('level_id') == $level->id)>
                        </span>
                    </label>
                @endforeach
            </div>
        </x-card>

        <form id="give-knowledge" action="{{ route('kiosk.give-knowledge', $kiosk) }}" method="POST" class="w-full text-center sm:text-start p-2">
            @csrf
            <template x-for="studentId in selectedStudents" :key="'input-'+studentId">
                <input type="hidden" name="students[]" :value="studentId">
            </template>

            <x-button-dark class="sm:w-auto w-full float-end">
                {{ __('Confirm') }}
            </x-button-dark>
        </form>
{{--        <x-card>--}}
{{--            <p class="text-center mb-2">{{ __('Summary') }}</p>--}}

            {{--<div class="grid sm:grid-cols-3">
                <div class="border border-slate-200 rounded-lg p-2">
                    <p class="text-center font-semibold">{{ __('Selected students') }}</p>
                    <ul>
                        <template x-for="studentId in selectedStudents" :key="'input-'+studentId">
                            <li x-text="studentId"></li>
                        </template>
                    </ul>
                </div>
                <div class="border border-slate-200 rounded-lg p-2">
                    <p class="text-center font-semibold">{{ __('Selected knowledge') }}</p>
                    <p x-text="this.selectedKnowledge"></p>

                </div>
                <div class="border border-slate-200 rounded-lg p-2">
                    <p class="text-center font-semibold">{{ __('Selected level') }}</p>

                </div>
            </div>--}}

{{--        </x-card>--}}

    </main>


</x-kiosk-layout>
