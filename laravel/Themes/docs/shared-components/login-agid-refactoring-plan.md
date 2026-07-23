# Piano di Refactoring Login AGID - Implementazione Dettagliata

## 🎯 Obiettivo

Trasformare completamente la pagina di login da un approccio "layout guest + contenuto AGID" a una **struttura nativa AGID-compliant** dall'inizio alla fine.

## 🏗️ Architettura Proposta

### 1. Nuovo Layout AGID Istituzionale

```blade
<!-- resources/views/components/layouts/agid-institutional.blade.php -->
<!DOCTYPE html>
<html lang="it" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- AGID Required Meta Tags -->
    <meta name="description" content="{{ $description ?? 'Servizio digitale della Pubblica Amministrazione' }}">
    <meta name="keywords" content="{{ $keywords ?? 'PA, servizi digitali, AGID' }}">
    <meta name="author" content="{{ config('app.institution_name') }}">
    
    <!-- Open Graph AGID -->
    <meta property="og:title" content="{{ $title ?? config('app.name') }}">
    <meta property="og:description" content="{{ $description ?? 'Servizio digitale AGID-compliant' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    
    <title>{{ $title ?? config('app.name') }} - {{ config('app.institution_name') }}</title>
    
    <!-- AGID Fonts: Titillium Web -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">
    
    <!-- AGID CSS Framework + Custom -->
    @vite(['resources/css/agid.css', 'resources/js/agid.js'], 'themes/Sixteen')
    
    <!-- Skip Links for Screen Readers -->
    <style>
        .skip-link {
            position: absolute;
            top: -40px;
            left: 6px;
            background: #0066CC;
            color: white;
            padding: 8px;
            text-decoration: none;
            z-index: 1000;
            border-radius: 0 0 4px 4px;
        }
        .skip-link:focus {
            top: 0;
        }
    </style>
</head>
<body class="font-titillium antialiased bg-gray-50 h-full">
    <!-- Skip Navigation Links -->
    <div class="sr-only focus-within:not-sr-only">
        <a href="#main-content" class="skip-link">Salta al contenuto principale</a>
        <a href="#main-navigation" class="skip-link">Salta alla navigazione</a>
        <a href="#footer" class="skip-link">Salta al footer</a>
    </div>
    
    <!-- AGID Institutional Header -->
    <x-agid.header-institutional />
    
    <!-- AGID Breadcrumb Navigation -->
    @if(isset($breadcrumb) && !empty($breadcrumb))
        <x-agid.breadcrumb :items="$breadcrumb" />
    @endif
    
    <!-- Main Content Area -->
    <main id="main-content" role="main" class="flex-1 min-h-screen">
        {{ $slot }}
    </main>
    
    <!-- AGID Institutional Footer -->
    <x-agid.footer-institutional />
    
    <!-- AGID Accessibility Scripts -->
    <script>
        // Focus management and keyboard navigation
        document.addEventListener('DOMContentLoaded', function() {
            // Enhanced focus management for AGID compliance
            const focusableElements = 'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])';
            const modal = document.querySelector('[role="dialog"]');
            
            if (modal) {
                const focusableContent = modal.querySelectorAll(focusableElements);
                const firstFocusableElement = focusableContent[0];
                const lastFocusableElement = focusableContent[focusableContent.length - 1];
                
                // Trap focus within modal
                modal.addEventListener('keydown', function(e) {
                    if (e.key === 'Tab') {
                        if (e.shiftKey) {
                            if (document.activeElement === firstFocusableElement) {
                                lastFocusableElement.focus();
                                e.preventDefault();
                            }
                        } else {
                            if (document.activeElement === lastFocusableElement) {
                                firstFocusableElement.focus();
                                e.preventDefault();
                            }
                        }
                    }
                    if (e.key === 'Escape') {
                        modal.close();
                    }
                });
            }
        });
    </script>
</body>
</html>
```

### 2. Header Istituzionale AGID

