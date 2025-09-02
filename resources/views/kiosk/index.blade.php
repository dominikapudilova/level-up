<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('dashboard') }}" class="hover:underline">&laquo;&nbsp;{{ __('Back') }}</a>
            <h2 class="ms-2 inline-block font-semibold text-xl text-gray-800 leading-tight">
                {{ __('View all active kiosk sessions') }}
            </h2>
        </div>
    </x-slot>

    <x-cover-image/>

    @if($kiosks->isEmpty())
        <x-card class="sm:mx-4 sm:mt-4">
            <p>{{ __('No sessions were found.') }}</p>
        </x-card>
    @else
        @foreach($kiosks as $kiosk)
            <x-card class="sm:mx-4 sm:mt-4 mt-2">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold">
                            {{ $kiosk->edugroup->name }} - {{ $kiosk->course->name }}
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
