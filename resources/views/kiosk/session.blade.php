<x-kiosk-layout>

    <div class="max-w-full w-full absolute top-0 -z-0 sm:min-h-[50vh] min-h-dvh" style="background-image: url('{{ asset('assets/img/patterns/pattern'. rand(1, 8) .'.png') }}')">
        <span class="mask bg-gradient-dark opacity-80"></span>
    </div>

    <main class="z-10 sm:pt-12 pt-10 w-full max-w-7xl flex flex-col gap-2 sm:gap-4 relative"
          x-data="{ selectedStudents: [{{ (old('students') ? implode(',', old('students')) : '') }}],
          selectedLevel: {{ old('level_id', 'null') }},
          selectedKnowledge: {{ old('knowledge_id', 'null') }},
          toggleStudentSelection(studentId) {
                if (this.selectedStudents.includes(studentId)) { this.selectedStudents = this.selectedStudents.filter(id => id !== studentId); }
                else { this.selectedStudents.push(studentId); }
          },
          isSelected(studentId) { return this.selectedStudents.includes(studentId); },
          selectAll() { this.selectedStudents = [{{ $students->pluck('id')->implode(',') }}] }
          }">

        <div class="text-center">
            <img alt="{{ __('app logo') }}" src="{{ asset('assets/img/icon-fullsize.png') }}" class="w-28 m-auto">
            <h1 class="font-bold leading-none tracking-tighter text-white text-4xl">{{ $edugroup->name }}</h1>
            <p class="text-white text-2xl">{{ $course->name }}</p>
            <p class="text-white">{{ $kiosk->started_at->format('d.m. H:i') }}</p>

            <div class="absolute top-2 right-auto sm:right-0 flex sm:flex-col flex-row gap-2 sm:ms-auto ms-2">
                <x-button-dark class="w-full">
                    {{ __('Version for students') }}
                </x-button-dark>
                <x-button-dark x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-end-session')" class="w-full">
                    {{ __('End session') }}
                </x-button-dark>
            </div>
        </div>

        <x-card class="w-full">
            <p class="text-center mb-4">
                <x-button-outline class="float-start" @click="selectAll()"><i class="fa-solid fa-user-check"></i></x-button-outline>
                {{ __('Select students') }}
                <span x-text="selectedStudents.length"></span>/{{ $students->count() }}
                <i class="fa-solid fa-user w-4 h-4 text-gray-400 inline-block"></i>
                <template x-if="selectedStudents.length > 0"><i class="fa-solid fa-circle-check"></i></template>
                <a href="{{ route('kiosk.attendance', $kiosk) }}" class="hover:underline text-blue-700 float-end">{{ __('Edit attendance') }}</a>
            </p>
            <div class="grid grid-cols-2 sm:grid-cols-6 gap-4">
                @foreach($students as $student)
                    @include('student.partials.student-card')
                @endforeach
            </div>
        </x-card>

        <x-card class="w-full">
            <p class="text-center mb-2">{{ __('Select knowledge') }}
                <template x-if="selectedKnowledge"><i class="fa-solid fa-circle-check"></i></template>
            </p>
            <x-knowledge-tree :edufields="$edufields" :mode="'kiosk'" :formName="'give-knowledge'"/>
        </x-card>

        <x-card>
            <p class="text-center mb-2">{{ __('Select knowledge level') }}
                <template x-if="selectedLevel"><i class="fa-solid fa-circle-check"></i></template>
            </p>
            <div class="grid sm:grid-cols-3 grid-cols-2 gap-2 mt-2">
                @foreach($knowledgeLevels as $level)
                    <label>
                        <span class="border-slate-200 border rounded-md w-full p-2 flex flex-row items-center" title="{{ $level->description }}">
                            <img src="{{ asset('assets/img/knowledge-icons/' . $level->icon) }}" alt="{{ $level->name }}" class="w-6 h-6 inline-block me-1.5">
                            {{ $level->name }}
                            <input type="radio" name="level_id" value="{{ $level->id }}" form="give-knowledge" class="ms-auto" x-model="selectedLevel">
                        </span>
                    </label>
                @endforeach
            </div>
        </x-card>

        <div x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-give-knowledge')">
            <x-button-dark class="sm:w-auto w-full float-end">
                {{ __('Confirm') }}
            </x-button-dark>
        </div>

        {{--<x-card>
            <p class="text-center mb-2">{{ __('Summary') }}</p>
            <div class="grid sm:grid-cols-3">
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
            </div>
        </x-card>--}}

        <x-modal name="confirm-give-knowledge" :show="$errors->has('pin')" focusable>
            <form id="give-knowledge" action="{{ route('kiosk.give-knowledge', $kiosk) }}" method="POST" class="w-full text-center sm:text-start p-6">
                @csrf
                <h2 class=" text-lg mb-1">{{ __('Confirm giving knowledge') }}</h2>

                <template x-for="studentId in selectedStudents" :key="'input-'+studentId">
                    <input type="hidden" name="students[]" :value="studentId">
                </template>

                <div>
                    <x-input-label for="give-knowledge-pin">{{ __('Teacher\'s PIN (:name)', ['name' => $teacherName]) }}</x-input-label>
                    <x-input-text id="give-knowledge-pin" class="w-full mt-1" name="pin" type="password" minlength="4" maxlength="20"></x-input-text>
                    <x-input-error :messages="$errors->get('pin')" class="mt-2"/>
                </div>

                <div class="w-full flex justify-end mt-4">
                    <x-button-dark class="sm:w-auto w-full">
                        {{ __('Confirm') }}
                    </x-button-dark>
                </div>
            </form>
        </x-modal>

        <x-modal name="confirm-end-session" :show="$errors->has('pin')" focusable>
            <form action="{{ route('kiosk.end', $kiosk) }}" method="POST" class="w-full text-center sm:text-start p-6">
                @csrf
                @method('PATCH')
                <h2 class=" text-lg mb-1">{{ __('Confirm ending the session') }}</h2>

                <div>
                    <x-input-label for="end-session-pin">{{ __('Teacher\'s PIN (:name)', ['name' => $teacherName]) }}</x-input-label>
                    <x-input-text id="end-session-pin" class="w-full mt-1" name="pin" type="password" minlength="4" maxlength="20"></x-input-text>
                    <x-input-error :messages="$errors->get('pin')" class="mt-2"/>
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