```blade
<!-- resources/views/components/agid/header-institutional.blade.php -->
<header role="banner" class="bg-white border-b-4 border-blue-600">
    <!-- Slim Header (Ente di appartenenza) -->
    <div class="bg-blue-600 text-white py-2">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center text-sm">
                <div class="flex items-center space-x-2">
                    <span>Un servizio a cura di</span>
                    <a href="{{ config('app.institution_url') }}" 
                       class="font-semibold hover:underline focus:underline focus:outline-none"
                       target="_blank" 
                       rel="noopener noreferrer">
                        {{ config('app.institution_name', 'Nome Ente') }}
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ config('app.institution_url') }}" 
                       class="hover:underline focus:underline focus:outline-none"
                       target="_blank" 
                       rel="noopener noreferrer">
                        Vai al sito dell'ente
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Header -->
    <div class="bg-white py-4">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between">
                <!-- Logo e denominazione servizio -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" 
                       class="flex items-center space-x-3 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 rounded-lg p-2"
                       aria-label="Torna alla homepage di {{ config('app.name') }}">
                        <x-pub_theme::ui.logo class="h-12 w-auto" />
                        <div>
                            <h1 class="text-xl font-bold text-gray-900 leading-tight">
                                {{ config('app.name') }}
                            </h1>
                            <p class="text-sm text-gray-600">
                                {{ config('app.tagline', 'Servizi digitali per i cittadini') }}
                            </p>
                        </div>
                    </a>
                </div>
                
                <!-- Navigation Actions -->
                <nav id="main-navigation" role="navigation" aria-label="Navigazione principale">
                    <div class="flex items-center space-x-4">
                        @guest
                            <a href="{{ route('login') }}" 
                               class="inline-flex items-center px-4 py-2 border border-blue-600 text-blue-600 rounded-md hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 transition-colors"
                               @if(request()->routeIs('login')) aria-current="page" @endif>
                                <x-filament::icon name="heroicon-o-arrow-right-on-rectangle" class="w-4 h-4 mr-2" />
                                Accedi
                            </a>
                        @else
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-600">Benvenuto,</span>
                                <span class="font-semibold text-gray-900">{{ auth()->user()->name }}</span>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="ml-2 text-sm text-blue-600 hover:text-blue-800 focus:outline-none focus:underline">
                                        Esci
                                    </button>
                                </form>
                            </div>
                        @endguest
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>
```

### 3. Breadcrumb AGID

```blade
<!-- resources/views/components/agid/breadcrumb.blade.php -->
@props(['items' => []])

@if(!empty($items))
<nav class="bg-gray-100 border-b border-gray-200 py-3" aria-label="Percorso di navigazione">
    <div class="container mx-auto px-4">
        <ol class="flex items-center space-x-2 text-sm" role="list">
            @foreach($items as $index => $item)
                <li role="listitem" class="flex items-center">
                    @if($index > 0)
                        <x-filament::icon name="heroicon-o-chevron-right" 
                                         class="w-4 h-4 text-gray-400 mx-2" 
                                         aria-hidden="true" />
                    @endif
                    
                    @if(isset($item['url']) && !$loop->last)
                        <a href="{{ $item['url'] }}" 
                           class="text-blue-600 hover:text-blue-800 hover:underline focus:outline-none focus:underline focus:ring-2 focus:ring-blue-600 focus:ring-offset-1 rounded px-1 transition-colors">
                            @if($index === 0)
                                <x-filament::icon name="heroicon-o-home" class="w-4 h-4 inline mr-1" />
                            @endif
                            {{ $item['label'] }}
                        </a>
                    @else
                        <span class="text-gray-700 font-medium" 
                              @if($loop->last) aria-current="page" @endif>
                            {{ $item['label'] }}
                        </span>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>
</nav>
@endif
```

### 4. Form di Login AGID

