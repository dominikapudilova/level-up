@props([
    'student',
    'interactive' => false,
    'img' => null,
    'bg' => null,
    'theme' => null,
    'nobg' => false, // leave background empty
])
@php
$img = $img ?? ($student->avatar ?? 'YOUR-TEXT');
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
