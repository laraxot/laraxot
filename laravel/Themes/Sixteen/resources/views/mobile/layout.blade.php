<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-theme="light">
@push('head')
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
@endpush
<x-cms::head theme-color="#007a52" />
<body class="bg-gray-50 text-gray-900 antialiased">
    <div id="app" class="min-h-dvh">
        {{ $slot }}
    </div>
    {{-- Il CSS lo rende <x-cms::head>; il JS del tema resta a fine body (module/defer). --}}
    @vite(['resources/js/app.js'], 'themes/Sixteen')

    @if(app()->environment('local'))
        <script src="http://localhost:8081/js/bundle.js" defer></script>
    @endif
</body>
</html>