```blade
<!-- resources/views/components/agid/login-form.blade.php -->
<div class="max-w-md mx-auto">
    <!-- Form Header -->
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">
            {{ __('auth.login.title') }}
        </h1>
        <p class="text-gray-600">
            {{ __('auth.login.description') }}
        </p>
    </div>
    
    <!-- Login Form Card -->
    <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
        <!-- Card Header -->
        <div class="bg-blue-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <x-filament::icon name="heroicon-o-key" class="w-5 h-5 mr-2 text-blue-600" />
                Accedi al servizio
            </h2>
            <p class="text-sm text-gray-600 mt-1">
                Inserisci le tue credenziali per accedere
            </p>
        </div>
        
        <!-- Form Body -->
        <div class="px-6 py-6">
            <!-- MANDATORY: Use Livewire Component -->
            @livewire(\Modules\User\Http\Livewire\Auth\Login::class)
        </div>
        
        <!-- Card Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="text-sm text-gray-600 space-y-3">
                <!-- Security Notice -->
                <div class="flex items-start space-x-2">
                    <x-filament::icon name="heroicon-o-shield-check" class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" />
                    <div>
                        <p class="font-medium text-gray-900">Sicurezza garantita</p>
                        <p class="text-xs">I tuoi dati sono protetti con crittografia SSL/TLS</p>
                    </div>
                </div>
                
                <!-- Help Link -->
                <div class="flex items-start space-x-2">
                    <x-filament::icon name="heroicon-o-question-mark-circle" class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" />
                    <div>
                        <a href="{{ route('pages.view', ['slug' => 'help']) }}" 
                           class="font-medium text-blue-600 hover:text-blue-800 hover:underline focus:outline-none focus:underline">
                            Hai bisogno di aiuto?
                        </a>
                        <p class="text-xs">Consulta la guida o contatta il supporto</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Registration Link (if enabled) -->
    @if (Route::has('register'))
        <div class="text-center mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <p class="text-sm text-gray-700 mb-2">
                <x-filament::icon name="heroicon-o-user-plus" class="w-4 h-4 inline mr-1" />
                Non hai ancora un account?
            </p>
            <a href="{{ route('register') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 transition-colors">
                {{ __('auth.login.create_account') }}
                <x-filament::icon name="heroicon-o-arrow-right" class="w-4 h-4 ml-2" />
            </a>
        </div>
    @endif
</div>
```

### 5. Footer Istituzionale AGID

