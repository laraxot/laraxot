@props([
    'title' => '',
    'siteName' => config('app.name'),
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ? "$title — " : '' }}{{ config('app.name') }}</title>
        {{--
        @vite(['resources/css/app.css'])

        @livewireStyles
        --}}

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
        @filamentStyles
        @vite(['Resources/css/filament/admin/theme.css', 'Resources/css/app.css'], 'themes/Two/dist')
        {{-- <link crossorigin="anonymous" media="all" rel="stylesheet" href="{{ $_theme->asset('pub_theme::dist/assets/theme.css') }}" />
        <link crossorigin="anonymous" media="all" rel="stylesheet" href="{{ $_theme->asset('pub_theme::dist/assets/app.css') }}" /> --}}

        {{--
        @vite(['Resources/css/filament/admin/theme.css','Resources/css/app.css'],'themes/Two/dist')
        --}}

    </head>
    <body class="bg-white">
        <div class="flex flex-col min-h-screen">
            <header class="bg-black text-white">
                <x-std tpl='container'>
                    <nav class="main-nav flex items-center">
                        @if ($siteName)
                            <div class="text-2xl">
                                <a href="/">{{ $siteName }}</a>
                            </div>
                        @endif

                        <x-menu name="main" />
                    </nav>
                </x-std >
            </header>

            <main>
                {!! $slot ?? '' !!}
            </main>

            <div class="mt-16"></div>

            <footer class="mt-auto text-center">
                <x-std tpl='container' class="text-gray-700">
                    <div class="flex flex-col lg:flex-row items-center justify-center space-x-4">
                        <span>Copyright © {{ date('Y') }} ACME inc.</span>
                        <x-menu name="footer" />
                    </div>
                </x-std>
            </footer>
        </div>
        {{--
        @livewireScripts
        --}}
        @livewire('notifications')

        @filamentScripts
        @vite(['Resources/js/app.js'], 'themes/Two/dist')

        {{-- <script src="{{ $_theme->asset('pub_theme::dist/assets/app2.js') }}" ></script> --}}
        {{--
        @vite('Resources/js/app.js','../laravel/Themes/Two/Resources/dist')
        --}}
    </body>
</html>
