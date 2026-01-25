@props([
    'student',
    'interactive' => false,
    'img' => null,
    'bg' => null,
    'theme' => null,
    'nobg' => false, // leave background empty
    'showPhoto' => false
])
@if($showPhoto === true)

    @if($student->photo === null)
        <img src="{{ asset('assets/img/empty-photo.png') }}"
             alt="{{ __(':name\'s profile photo', ['name' => $student->nickname]) }}"
            {{ $attributes->merge([ 'class' => "max-w-24 max-h-24 rounded-xl " ]) }}>
    @else
        <img src="{{ asset('storage/' . $student->photo) }}"
             alt="{{ __(':name\'s profile photo', ['name' => $student->nickname]) }}"
            {{ $attributes->merge([ 'class' => "max-w-24 max-h-24 rounded-xl " ]) }}>
    @endif

@else
    @php
    $img = $img ?? ($student->avatar ?? 'YOUR-TEXT.png');
    $bg = $bg ?? $student->background_image;
    $theme = $theme ?? ($student->theme ?? 'dark');
    @endphp


    <div @if($bg && ! $nobg)
             style="background-image: url('{{ asset('assets/img/backgrounds/' . $bg) }}');
             background-size: 100%;
             background-repeat: repeat"
        @endif
        {{ $attributes->merge([
           'class' => "flex justify-center rounded-xl bg-white overflow-hidden " . ($nobg ? "" : " bg-gradient-$theme ") . ($interactive ? ' cursor-pointer transition-all hover:scale-105 hover:shadow-lg ' : '')
       ]) }}>

        @if(!$slot->isEmpty())
            {{ $slot }}
        @else
            <img src="{{ asset('assets/img/avatars/' . $img) }}" alt="{{ __(':name\'s profile picture', ['name' => $student->nickname]) }}">
        @endif
    </div>
@endif
