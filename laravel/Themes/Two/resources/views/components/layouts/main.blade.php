@props([
    'title' => '',
])
@php
    $metatag = \Modules\Xot\Datas\MetatagData::make();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="application-name" content="{{ config('app.name') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $metatag->getTitle() ?? ($title ? "$title — " : '') }}{{ config('app.name') }}</title>
        @if($metatag->getDescription())
        <meta name="description" content="{{ $metatag->getDescription() }}">
        @endif

        {{-- Critical CSS inline --}}
        <style>[x-cloak] { display: none !important; }</style>

        {{-- Non-blocking CSS loading --}}
        @filamentStyles
        @vite(['resources/css/app.css'], 'themes/Two')
    </head>
    <body class="antialiased font-sans bg-white text-gray-900">
        {{-- Skip to main content link for accessibility --}}
        <a href="#main-content" 
           class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-[9999] focus:px-4 focus:py-2 focus:bg-[#1E5A96] focus:text-white focus:rounded-md focus:ring-2 focus:ring-offset-2 focus:ring-[#1E5A96]">
            Vai al contenuto principale
        </a>

        @if(isset($beforeMain))
            {!! $beforeMain !!}
        @endif

        <main id="main-content" role="main">
            {!! $slot ?? '' !!}
        </main>

        @if(isset($afterMain))
            {!! $afterMain !!}
        @endif

        {{-- Livewire and Filament Scripts --}}
        @livewireScripts
        @filamentScripts
        
        {{-- Application JS --}}
        @vite(['resources/js/app.js'], 'themes/Two')
    </body>
</html>
