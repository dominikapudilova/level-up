<x-app-layout>

    <x-slot name="header" >
        <h2 class="font-semibold text-xl text-slate-700 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>


    <x-cover-image/>

    <div class="m-4 space-x-4">
        <x-button-dark :href="route('kiosk.create')">{{ __('Start lesson') }}</x-button-dark>
    </div>

    @if(!$kiosks->isEmpty())
        <div class="flex flex-row items-center space-x-2 w-full">
            <hr class="grow">
            <h3 class="uppercase text-sm text-slate-500">{{ __('Resume kiosk session') }}</h3>
            <hr class="grow">
        </div>
        @foreach($kiosks as $kiosk)
            <x-card class="sm:mx-4 sm:mt-4 mt-2">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold">{{ $kiosk->edugroup->name }} – {{ $kiosk->course->name }}
                            <span class="ms-2 text-slate-400 text-sm">{{ $kiosk->attendances->count() }}</span>
                            <i class="fa-solid fa-user w-4 h-4 text-sm text-gray-400 inline-block"></i>
                        </h3>
                        <p class="text-sm text-gray-500">
                            <span class="sm:inline hidden">{{ __('Started at') }}: </span>
                            {{ $kiosk->started_at->format('d.m. Y H:i') }}
                            <x-badge>{{ $kiosk->started_at->diffForHumans() }}</x-badge>
                        </p>
                    </div>
                    <div class="shrink-0 flex items-center gap-2">
                        <x-button-dark :href="route('kiosk.session', $kiosk->id)">{{ __('Enter') }}</x-button-dark>
                        <form method="POST" action="{{ route('kiosk.end', $kiosk) }}"> @csrf @method('PATCH')
                            <button type="submit" class="inline-block text-slate-400 hover:text-slate-800 cursor-pointer">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </x-card>
        @endforeach
    @endif

</x-app-layout>
