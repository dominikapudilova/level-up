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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js']){{--'resources/css/softui.css'--}}
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-100">
        <div class="min-h-screen flex flex-col  items-center sm:pt-0 pt-6 relative">

{{--            <div class="container position-sticky z-index-sticky top-0">--}}
{{--                <div class="row">--}}
{{--                    <div class="col-12">--}}

                        {{ $slot }}

{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </body>
</html>
