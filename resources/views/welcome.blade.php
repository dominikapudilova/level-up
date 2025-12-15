<x-guest-layout>
    <div class="max-w-full w-full absolute top-0 -z-0 sm:min-h-[50vh] min-h-dvh" style="background-image: url('{{ asset('assets/img/backgrounds/'. collect(config('school.cosmetics.backgrounds'))->random() ) }}')">
        <span class="mask bg-gradient-dark opacity-80"></span>
    </div>

    <main class="z-10 text-center sm:mt-16 mt-0 w-full max-w-sm">
        <img alt="{{ __('app logo') }}" src="{{ asset('assets/img/icon-fullsize.png') }}" class="w-28 m-auto">
        <h1 class="font-bold leading-none tracking-tighter text-white text-4xl">{{ __('Welcome back') }}</h1>
{{--        <p class="text-white">{{ __('to LevelUp') }}</p>--}}
        <p class="text-white mt-2">{{ __('Please log in to continue.') }}</p>
        <section class="bg-white p-6 rounded-2xl mt-10 shadow-md">
            <form class="w-full text-start" method="POST" action="{{ route('login') }}">
                @csrf
                <x-input-label for="username">{{ __('Username') }}</x-input-label>
                <x-input-text id="username" class="w-full mt-1" name="username" :value="old('username')" required autofocus autocomplete></x-input-text>
                <x-input-error :messages="$errors->get('username')" class="mt-2"/>

                <x-input-label class="mt-4 block" for="password">{{ __('Password') }}</x-input-label>
                <x-input-text id="password" class="w-full mt-1" name="password" type="password" required></x-input-text>
                <x-input-error :messages="$errors->get('email')" class="mt-2"/>

                <x-button-dark class="w-full mt-5">
                    {{ __('Log in') }}
                </x-button-dark>
            </form>
        </section>
    </main>
</x-guest-layout>
