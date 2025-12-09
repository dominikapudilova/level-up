<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LevelUp') }} | {{ __('Kiosk') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
{{--        <link href="https://fonts.bunny.net/css?family=open-sans:400,500,600&display=swap" rel="stylesheet" />--}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js']){{--'resources/css/softui.css'--}}

        <style> [x-cloak] {display: none !important;} </style>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-100">
        <div class="min-h-screen flex flex-col  items-center sm:pt-0 relative">

            <x-status-notification class="mb-4" :status="session('status')"/>

            {{ $slot }}
        </div>
    </body>
</html>
