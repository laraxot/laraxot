<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @hasSection('title')
            <title>@yield('title') - {{ config('app.name') }}</title>
        @else
            <title>{{ config('app.name') }}</title>
        @endif

        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ url(asset('favicon.ico')) }}">

        <!-- Tailwind CSS + Test overrides (includes Filament wizard parity) -->
        @vite(['resources/css/app-test.css'], 'themes/Sixteen')

        <!-- Filament Styles (required for Filament components in frontend) -->
        @filamentStyles

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>

    <body>
        @yield('body')

        @livewire('notifications')
        @filamentScripts
        @vite(['resources/js/app.js'], 'themes/Sixteen')

        {{-- Banner cookie: lo inietta CookieConsentMiddleware (vista Modules/Gdpr/resources/views/cookie-consent, DCP-153); nessun include qui. --}}
    </body>
</html>
