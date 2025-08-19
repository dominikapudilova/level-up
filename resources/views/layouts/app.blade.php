<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LevelUp') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
{{--        <link href="https://fonts.bunny.net/css?family=open-sans:400,500,600&display=swap" rel="stylesheet" />--}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style> [x-cloak] { display: none !important; } </style>
    </head>
    <body class="font-sans antialiased bg-gray-100 text-slate-700">
        <div class="min-h-screen flex flex-row" x-data="{ openNav: false }">
            @include('layouts.sidebar')

            <div class="w-full flex flex-col">
                {{--Page Notifications--}}
                <x-status-notification class="mb-4" :status="session('status')"/>

                {{--Page Heading--}}
                @isset($header)
                    <header class="p-4 flex items-center justify-between">
{{--                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">--}}
                        {{ $header }}
{{--                        </div>--}}

                        <div class="hidden sm:block">
                            <img src="https://robohash.org/YOUR-TEXT.png?size=28x28&set=set5" alt="{{ __('profile picture') }}" class="w-7 inline-block rounded-full bg-rose-300 me-2">
                            {{ auth()->user()->first_name }}&nbsp;{{ auth()->user()->last_name }}
                        </div>

                        {{--může být přesunuto -- musí být na každé stránce--}}
                        <button @click="openNav = true" class="text-gray-800  sm:hidden p-3 bg-white shadow-sm rounded-lg">
                            {{--Hamburger icon--}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </header>
                @endisset

                {{--Page Content--}}
                <main class="flex flex-col">
                    {{ $slot }}
                </main>
            </div>
        </div>

    </body>
</html>