```blade
<!-- resources/views/components/agid/footer-institutional.blade.php -->
<footer id="footer" role="contentinfo" class="bg-gray-900 text-white mt-auto">
    <!-- Main Footer -->
    <div class="py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Ente Information -->
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <x-pub_theme::ui.logo class="h-10 w-auto text-white" />
                        <div>
                            <h3 class="text-lg font-semibold">{{ config('app.institution_name') }}</h3>
                            <p class="text-sm opacity-80">{{ config('app.tagline') }}</p>
                        </div>
                    </div>
                    
                    @if(config('app.institution_address'))
                        <address class="text-sm opacity-80 not-italic">
                            {{ config('app.institution_address') }}<br>
                            @if(config('app.institution_phone'))
                                Tel: {{ config('app.institution_phone') }}<br>
                            @endif
                            @if(config('app.institution_email'))
                                Email: <a href="mailto:{{ config('app.institution_email') }}" 
                                         class="hover:underline focus:underline focus:outline-none">
                                    {{ config('app.institution_email') }}
                                </a>
                            @endif
                        </address>
                    @endif
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Link utili</h4>
                    <ul class="space-y-2 text-sm">
                        <li>
                            <a href="{{ route('pages.view', ['slug' => 'privacy']) }}" 
                               class="opacity-80 hover:opacity-100 hover:underline focus:opacity-100 focus:underline focus:outline-none transition-opacity">
                                Informativa Privacy
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pages.view', ['slug' => 'accessibility']) }}" 
                               class="opacity-80 hover:opacity-100 hover:underline focus:opacity-100 focus:underline focus:outline-none transition-opacity">
                                Dichiarazione di Accessibilità
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pages.view', ['slug' => 'contacts']) }}" 
                               class="opacity-80 hover:opacity-100 hover:underline focus:opacity-100 focus:underline focus:outline-none transition-opacity">
                                Contatti
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pages.view', ['slug' => 'legal-notes']) }}" 
                               class="opacity-80 hover:opacity-100 hover:underline focus:opacity-100 focus:underline focus:outline-none transition-opacity">
                                Note Legali
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- AGID Compliance -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Conformità AGID</h4>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center space-x-2">
                            <x-filament::icon name="heroicon-o-check-circle" class="w-5 h-5 text-green-400 flex-shrink-0" />
                            <span class="opacity-80">WCAG 2.1 AA Compliant</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <x-filament::icon name="heroicon-o-shield-check" class="w-5 h-5 text-green-400 flex-shrink-0" />
                            <span class="opacity-80">Sicurezza Certificata</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <x-filament::icon name="heroicon-o-device-phone-mobile" class="w-5 h-5 text-green-400 flex-shrink-0" />
                            <span class="opacity-80">Mobile Responsive</span>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="https://www.agid.gov.it/" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="inline-flex items-center text-sm opacity-80 hover:opacity-100 hover:underline focus:opacity-100 focus:underline focus:outline-none transition-opacity">
                            Linee Guida AGID
                            <x-filament::icon name="heroicon-o-arrow-top-right-on-square" class="w-4 h-4 ml-1" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bottom Footer -->
    <div class="bg-gray-800 py-4 border-t border-gray-700">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center text-sm opacity-80">
                <p>&copy; {{ date('Y') }} {{ config('app.institution_name') }}. Tutti i diritti riservati.</p>
                <p class="mt-2 md:mt-0">
                    Realizzato con ❤️ seguendo le linee guida AGID
                </p>
            </div>
        </div>
    </div>
</footer>
```

### 6. Pagina Login Refactored

```blade
<!-- resources/views/pages/auth/login.blade.php -->
<?php

declare(strict_types=1);

use function Laravel\Folio\{middleware, name};

middleware(['guest']);
name('login');

?>

<x-layouts.agid-institutional>
    <x-slot name="title">{{ __('auth.login.title') }}</x-slot>
    <x-slot name="description">{{ __('auth.login.meta_description') }}</x-slot>
    <x-slot name="breadcrumb">
        @php
            $breadcrumb = [
                ['label' => 'Home', 'url' => route('home')],
                ['label' => __('auth.login.title')]
            ];
        @endphp
        {{ $breadcrumb }}
    </x-slot>
    
    <!-- Main Login Content -->
    <div class="py-12 min-h-screen flex items-center">
        <div class="container mx-auto px-4">
            <x-agid.login-form />
            
            <!-- AGID Compliance Notice -->
            <div class="max-w-md mx-auto mt-8">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                    <div class="flex items-center justify-center mb-2">
                        <x-filament::icon name="heroicon-o-shield-check" class="w-6 h-6 text-blue-600 mr-2" />
                        <span class="font-semibold text-blue-900">Servizio AGID Compliant</span>
                    </div>
                    <p class="text-sm text-blue-800">
                        Questo servizio rispetta le linee guida per l'accessibilità e l'usabilità 
                        dei servizi digitali della Pubblica Amministrazione.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.agid-institutional>
```

## 🎨 CSS AGID Framework

