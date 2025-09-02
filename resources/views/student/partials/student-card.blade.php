
<div class="border cursor-pointer rounded-md h-36 shadow-md relative transition-all hover:scale-105 hover:shadow-lg ring-offset-1 ring-4" @click="toggleStudentSelection({{ $student->id }})" :class="isSelected({{ $student->id }}) ? 'ring-emerald-400' : 'ring-rose-400'">
    <div class="h-1/2 rounded-t-md" style="background-image: url('{{ asset("assets/img/patterns/") }}/{{ $student->background_image ?? 'pattern5.png' }}'); background-size: cover"></div>

    <div class="absolute top-0 left-0 right-0 bottom-0 text-center">
        <div class="max-w-20 max-h-20 mx-auto mt-3 mb-1 aspect-square rounded-full bg-white overflow-hidden">
            <img src="https://robohash.org/{{ $student->avatar ?? 'YOUR-TEXT' }}.png?set=set1" alt="{{ __('student avatar') }}" class="w-full h-full object-cover">
        </div>
        <p class="truncate"><span class="uppercase">{{ $student->first_name }}</span><span class=" text-gray-500"> {{ $student->last_name }}</span></p>
        <p class="text-slate-400 text-xs">{{ __('Level') }} {{ $student->getLevel() }}</p>
    </div>
</div>
