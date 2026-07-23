# Piano di Refactoring AGID-Compliant per Login Page

## 🚨 Analisi del Problema Attuale

### Cosa NON Funziona nel Design Attuale

1. **Layout Generico Laravel**: Il layout attuale usa il design standard Laravel che non rispetta le linee guida AGID
2. **Mancanza di Header Istituzionale**: Non c'è l'header slim e main header obbligatori per le PA
3. **Footer Inadeguato**: Footer generico invece del footer istituzionale obbligatorio
4. **Breadcrumb Mancante**: Manca la navigazione breadcrumb richiesta da AGID
5. **Branding Generico**: Logo e branding non seguono le linee guida PA
6. **Colori Non Conformi**: Palette colori non conforme al design system AGID
7. **Tipografia Inadeguata**: Font e dimensioni non conformi alle specifiche
8. **Struttura HTML Non Semantica**: Manca la struttura semantica richiesta per l'accessibilità

### Screenshot del Problema

Il risultato attuale mostra:
- Layout centrato generico con sfondo grigio
- Logo Laravel standard
- Form di login basic senza contesto istituzionale
- Assenza totale di elementi AGID obbligatori

## 🎯 Obiettivo: Login Page AGID-Compliant

### Riferimenti Design System AGID

Seguire le linee guida di **Bootstrap Italia** per:
- [Header PA](https://italia.github.io/bootstrap-italia/docs/organizzare-i-contenuti/header/)
- [Footer PA](https://italia.github.io/bootstrap-italia/docs/organizzare-i-contenuti/footer/)
- [Form di Login](https://italia.github.io/bootstrap-italia/docs/form/input/)
- [Palette Colori PA](https://italia.github.io/bootstrap-italia/docs/utilities/colori/)

### Struttura Target AGID-Compliant

```
┌─────────────────────────────────────────┐
│ HEADER SLIM (Ente + Link Istituzionali)│
├─────────────────────────────────────────┤
│ HEADER MAIN (Logo + Nome Ente)         │
├─────────────────────────────────────────┤
│ BREADCRUMB (Home > Login)               │
├─────────────────────────────────────────┤
│                                         │
│         MAIN CONTENT AREA               │
│    ┌─────────────────────────────┐      │
│    │     LOGIN FORM CARD         │      │
│    │  - Titolo Istituzionale     │      │
│    │  - Sottotitolo Descrittivo  │      │
│    │  - Form Livewire            │      │
│    │  - Link di Supporto         │      │
│    └─────────────────────────────┘      │
│                                         │
├─────────────────────────────────────────┤
│ FOOTER ISTITUZIONALE                    │
│ (Link Privacy, Accessibilità, ecc.)     │
└─────────────────────────────────────────┘
```

## 🎨 Design System AGID per Login

### Palette Colori AGID

```css
/* Colori Primari PA */
--primary-blue: #0066CC;      /* Blu Italia */
--primary-dark: #004080;      /* Blu Scuro */
--primary-light: #CCE6FF;     /* Blu Chiaro */

/* Colori Secondari */
--secondary-grey: #5A6772;    /* Grigio Scuro */
--light-grey: #F5F5F5;        /* Grigio Chiaro */
--white: #FFFFFF;             /* Bianco */

/* Colori di Stato */
--success: #008758;           /* Verde Successo */
--warning: #A66300;           /* Arancione Warning */
--danger: #D73527;            /* Rosso Errore */
--info: #0073E6;              /* Blu Info */
```

### Tipografia AGID

```css
/* Font Family */
font-family: 'Titillium Web', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;

/* Dimensioni Testo */
--text-xs: 0.75rem;    /* 12px */
--text-sm: 0.875rem;   /* 14px */
--text-base: 1rem;     /* 16px */
--text-lg: 1.125rem;   /* 18px */
--text-xl: 1.25rem;    /* 20px */
--text-2xl: 1.5rem;    /* 24px */
--text-3xl: 1.875rem;  /* 30px */

/* Titoli */
h1: --text-3xl, font-weight: 700;
h2: --text-2xl, font-weight: 600;
h3: --text-xl, font-weight: 600;
```

### Spaziature AGID

```css
/* Spaziature Standard */
--space-1: 0.25rem;   /* 4px */
--space-2: 0.5rem;    /* 8px */
--space-3: 0.75rem;   /* 12px */
--space-4: 1rem;      /* 16px */
--space-6: 1.5rem;    /* 24px */
--space-8: 2rem;      /* 32px */
--space-12: 3rem;     /* 48px */
--space-16: 4rem;     /* 64px */
```

## 🏗️ Architettura Componenti AGID

### Struttura Blocchi Riutilizzabili

#### 1. Header Slim Block
```blade
<x-pub_theme::blocks.navigation.header-slim 
    :ente="config('app.name')"
    :links="[
        ['url' => route('pages.view', ['slug' => 'contacts']), 'text' => 'Contatti'],
        ['url' => route('pages.view', ['slug' => 'help']), 'text' => 'Assistenza']
    ]"
/>
```

#### 2. Header Main Block
```blade
<x-pub_theme::blocks.navigation.header-main 
    :logo="asset('images/logo-pa.svg')"
    :ente="config('app.name')"
    :tagline="config('app.tagline')"
/>
```

#### 3. Breadcrumb Block
```blade
<x-pub_theme::blocks.navigation.breadcrumb-agid 
    :items="[
        ['url' => route('home'), 'text' => 'Home'],
        ['text' => 'Accesso']
    ]"
/>
```

#### 4. Login Form Card Block
```blade
<x-pub_theme::blocks.forms.login-card-agid 
    :title="'Accedi ai servizi'"
    :subtitle="'Utilizza le tue credenziali per accedere all\'area riservata'"
    :livewire-component="'\Modules\User\Http\Livewire\Auth\Login'"
/>
```

#### 5. Footer Istituzionale Block
```blade
<x-pub_theme::blocks.navigation.footer-institutional 
    :ente="config('app.name')"
    :links="[
        ['url' => route('pages.view', ['slug' => 'privacy']), 'text' => 'Privacy'],
        ['url' => route('pages.view', ['slug' => 'accessibility']), 'text' => 'Accessibilità'],
        ['url' => route('pages.view', ['slug' => 'legal-notes']), 'text' => 'Note legali']
    ]"
/>
```

## 📋 Specifiche Tecniche Implementazione

### Layout Target: `layouts/guest-agid.blade.php`

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? 'Accesso' }} - {{ config('app.name') }}</title>
    
    <!-- Fonts AGID -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Favicon PA -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Meta AGID -->
    <meta name="description" content="Accesso sicuro ai servizi digitali di {{ config('app.name') }}">
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'], 'themes/Sixteen')
    @livewireStyles
</head>
<body class="font-titillium antialiased bg-white">
    <!-- Skip Links per Accessibilità -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-primary-blue text-white px-4 py-2 rounded">
        Salta al contenuto principale
    </a>
    
    <!-- Header Slim -->
    <x-pub_theme::blocks.navigation.header-slim />
    
    <!-- Header Main -->
    <x-pub_theme::blocks.navigation.header-main />
    
    <!-- Breadcrumb -->
    <x-pub_theme::blocks.navigation.breadcrumb-agid />
    
    <!-- Main Content -->
    <main id="main-content" role="main" class="min-h-screen bg-light-grey py-8">
        <div class="container mx-auto px-4">
            {{ $slot }}
        </div>
    </main>
    
    <!-- Footer Istituzionale -->
    <x-pub_theme::blocks.navigation.footer-institutional />
    
    @livewireScripts
    
    <!-- Focus Management Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const firstInput = document.querySelector('input[type="email"], input[type="text"]');
            if (firstInput) {
                firstInput.focus();
            }
        });
    </script>
