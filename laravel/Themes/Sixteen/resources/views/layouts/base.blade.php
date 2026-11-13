<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
    <x-cms::head />

    <body>
        @yield('body')

        @filamentScripts

    </body>
</html>
