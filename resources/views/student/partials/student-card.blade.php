
<div class="border cursor-pointer rounded-md h-36 shadow-md relative transition-all hover:scale-105 hover:shadow-lg ring-offset-1 ring-4"
     @click="typeof toggleStudentSelection === 'function' ? (toggleStudentSelection({{ $student->id }})) : null"
     :class="typeof isSelected === 'function' ? (isSelected({{ $student->id }}) ? 'ring-emerald-400' : 'ring-rose-400') : ''">

    <div class="h-1/2 rounded-t-md bg-gradient-{{$student->theme}}"
         style=" @if($student->background_image) background-image: url('{{ asset("assets/img/backgrounds/") }}/{{ $student->background_image }}'); background-size: cover @endif"
    ></div>

    <div class="absolute top-0 left-0 right-0 bottom-0 text-center">
        <div class="max-w-20 max-h-20 mx-auto mt-3 mb-1 aspect-square rounded-full bg-white overflow-hidden">
            <x-student-profile-pic :student="$student" :nobg="true" :showPhoto="isset($showPhotos) && $showPhotos === true" class="w-full h-full object-cover"/>
        </div>
        <p class="truncate">
            @if(isset($showNickname) && $showNickname)
                <span class="">{{ $student->nickname }}</span>
            @else
                <span class="uppercase">{{ $student->first_name }}</span><span class="text-slate-500"> {{ $student->last_name }}</span>
            @endif
        </p>
        <p class="text-slate-400 text-xs">
            {{ __('Level') }} {{ $student->getLevel() }}
            @if(isset($showBucks) && $showBucks)
                &middot; <span class="text-slate-600">{{ $student->bucks }} <i class="fa-solid fa-money-bill"></i></span>
            @endif
        </p>
    </div>
</div>
