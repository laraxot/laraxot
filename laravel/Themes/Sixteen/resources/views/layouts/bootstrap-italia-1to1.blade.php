@props(['pageSlug' => '', 'title' => 'Homepage'])

{{-- EXACT Bootstrap Italia Page - 1:1 Replication from view-source --}}
{{-- Source: https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html --}}
{{-- HTML MUST be IDENTICAL to upstream (except scripts) --}}

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title }} - {{ $municipality ?? 'Comune' }}</title>
  
  {{-- Tailwind CSS (replaces Bootstrap Italia CSS) --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
  
  {{-- Skip Links - EXACT from upstream --}}
  <div class="skiplink">
    <a class="visually-hidden-focusable" href="#main-container">Vai ai contenuti</a>
    <a class="visually-hidden-focusable" href="#footer">Vai al footer</a>
  </div><!-- /skiplink -->
  
  {{-- Header - EXACT from upstream --}}
  <x-blocks.header.exact-1to1
    region="Nome della Regione"
    municipality="NOME DEL COMUNE"
    subtitle="Un comune da vivere"
    language="ITA"
  />
  
  {{-- Main Container - EXACT from upstream --}}
  <main class="container" id="main-container">
    <div class="row">
      <div class="col-12">
        
        {{-- Page Content --}}
        @yield('content')
        
      </div>
    </div>
  </main>
  
  {{-- Footer - EXACT from upstream --}}
  <footer class="it-footer" id="footer">
    <x-pub_theme::blocks.footer.exact-1to1 />
  </footer>
  
</body>
</html>
