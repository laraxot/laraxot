<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>
    @if(isset($description))
        <meta name="description" content="{{ $description }}">
    @endif

    <meta property="og:title" content="{{ $title ?? config('app.name', 'Laravel') }}">
    @if(isset($description))
        <meta property="og:description" content="{{ $description }}">
    @endif
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ request()->url() }}">
    <link rel="canonical" href="{{ request()->url() }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @livewireStyles
    @filamentStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'], 'themes/Zero')

    {{--
        This page renders a Filament schema widget (LoginWidget) outside of any
        Filament panel. @filamentStyles alone only emits shared bits (color
        variables, wire:loading, nprogress) — the actual .fi-icon/.fi-btn/.fi-input
        component rules only exist inside a panel's own compiled Tailwind bundle,
        which Filament has no reason to enqueue without an active panel context.
        Loading the admin panel's compiled CSS directly is the correct fix (it's
        the real source of those classes, not a hand-guessed reimplementation);
        the cost is one cached ~90KB gzipped request, once per session.
    --}}
    <link rel="stylesheet" href="{{ asset('css/filament/filament/app.css') }}">

    @stack('styles')
</head>
<body data-auth-page class="h-full font-sans antialiased bg-primary-50">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-primary-600 text-white px-4 py-2 rounded-md z-50">
        {{ __('Vai al contenuto principale') }}
    </a>

    <div class="relative min-h-screen overflow-hidden bg-primary-50">
        <!-- Ambient background: decorative only, frozen under prefers-reduced-motion (see app.css .auth-ambient) -->
        <div class="auth-ambient" aria-hidden="true">
            <div class="auth-ambient__blob auth-ambient__blob--one"></div>
            <div class="auth-ambient__blob auth-ambient__blob--two"></div>
            <div class="auth-ambient__blob auth-ambient__blob--three"></div>
        </div>

        <div class="relative z-10 flex min-h-screen flex-col">
            <header class="flex items-center justify-between px-4 pt-6 sm:px-8 sm:pt-8" data-auth-header>
                <a href="{{ url('/'.app()->getLocale()) }}" class="flex items-center gap-3 rounded-md text-primary-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500" aria-label="{{ config('app.name', 'Laravel') }}">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary-600 text-white shadow-lg shadow-primary-900/15">
                        <x-heroicon-o-building-storefront class="h-5 w-5" aria-hidden="true" />
                    </span>
                    <span class="font-serif text-xl font-bold tracking-tight">{{ config('app.name', 'Laravel') }}</span>
                </a>
                <span class="hidden text-xs font-semibold uppercase tracking-[0.18em] text-primary-700/70 sm:inline">{{ __('Area riservata') }}</span>
            </header>

            <main id="main-content" class="flex flex-1 items-center justify-center px-4 py-10 sm:px-6" data-auth-main>
                {{ $slot }}
            </main>

            <footer class="pb-6 text-center text-xs text-primary-700/70" data-auth-footer>
                &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}
            </footer>
        </div>
    </div>

    @filamentScripts(withCore: true)
    @stack('scripts')
</body>
</html>