```css
/* resources/css/agid.css */

/* AGID Typography - Titillium Web */
.font-titillium {
    font-family: 'Titillium Web', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* AGID Color Palette */
:root {
    --agid-blue-primary: #0066CC;
    --agid-blue-secondary: #004080;
    --agid-blue-light: #E6F2FF;
    --agid-gray-dark: #5C6F82;
    --agid-gray-medium: #A5B3C1;
    --agid-gray-light: #F0F6FC;
    --agid-green: #00A040;
    --agid-red: #D73527;
    --agid-orange: #FF9900;
}

/* AGID Components */
.agid-button-primary {
    @apply bg-blue-600 hover:bg-blue-700 focus:ring-blue-600 text-white font-medium py-2 px-4 rounded-md transition-colors;
}

.agid-button-secondary {
    @apply bg-white hover:bg-gray-50 focus:ring-blue-600 text-blue-600 border border-blue-600 font-medium py-2 px-4 rounded-md transition-colors;
}

.agid-input {
    @apply w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors;
}

.agid-card {
    @apply bg-white rounded-lg shadow-lg border border-gray-200;
}

/* AGID Focus Management */
.agid-focus {
    @apply focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2;
}

/* AGID Accessibility Helpers */
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

.focus-within\:not-sr-only:focus-within,
.focus\:not-sr-only:focus {
    position: static;
    width: auto;
    height: auto;
    padding: inherit;
    margin: inherit;
    overflow: visible;
    clip: auto;
    white-space: normal;
}

/* AGID Responsive Breakpoints */
@media (max-width: 768px) {
    .agid-mobile-stack {
        @apply flex-col space-y-4 space-x-0;
    }
}
```

## 📱 Responsive Design AGID

### Mobile First Approach
- **Breakpoints**: 320px, 768px, 1024px, 1280px
- **Touch Targets**: Minimo 44px x 44px
- **Font Scaling**: Responsive typography
- **Navigation**: Hamburger menu su mobile

### Tablet Optimization
- **Layout**: 2 colonne su tablet
- **Form**: Ottimizzato per touch
- **Navigation**: Compatta ma accessibile

### Desktop Enhancement
- **Layout**: 3 colonne su desktop
- **Keyboard**: Navigazione completa da tastiera
- **Focus**: Indicatori visivi chiari

## 🧪 Testing e Validazione

### Accessibilità (WCAG 2.1 AA)
- **Screen Reader**: Test con NVDA, JAWS, VoiceOver
- **Keyboard Navigation**: Tab, Shift+Tab, Enter, Escape
- **Color Contrast**: Minimo 4.5:1 per testo normale
- **Focus Indicators**: Visibili e chiari

### Performance
- **Lighthouse Score**: >90 per tutte le metriche
- **Core Web Vitals**: LCP <2.5s, FID <100ms, CLS <0.1
- **Bundle Size**: CSS <50KB, JS <100KB

### Cross-browser
- **Chrome**: Versioni recenti
- **Firefox**: Versioni recenti  
- **Safari**: Versioni recenti
- **Edge**: Versioni recenti

## 🚀 Piano di Implementazione

### Fase 1: Setup Base (1-2 giorni)
1. Creare nuovo layout `agid-institutional.blade.php`
2. Implementare componenti base (header, footer, breadcrumb)
3. Setup CSS AGID framework

### Fase 2: Componenti Avanzati (2-3 giorni)
1. Form di login AGID compliant
2. Sistema di notifiche e feedback
3. Gestione errori AGID

### Fase 3: Testing e Ottimizzazione (1-2 giorni)
1. Test accessibilità completi
2. Ottimizzazione performance
3. Cross-browser testing

### Fase 4: Documentazione (1 giorno)
1. Aggiornamento documentazione
2. Guide per sviluppatori
3. Checklist AGID compliance

## 📊 Metriche di Successo

### Conformità Normativa
- ✅ **100% WCAG 2.1 AA** compliance
- ✅ **100% AGID** guidelines compliance
- ✅ **Accessibilità** certificata

### Performance
- ✅ **Lighthouse Score** >90
- ✅ **Page Load Time** <2 secondi
- ✅ **Bundle Size** ottimizzato

### Usabilità
- ✅ **User Testing** positivo
- ✅ **Mobile Experience** ottimale
- ✅ **Keyboard Navigation** fluida

---

**Questo piano garantisce una trasformazione completa della pagina di login da un approccio ibrido a una soluzione nativa AGID-compliant al 100%.**

