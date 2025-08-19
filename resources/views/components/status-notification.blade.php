{{--@props(['status'])--}}

@session('notification')
    <div
        class="bg-gradient-green sm:absolute max-w-full sm:max-w-2xl w-full top-0 sm:left-1/2 sm:-translate-x-1/2 py-3 px-4 sm:m-2 rounded-none sm:rounded-md z-50 cursor-pointer"
        x-data="{ open: true, hideAfter() { setTimeout(() => { this.open = false}, 4000) } }"
        role="alert"
        @click="open = false"
        x-show="open"
        x-init="hideAfter()"
        x-transition:enter="transition ease-out duration-250"
        x-transition:enter-start="-translate-y-full"
        x-transition:enter-end="translate-y-0 "
        x-transition:leave="transition ease-out duration-250"
        x-transition:leave-start="translate-y-0 "
        x-transition:leave-end="-translate-y-full">
        <span class="text-white">{{ $value }}</span>
    </div>
@endsession

@foreach($errors->all() as $error)
    <div
        class="bg-gradient-red sm:absolute max-w-full sm:max-w-2xl w-full top-0 sm:left-1/2 sm:-translate-x-1/2 py-3 px-4 sm:m-2 rounded-none sm:rounded-md z-50 cursor-pointer"
        x-data="{ open: true, hideAfter() { setTimeout(() => { this.open = false}, 4000) } }"
        role="alert"
        @click="open = false"
        x-show="open"
        x-init="hideAfter()"
        x-transition:enter="transition ease-out duration-250"
        x-transition:enter-start="-translate-y-full"
        x-transition:enter-end="translate-y-0 "
        x-transition:leave="transition ease-out duration-250"
        x-transition:leave-start="translate-y-0 "
        x-transition:leave-end="-translate-y-full">
        <span class="text-white">{{ $error }}</span>
    </div>
@endforeach
