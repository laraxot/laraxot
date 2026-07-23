<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Comune di ' . config('comune.nome', 'Nome Comune'))</title>
    
    <!-- Bootstrap Italia CSS removed: use Tailwind + theme assets (design-comuni). See Themes/Sixteen/docs/REMOVE_BOOTSTRAP_CDN.md -->
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ theme_asset('css/design-comuni.css') }}" rel="stylesheet">
    <link href="{{ theme_asset('css/comune-custom.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body>
    @include('sixteen::components.header-comune')
    
    <main id="main-content">
        @yield('content')
    </main>
    
    @include('sixteen::components.footer-comune')
    
    <!-- Bootstrap Italia JS removed: theme uses Tailwind/Alpine/Lit. If needed, include local assets via design-comuni. See Themes/Sixteen/docs/REMOVE_BOOTSTRAP_CDN.md -->
    
    <!-- Leaflet JS per le mappe -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- Custom JS -->
    <script src="{{ theme_asset('js/design-comuni.js') }}"></script>
    <script src="{{ theme_asset('js/comune-functions.js') }}"></script>
    
    @stack('scripts')
</body>
</html>



