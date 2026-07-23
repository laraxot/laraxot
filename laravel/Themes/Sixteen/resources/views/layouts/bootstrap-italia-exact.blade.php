@props(['pageSlug' => ''])

{{-- EXACT Bootstrap Italia Page Structure --}}
{{-- HTML MUST match upstream exactly --}}

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Homepage' }} - {{ $municipality ?? 'Comune' }}</title>
    
    {{-- Tailwind CSS (replaces Bootstrap Italia CSS) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
  
  {{-- Skip Links --}}
  <div class="skiplink">
    <a class="visually-hidden-focusable" href="#main-container">Vai ai contenuti</a>
    <a class="visually-hidden-focusable" href="#footer">Vai al footer</a>
  </div>
  
  {{-- Header --}}
  <x-blocks.header.exact 
    region="Nome della Regione"
    municipality="NOME DEL COMUNE"
    subtitle="Un comune da vivere"
    language="ITA"
  />
  
  {{-- Main Container --}}
  <main class="container" id="main-container">
    <div class="row">
      <div class="col-12">
        
        {{-- Page Content --}}
        @yield('content')
        
      </div>
    </div>
  </main>
  
  {{-- Footer --}}
  <footer class="it-footer" id="footer">
    <x-pub_theme::blocks.footer.exact-1to1 />
  </footer>
  
</body>
</html>