</body>
</html>
```

### Form Card AGID: `blocks/forms/login-card-agid.blade.php`

```blade
@props([
    'title' => 'Accedi ai servizi',
    'subtitle' => 'Utilizza le tue credenziali per accedere all\'area riservata',
    'livewireComponent' => '\Modules\User\Http\Livewire\Auth\Login'
])

<div class="max-w-md mx-auto">
    <!-- Card Login -->
    <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
        <!-- Header Card -->
        <div class="bg-primary-blue text-white px-6 py-4">
            <h1 class="text-2xl font-bold mb-2">{{ $title }}</h1>
            <p class="text-primary-light text-sm">{{ $subtitle }}</p>
        </div>
        
        <!-- Body Card -->
        <div class="px-6 py-8">
            <!-- Livewire Login Component (OBBLIGATORIO - NON MODIFICARE) -->
            @livewire($livewireComponent)
        </div>
        
        <!-- Footer Card -->
        <div class="bg-light-grey px-6 py-4 border-t border-gray-200">
            <div class="text-center text-sm text-secondary-grey">
                <p class="mb-2">
                    <strong>Problemi di accesso?</strong>
                </p>
                <p>
                    Contatta l'assistenza tecnica al numero 
                    <a href="tel:+390123456789" class="text-primary-blue hover:text-primary-dark font-semibold">
                        01 2345 6789
                    </a>
                </p>
            </div>
        </div>
    </div>
    
    <!-- Info Accessibilità -->
    <div class="mt-6 text-center text-sm text-secondary-grey">
        <p class="mb-2">
            <strong>Accessibilità:</strong> Questo servizio è conforme alle linee guida WCAG 2.1 AA.
        </p>
        <p>
            <a href="{{ route('pages.view', ['slug' => 'accessibility']) }}" 
               class="text-primary-blue hover:text-primary-dark underline">
                Dichiarazione di accessibilità
            </a>
        </p>
    </div>
