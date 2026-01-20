<x-kiosk-layout>

    <div class="max-w-full w-full absolute top-0 -z-0 min-h-dvh h-full"
         style="background-image: url('{{ asset('assets/img/backgrounds/'. collect(config('school.cosmetics.backgrounds'))->random() ) }}')">
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
          selectAll() { this.selectedStudents = [{{ $students->pluck('id')->implode(',') }}] },
          selectedTab: 0,
          bucksAmount: {{ old('bucks', 'null') }}
          }">

        <div class="text-center">
            <img alt="{{ __('app logo') }}" src="{{ asset('assets/img/icon-fullsize.png') }}" class="w-28 m-auto">
            <h1 class="font-bold leading-none tracking-tighter text-white text-4xl">{{ $edugroup->name }}</h1>
            <p class="text-white text-2xl">{{ $course->name }}</p>
            <p class="text-white">{{ $kiosk->created_at->format('d.m. H:i') }}</p>

            <div class="absolute top-2 right-auto sm:right-0 flex sm:flex-col flex-row gap-2 sm:ms-auto ms-2">
                <x-button-dark :href="route('kiosk.student.index', $kiosk)" class="w-full">
                    {{ __('Version for students') }}
                </x-button-dark>
                <x-button-dark x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-end-session')"
                               class="w-full">
                    {{ __('End session') }}
                </x-button-dark>
            </div>
        </div>

        <ul class="flex flex-wrap text-sm text-center border-default mx-6 -my-2 sm:-my-4 gap-2 ">
            <li @click="selectedTab = 0; selectedStudents = [];">
                <span
                    class="inline-block p-4 pb-2 rounded-t-2xl cursor-pointer hover:bg-white transition-colors ease-in-out duration-200"
                    :class="selectedTab === 0 ? 'bg-white' : 'bg-slate-300'">{{ __('Knowledge units') }}</span>
            </li>
            <li @click="selectedTab = 1; selectedStudents = [];">
                <span
                    class="inline-block p-4 pb-2 rounded-t-2xl cursor-pointer hover:bg-white transition-colors ease-in-out duration-200"
                    :class="selectedTab === 1 ? 'bg-white' : 'bg-slate-300'">{{ __('Brain Bucks') }}</span>
            </li>
            <li @click="selectedTab = 2">
                <span
                    class="inline-block p-4 pb-2 rounded-t-2xl cursor-pointer hover:bg-white transition-colors ease-in-out duration-200"
                    :class="selectedTab === 2 ? 'bg-white' : 'bg-slate-300'">{{ __('History') }}</span>
            </li>
        </ul>

        <div x-show="selectedTab === 0">
            <x-card>
                <p class="text-center mb-4">
                    <x-button-outline class="float-start" @click="selectAll()"><i class="fa-solid fa-user-check"></i>
                    </x-button-outline>
                    {{ __('Select students') }}
                    <span x-text="selectedStudents.length"></span>/{{ $students->count() }}
                    <i class="fa-solid fa-user w-4 h-4 text-gray-400 inline-block"></i>
                    <template x-if="selectedStudents.length > 0"><i class="fa-solid fa-circle-check"></i></template>
                    <a href="{{ route('kiosk.attendance', $kiosk) }}"
                       class="hover:underline text-blue-700 float-end">{{ __('Edit attendance') }}</a>
                </p>
                <div class="grid grid-cols-2 sm:grid-cols-6 gap-4">
                    @foreach($students as $student)
                        @include('student.partials.student-card')
                    @endforeach
                </div>
            </x-card>
        </div>

        <div x-show="selectedTab === 0">
            <x-card>
                <p class="text-center mb-2">{{ __('Select knowledge') }}
                    <template x-if="selectedKnowledge"><i class="fa-solid fa-circle-check"></i></template>
                </p>
                <x-knowledge-tree :edufields="$edufields" :mode="'kiosk'" :formName="'give-knowledge'"/>
            </x-card>
        </div>

        <div x-show="selectedTab === 0">
            <x-card>
                <p class="text-center mb-2">{{ __('Select knowledge level') }}
                    <template x-if="selectedLevel"><i class="fa-solid fa-circle-check"></i></template>
                </p>
                <div class="grid sm:grid-cols-3 grid-cols-2 gap-2 mt-2">
                    @foreach($knowledgeLevels as $level)
                        <label>
                            <span
                                class="border-slate-200 border rounded-md w-full p-2 flex flex-row items-center hover:bg-slate-100 cursor-pointer"
                                title="{{ $level->description }}">
                                <img src="{{ asset('assets/img/knowledge-icons/' . $level->icon) }}"
                                     alt="{{ $level->name }}" class="w-6 h-6 inline-block me-1.5">
                                {{ $level->name }}
                                <input type="radio" name="level_id" value="{{ $level->id }}" form="give-knowledge"
                                       class="ms-auto" x-model="selectedLevel">
                            </span>
                        </label>
                    @endforeach
                </div>
            </x-card>
        </div>

        <div x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-give-knowledge')"
             x-show="selectedTab === 0">
            <x-button-dark class="sm:w-auto w-full float-end">
                {{ __('Confirm') }}
            </x-button-dark>
        </div>

        <div x-cloak x-show="selectedTab === 1">
            <x-card>
                <p class="text-center mb-4">
                    <x-button-outline class="float-start" @click="selectAll()"><i class="fa-solid fa-user-check"></i>
                    </x-button-outline>
                    {{ __('Select students') }}
                    <span x-text="selectedStudents.length"></span>/{{ $students->count() }}
                    <i class="fa-solid fa-user w-4 h-4 text-gray-400 inline-block"></i>
                    <template x-if="selectedStudents.length > 0"><i class="fa-solid fa-circle-check"></i></template>
                    <a href="{{ route('kiosk.attendance', $kiosk) }}"
                       class="hover:underline text-blue-700 float-end">{{ __('Edit attendance') }}</a>
                </p>
                <div class="grid grid-cols-2 sm:grid-cols-6 gap-4">
                    @foreach($students as $student)
                        @include('student.partials.student-card', ['showBucks' => true])
                    @endforeach
                </div>
            </x-card>
        </div>

        @php
            $maxBucksPerTransaction = config('school.economy.max_bucks_given_per_transaction');
        @endphp
        <div x-show="selectedTab === 1" class="flex flex-col sm:flex-row gap-4">
            <x-card class="w-full sm:w-1/2">
                <p class="text-center mb-2">{{ __('Brain Bucks') }}
                    <template x-if="bucksAmount && (bucksAmount >= -{{ $maxBucksPerTransaction }} && bucksAmount <= {{ $maxBucksPerTransaction }})">
                        <i class="fa-solid fa-circle-check"></i>
                    </template>
                </p>
                <div>
                    <x-input-label for="give-bucks-amount">{{ __('Amount of Brain Bucks') }}</x-input-label>
                    <x-input-text id="give-bucks-amount" class="w-full mt-1" name="bucks" x-model="bucksAmount" type="number" min="-{{ $maxBucksPerTransaction }}" max="{{ $maxBucksPerTransaction }}"></x-input-text>
                    <x-input-error :messages="$errors->get('bucks')" class="mt-2"/>
                </div>
                <x-button-dark class="sm:w-auto w-full mt-4 float-end" x-on:click.prevent="$dispatch('open-modal', 'confirm-give-bucks')">
                    {{ __('Confirm') }}
                </x-button-dark>
            </x-card>

            <x-card class="w-full sm:w-1/2 text-slate-500 text-sm text-justify">
                <p class="text-center mb-2 text-slate-800 text-base">{{ __('Giving Brain Bucks') }}</p>
                <p>{{ __('Brain Bucks are in-game currency that students can use to purchase various cosmetic upgrades. They serve as an incentive for students to engage more actively in their learning process. Teachers can award Brain Bucks to students for completing assignments, participating in class activities, or achieving specific milestones.') }}</p>
                <p class="mt-1">{!! __('Prices of in-game items span from :min to :max :buck.', ['min' => collect(config('school.economy.prices'))->min(), 'max' => collect(config('school.economy.prices'))->max(), 'buck' => '<i class=\'fa-solid fa-money-bill text-base\'></i>']) !!}</p>
                <p class="mt-1">{!! __('Maximum amount of Brain Bucks given at once is :max :buck. Through this form, Brain Bucks can also be deducted.', ['max' => $maxBucksPerTransaction, 'buck' => '<i class=\'fa-solid fa-money-bill text-base\'></i>']) !!}</p>
            </x-card>
        </div>

        <div x-cloak x-show="selectedTab === 2">
            <x-card>
                <p class="text-center mb-2">{{ __('History of given knowledge during this session') }}</p>
                <ul>
                    @forelse($history as $log)
                        <li class="w-full">
                            <div class="inline-block me-1 align-middle">
                                <x-student-profile-pic class="w-6 h-6" :student="$log->student"/>
                            </div>
                            {{ $log->student->first_name }} {{ $log->student->last_name }}
                            &middot;
                            {{ $log->knowledge->name }}
                            &middot;
                            <img src="{{ asset('assets/img/knowledge-icons/' . $log->level->icon) }}" alt="{{ $log->level->name }}" class="w-6 h-6 inline-block">
                            &middot;
                            <span class="text-slate-400 text-end">{{ \Carbon\Carbon::create($log->updated_at)->format('H:i d.m.') }}</span>
                        </li>
                    @empty
                        <li class="text-gray-400">{{ __('You have not given any knowledge during this session.') }}</li>
                    @endforelse
                </ul>
            </x-card>
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

        <x-modal name="confirm-give-knowledge" :show="$errors->confirmGiveKnowledge->has('pin')" focusable>
            <form id="give-knowledge" action="{{ route('kiosk.give-knowledge', $kiosk) }}" method="POST"
                  class="w-full text-center sm:text-start p-6">
                @csrf
                <h2 class=" text-lg mb-1">{{ __('Confirm giving knowledge') }}</h2>

                <template x-for="studentId in selectedStudents" :key="'input-'+studentId">
                    <input type="hidden" name="students[]" :value="studentId">
                </template>

                <div>
                    <x-input-label
                        for="give-knowledge-pin">{{ __('Teacher\'s PIN (:name)', ['name' => $teacherName]) }}</x-input-label>
                    <x-input-text id="give-knowledge-pin" class="w-full mt-1" name="pin" type="password" minlength="4"
                                  maxlength="20"></x-input-text>
                    <x-input-error :messages="$errors->confirmGiveKnowledge->get('pin')" class="mt-2"/>
                </div>

                <div class="w-full flex justify-end mt-4">
                    <x-button-dark class="sm:w-auto w-full">
                        {{ __('Confirm') }}
                    </x-button-dark>
                </div>
            </form>
        </x-modal>

        <x-modal name="confirm-end-session" :show="$errors->confirmEndSession->has('pin')" focusable>
            <form action="{{ route('kiosk.end', $kiosk) }}" method="POST" class="w-full text-center sm:text-start p-6">
                @csrf
                @method('PATCH')
                <h2 class=" text-lg mb-1">{{ __('Confirm ending the session') }}</h2>

                <div>
                    <x-input-label
                        for="end-session-pin">{{ __('Teacher\'s PIN (:name)', ['name' => $teacherName]) }}</x-input-label>
                    <x-input-text id="end-session-pin" class="w-full mt-1" name="pin" type="password" minlength="4"
                                  maxlength="20"></x-input-text>
                    <x-input-error :messages="$errors->confirmEndSession->get('pin')" class="mt-2"/>
                </div>

                <div class="w-full flex justify-end mt-4">
                    <x-button-dark class="sm:w-auto w-full">
                        {{ __('Confirm') }}
                    </x-button-dark>
                </div>
            </form>
        </x-modal>

        <x-modal name="confirm-give-bucks" :show="$errors->confirmGiveBucks->has('pin')" focusable>
            <form id="give-bucks" action="{{ route('kiosk.give-bucks', $kiosk) }}" method="POST"
                  class="w-full text-center sm:text-start p-6">
                @csrf
                <h2 class=" text-lg mb-1">{{ __('Confirm giving bucks') }}</h2>

                <template x-for="studentId in selectedStudents" :key="'input-'+studentId">
                    <input type="hidden" name="students[]" :value="studentId">
                </template>

                <input type="hidden" name="bucks" :value="bucksAmount">

                <div>
                    <x-input-label for="give-bucks-pin">{{ __('Teacher\'s PIN (:name)', ['name' => $teacherName]) }}</x-input-label>
                    <x-input-text id="give-bucks-pin" class="w-full mt-1" name="pin" type="password" minlength="4" maxlength="20"></x-input-text>
                    <x-input-error :messages="$errors->confirmGiveBucks->get('pin')" class="mt-2"/>
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
