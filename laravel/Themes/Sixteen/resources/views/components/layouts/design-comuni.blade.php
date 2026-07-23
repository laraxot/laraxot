{{--
    Layout Design Comuni - Bootstrap Italia EXACT
    Use: <x-pub_theme::layouts.design-comuni>
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'], 'themes/Sixteen')
</head>
<body>
    {{-- Skip Links - EXACT Bootstrap Italia Structure --}}
    <div class="skiplink">
        <a class="visually-hidden-focusable" href="#main-container">Vai ai contenuti</a>
        <a class="visually-hidden-focusable" href="#footer">Vai al
            footer</a>
    </div><!-- /skiplink -->

    {{-- Header - Bootstrap Italia EXACT Structure --}}
    <x-section slug="header" />

    {{-- Main Content --}}
    <main id="main-container">
        {{ $slot }}
    </main>

    {{-- Footer - Bootstrap Italia EXACT Structure --}}
    <x-section slug="footer" />

    {{-- Scripts --}}
    @stack('scripts')
</body>
</html>