</div>
```

## 🎨 CSS Tailwind Customizations

### Estensioni Tailwind per AGID

```javascript
// tailwind.config.js
module.exports = {
  theme: {
    extend: {
      colors: {
        'primary-blue': '#0066CC',
        'primary-dark': '#004080',
        'primary-light': '#CCE6FF',
        'secondary-grey': '#5A6772',
        'light-grey': '#F5F5F5',
        'success': '#008758',
        'warning': '#A66300',
        'danger': '#D73527',
        'info': '#0073E6',
      },
      fontFamily: {
        'titillium': ['Titillium Web', 'sans-serif'],
      },
      spacing: {
        '18': '4.5rem',
        '88': '22rem',
      }
    }
  }
}
```

## 🔧 Piano di Implementazione

### Fase 1: Preparazione Blocchi AGID
1. ✅ Aggiornare `header-slim.blade.php` con design AGID
2. ✅ Aggiornare `header-main.blade.php` con design AGID  
3. ✅ Aggiornare `breadcrumb-agid.blade.php` con design AGID
4. ✅ Aggiornare `footer-institutional.blade.php` con design AGID
5. 🔄 **NUOVO**: Creare `login-card-agid.blade.php` con design AGID

### Fase 2: Layout AGID
1. 🔄 **NUOVO**: Creare `layouts/guest-agid.blade.php` completo
2. 🔄 **NUOVO**: Aggiornare CSS con palette colori AGID
3. 🔄 **NUOVO**: Integrare font Titillium Web

### Fase 3: Pagina Login AGID
1. 🔄 **NUOVO**: Aggiornare `pages/auth/login.blade.php` per usare layout AGID
2. 🔄 **NUOVO**: Testare accessibilità e conformità AGID
3. 🔄 **NUOVO**: Validare con strumenti di accessibilità

## 📊 Checklist Conformità AGID

### Design System
- [ ] Palette colori conforme Bootstrap Italia
- [ ] Tipografia Titillium Web implementata
- [ ] Spaziature conformi alle specifiche
- [ ] Componenti UI conformi alle linee guida

### Struttura HTML
- [ ] Header slim con link istituzionali
- [ ] Header main con logo e branding PA
- [ ] Breadcrumb navigation presente
- [ ] Main content con struttura semantica
- [ ] Footer istituzionale con link obbligatori

### Accessibilità WCAG 2.1 AA
- [ ] Skip links implementati
- [ ] Ruoli ARIA corretti
- [ ] Focus management funzionante
- [ ] Contrasto colori conforme
- [ ] Navigazione da tastiera completa

### Conformità PA
- [ ] Logo istituzionale presente
- [ ] Link privacy, accessibilità, note legali
- [ ] Informazioni di contatto assistenza
- [ ] Dichiarazione di accessibilità
- [ ] Meta tag SEO corretti

## 🎯 Risultato Atteso

Il nuovo login AGID-compliant avrà:

1. **Header Istituzionale** con logo PA e link obbligatori
2. **Breadcrumb Navigation** per orientamento utente
3. **Form Card Centrata** con design Bootstrap Italia
4. **Colori e Tipografia** conformi alle linee guida AGID
5. **Footer Istituzionale** con tutti i link obbligatori PA
6. **Accessibilità Completa** WCAG 2.1 AA
7. **Responsive Design** per tutti i dispositivi

---

**Stato**: 🔄 PIANIFICAZIONE COMPLETATA - PRONTO PER IMPLEMENTAZIONE  
**Priorità**: 🚨 CRITICA  
**Tempo Stimato**: 2-3 ore di implementazione  
**Responsabile**: Team Development  

**Note**: Questo piano sostituisce completamente il design attuale con un approccio AGID-compliant professionale e conforme alle normative PA.
