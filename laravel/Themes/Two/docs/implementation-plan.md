# Piano di Implementazione: Adattamento Sito Target

## Data: 6 Febbraio 2026

## Obiettivo
Trasformare il sito locale (http://127.0.0.1:8000/it) per renderlo identico al sito target (https://lightseagreen-dogfish-560272.hostingersite.com/)

## Fase 1: Configurazione Tailwind CSS

### 1.1 Aggiornare tailwind.config.js
```javascript
module.exports = {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        // Custom colors from target site
        primary: {
          DEFAULT: '#1E5A96',  // Blu scuro professionale
          light: '#2d72b0',
          lighter: '#5a8cc7',
          dark: '#164575',
        },
        secondary: {
          DEFAULT: '#2D8659',  // Verde per settore veterinario
          light: '#3da06a',
          lighter: '#6eb991',
          dark: '#247049',
        },
        accent: {
          DEFAULT: '#E67E22',  // Arancione per call-to-action
          light: '#e8953c',
          lighter: '#f0b670',
          dark: '#d35400',
        },
      },
      fontFamily: {
        sans: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
      },
      backgroundImage: {
        'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
        'gradient-to-br': 'linear-gradient(to bottom right, var(--tw-gradient-stops))',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}
```

### 1.2 Verificare resources/css/app.css
Assicurarsi che includa:
```css
@tailwind base;
@tailwind components;
@tailwind utilities;

/* Custom styles */
html {
  scroll-behavior: smooth;
}

body {
  font-family: Inter, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* Line clamp utilities */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
```

## Fase 2: Creazione Componenti Blade

### 2.1 Componente Navigation (header)
File: `resources/views/components/radioprotection-header.blade.php`

Struttura:
- Logo "RP" con cerchio blu
- Menu: Servizi, Settori, Controlli, Testimonianze, Contatti
- Bottone "Contattaci" arancione
- Fixed position con backdrop blur
- Responsive con menu mobile

### 2.2 Componente Hero Section
File: `resources/views/components/sections/hero.blade.php`

Contenuto:
- Titolo principale: "Radioprotezione e Sicurezza Radiologica..."
- Sottotitolo con descrizione
- Due CTA: "Richiedi Preventivo" e "Scopri i Servizi"
- Background gradient blu scuro
- Pattern radiale decorativo
- Icona freccia scroll down animata

### 2.3 Componente Services Cards
File: `resources/views/components/sections/services.blade.php`

Tre card:
1. Controllo Radioprotezione (icona scudo, bordo blu)
2. Controllo Elettromedicali (icona settings, bordo verde)
3. Documentazione e Conformità (icona documento, bordo arancione)

### 2.4 Componente Why Radioprotection
File: `resources/views/components/sections/why-radioprotection.blade.php`

Quattro punti:
- Rischi e Sanzioni (icona warning, rosso)
- Obblighi Normativi (icona check, blu)
- Sicurezza Persone (icona users, verde)
- Responsabilità Legale (icona gavel, giallo)

### 2.5 Componente Sectors
File: `resources/views/components/sections/sectors.blade.php`

Due colonne:
1. Odontoiatria (blu) con 3 servizi
2. Medicina Veterinaria (verde) con 3 servizi
- Immagini associate

### 2.6 Componente Controls
File: `resources/views/components/sections/controls.blade.php`

Cinque aree con icone colorate:
- Dosimetria (blu)
- Schermature (verde)
- Apparecchiature (arancione)
- Sistemi di Protezione (viola)
- Documentazione (grigio)

### 2.7 Componente Testimonials
File: `resources/views/components/sections/testimonials.blade.php`

Quattro testimonianze con:
- Foto avatar
- Nome e titolo
- Studio e città
- Citazione
- Data

### 2.8 Componente Resources
File: `resources/views/components/sections/resources.blade.php`

Due card per download PDF:
- Guida Radioprotezione Odontoiatrica (blu)
- Guida Radioprotezione Veterinaria (verde)

### 2.9 Componente Newsletter
File: `resources/views/components/sections/newsletter.blade.php`

Form iscrizione:
- Titolo e descrizione
- Input email
- Bottone "Iscriviti"

### 2.10 Componente Footer
File: `resources/views/components/radioprotection-footer.blade.php`

Struttura:
- Logo e descrizione
- Colonna Servizi
- Colonna Settori
- Colonna Contatti
- Copyright

## Fase 3: Aggiornamento Layout Principale

### 3.1 Nuovo Layout Blade
File: `resources/views/layouts/radioprotection.blade.php`

Struttura:
```blade
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Radioprotezione e Sicurezza Radiologica')</title>
    <meta name="description" content="@yield('description', 'Conformità normativa garantita per studi dentistici e veterinari')">
    
    @vite('resources/css/app.css', 'themes/Two')
</head>
<body class="antialiased font-sans bg-white text-gray-900">
    <x-radioprotection-header />
    
    <main class="min-h-screen">
        @yield('content')
    </main>
    
    <x-radioprotection-footer />
    
    @vite('resources/js/app.js', 'themes/Two')
</body>
</html>
```

## Fase 4: Creazione Pagina Home

### 4.1 Nuova Home Page
File: `resources/views/home-radioprotezione.blade.php`

Contenuto:
```blade
@extends('layouts.radioprotection')

@section('title', 'Radioprotezione e Sicurezza Radiologica per Studi Dentistici e Veterinari')

@section('description', 'Conformità normativa garantita, sicurezza pazienti e staff, controlli periodici certificati secondo D.Lgs 101/2020 e Direttiva 2013/59/Euratom.')

@section('content')
<x-sections.hero />
<x-sections.services />
<x-sections.why-radioprotection />
<x-sections.sectors />
<x-sections.controls />
<x-sections.testimonials />
<x-sections.resources />
<x-sections.newsletter />
@endsection
```

## Fase 5: Gestione Immagini

### 5.1 Copiare Immagini
```bash
# Creare directory
mkdir -p public_html/themes/Two/images

# Copiare da Main_files
cp laravel/Themes/Two/Main_files/images/*.jpg public_html/themes/Two/images/
```

### 5.2 Ottimizzare Immagini
- Comprimere con tool appropriati
- Creare versioni webp
- Implementare lazy loading

## Fase 6: Traduzioni

### 6.1 File di Traduzione Italiano
File: `lang/it/radioprotection.php`

```php
<?php

return [
    // Navigation
    'nav' => [
        'services' => 'Servizi',
        'sectors' => 'Settori',
        'controls' => 'Controlli',
        'testimonials' => 'Testimonianze',
        'contacts' => 'Contatti',
        'contact_us' => 'Contattaci',
    ],
    
    // Hero
    'hero' => [
        'title' => 'Radioprotezione e Sicurezza Radiologica',
        'subtitle' => 'per Studi Dentistici e Veterinari',
        'description' => 'Conformità normativa garantita, sicurezza pazienti e staff, controlli periodici certificati secondo D.Lgs 101/2020 e Direttiva 2013/59/Euratom.',
        'cta_primary' => 'Richiedi Preventivo',
        'cta_secondary' => 'Scopri i Servizi',
    ],
    
    // Services
    'services' => [
        'title' => 'I Nostri Servizi',
        'subtitle' => 'Soluzioni complete per la conformità normativa e la sicurezza radiologica',
        'radiation_protection' => [
            'title' => 'Controllo Radioprotezione',
            'description' => 'Verifiche periodiche e straordinarie per apparecchiature radiologiche in ambito odontoiatrico e veterinario. Garanzia di conformità al D.Lgs 101/2020.',
            'learn_more' => 'Scopri di più',
        ],
        // ... continue with other services
    ],
    
    // Continue with all other sections
];
```

## Fase 7: JavaScript Interactions

### 7.1 Smooth Scroll
```javascript
// resources/js/app.js
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});
```

### 7.2 Mobile Menu Toggle
```javascript
const menuButton = document.getElementById('mobile-menu-button');
const mobileMenu = document.getElementById('mobile-menu');

if (menuButton && mobileMenu) {
    menuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
}
```

### 7.3 Header Scroll Effect
```javascript
let lastScroll = 0;
const header = document.querySelector('header');

window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    
    if (currentScroll > 100) {
        header.classList.add('bg-white/20', 'backdrop-blur-md');
    } else {
        header.classList.remove('bg-white/20', 'backdrop-blur-md');
    }
    
    lastScroll = currentScroll;
});
```

## Fase 8: Build e Deploy

### 8.1 Development Build
```bash
cd laravel/Themes/Two
npm run dev
```

### 8.2 Production Build
```bash
cd laravel/Themes/Two
npm run build
npm run copy
```

### 8.3 Cache Clear
```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

## Fase 9: Testing

### 9.1 Responsive Testing
- Mobile (375px, 414px)
- Tablet (768px, 1024px)
- Desktop (1280px, 1920px)

### 9.2 Browser Testing
- Chrome
- Firefox
- Safari
- Edge

### 9.3 Performance Testing
- Lighthouse score > 90
- Load time < 2s
- First Contentful Paint < 1s

## Fase 10: SEO e Analytics

### 10.1 Meta Tags
```blade
<meta name="description" content="...">
<meta name="keywords" content="radioprotezione, sicurezza radiologica, studio dentistico, veterinario, D.Lgs 101/2020">
<meta property="og:title" content="...">
<meta property="og:description" content="...">
<meta property="og:image" content="...">
```

### 10.2 Schema.org Markup
```json
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "name": "Radioprotezione",
  "description": "Servizi di radioprotezione per studi dentistici e veterinari",
  "address": {
    "@type": "PostalAddress",
    "addressLocality": "Padova",
    "addressCountry": "IT"
  }
}
```

## Checklist Completa

### Configurazione
- [ ] Aggiornare tailwind.config.js con custom colors
- [ ] Verificare resources/css/app.css
- [ ] Configurare vite.config.js

### Componenti
- [ ] Creare radioprotection-header.blade.php
- [ ] Creare hero.blade.php
- [ ] Creare services.blade.php
- [ ] Creare why-radioprotection.blade.php
- [ ] Creare sectors.blade.php
- [ ] Creare controls.blade.php
- [ ] Creare testimonials.blade.php
- [ ] Creare resources.blade.php
- [ ] Creare newsletter.blade.php
- [ ] Creare radioprotection-footer.blade.php

### Layout
- [ ] Creare radioprotection.blade.php
- [ ] Aggiornare home.blade.php

### Contenuto
- [ ] Creare home-radioprotezione.blade.php
- [ ] Integrare tutte le sezioni
- [ ] Aggiungere immagini

### Traduzioni
- [ ] Creare lang/it/radioprotection.php
- [ ] Tradurre tutti i testi
- [ ] Aggiornare viste per usare traduzioni

### JavaScript
- [ ] Implementare smooth scroll
- [ ] Implementare mobile menu
- [ ] Implementare header scroll effect
- [ ] Testare interazioni

### Build
- [ ] npm run build
- [ ] npm run copy
- [ ] Clear cache
- [ ] Testare sito locale

### Testing
- [ ] Test responsive
- [ ] Test browser compatibility
- [ ] Test performance
- [ ] Test accessibilità

### SEO
- [ ] Aggiungere meta tags
- [ ] Aggiungere Schema.org markup
- [ ] Verificare SEO tools

## Note Importanti

1. **Consistenza Architetturale**: Mantenere coerenza con Filament, Livewire, Volt, e Folio
2. **DRY Principle**: Creare componenti riutilizzabili dove possibile
3. **KISS Principle**: Mantenere il codice semplice e leggibile
4. **Performance**: Ottimizzare immagini, minify CSS/JS, implementare caching
5. **Accessibilità**: Assicurarsi che il sito sia accessibile a tutti gli utenti
6. **SEO**: Ottimizzare per i motori di ricerca con meta tags e structured data

## Risultato Atteso

Al termine dell'implementazione, il sito locale http://127.0.0.1:8000/it dovrebbe essere visivamente identico al sito target https://lightseagreen-dogfish-560272.hostingersite.com/, con tutte le funzionalità lavoranti e ottimizzato per performance e SEO.