<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('dashboard') }}" class="hover:underline">&laquo;&nbsp;{{ __('Back') }}</a>
            <h2 class="ms-2 inline-block font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Start new lesson') }}
            </h2>
        </div>
    </x-slot>

    <x-cover-image/>

    <div class="flex gap-4 flex-col sm:flex-row justify-center mt-2 sm:-mt-12 z-0">
        <x-card class="text-sm sm:w-1/2 w-full sm:mx-0 bg-milky-glass">
            <h5 class="text-slate-600 text-base mb-4">{{ __('Start new lesson') }}</h5>
            <form class="w-full" method="POST" action="{{ route('kiosk.store') }}">
                @csrf

                <div x-data="searchCoursesComponent()" class="space-y-4 w-full">

                    <div>
                        <x-input-label for="edugroup_id" class="block">{{ __('Select your group') }}</x-input-label>

                        @if($edugroups->isEmpty())
                            <p class="text-red-500">{{ __('No groups available. Please create a group first.') }}</p>
                        @else
                            <select @change="getCourses" x-model="selectedGroup" class="border border-slate-600 rounded p-1 w-full mt-1" id="edugroup_id" name="edugroup_id">
                                <option value=""></option>
                                @foreach($edugroups as $edugroup)
                                    <option value="{{ $edugroup->id }}">{{ $edugroup->name }}</option>
                                @endforeach
                            </select>
                        @endif
                        <x-input-error :messages="$errors->get('edugroup_id')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="course_id" class="block">{{ __('Select course') }}</x-input-label>
                        <select :disabled="courses.length <= 0" class="border border-slate-600 rounded p-1 w-full mt-1" id="course_id" name="course_id">
                            <template x-for="(course, index) in courses" :key="course.id" >
                                <option x-text="course.name" :value="course.id" :selected="index === 0"></option>
                            </template>
                        </select>
                        <x-input-error :messages="$errors->get('course_id')" class="mt-2"/>
                    </div>

                </div>

                <x-button-dark class="mt-4 float-end">
                    {{ __('Start lesson') }}
                </x-button-dark>
            </form>
        </x-card>
    </div>

    <script>
        function searchCoursesComponent() {
            return {
                selectedGroup: '',
                courses: [],
                async getCourses() {
                    if (!this.selectedGroup) {
                        this.courses = [];
                        return;
                    }
                    let response = await fetch(`{{ route('kiosk.search-courses') }}?edugroup_id=${this.selectedGroup}`);
                    this.courses = await response.json();
                }
            }
        }
    </script>

</x-app-layout>
