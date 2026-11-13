---
title: "agid layout usage rules"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "agid layout usage rules"
issues: []
discussions: []
status: draft
---

# Regole per l'Uso dei Layout AGID - Tema Sixteen

## 🚨 REGOLA FONDAMENTALE - Layout AGID-Compliant

**Il tema Sixteen ora include layout AGID-compliant per le pagine di autenticazione e pubbliche che devono rispettare le linee guida delle Pubbliche Amministrazioni italiane.**

### ✅ Layout AGID Disponibili

#### 1. Layout Guest AGID: `<x-layouts.guest-agid>`

**Utilizzo**: Per tutte le pagine di autenticazione e pubbliche che devono essere AGID-compliant

```blade
<x-layouts.guest-agid>
    <x-slot name="title">
        Titolo della Pagina - {{ config('app.name') }}
    </x-slot>

    <!-- Contenuto della pagina -->
    <div class="max-w-md mx-auto">
        <!-- Il tuo contenuto qui -->
    </div>
</x-layouts.guest-agid>
```

**Caratteristiche Incluse**:
- ✅ Header Slim AGID con link istituzionali
- ✅ Header Main con logo PA e branding
- ✅ Breadcrumb navigation obbligatorio
- ✅ Footer istituzionale con link PA obbligatori
- ✅ Font Titillium Web (standard AGID)
- ✅ Palette colori Bootstrap Italia
- ✅ Skip links per accessibilità WCAG 2.1 AA
- ✅ Focus management automatico
- ✅ Meta tag SEO e accessibilità

### ❌ Layout da NON Usare per Pagine AGID

```blade
<!-- ❌ ERRATO per pagine PA -->
<x-layouts.guest>
    <!-- Layout generico Laravel, non AGID-compliant -->
</x-layouts.guest>

<!-- ❌ ERRATO - Layout non esistente -->
<x-pub_theme::layouts.auth-agid>
    <!-- Questo layout non esiste -->
</x-pub_theme::layouts.auth-agid>
```

## 🎯 Quando Usare Ogni Layout

### Layout Guest AGID (`<x-layouts.guest-agid>`)

**Usare per**:
- ✅ Pagine di login PA
- ✅ Pagine di registrazione PA
- ✅ Pagine di reset password PA
- ✅ Pagine pubbliche istituzionali
- ✅ Pagine di servizi digitali PA
- ✅ Pagine di accesso ai servizi online

**NON usare per**:
- ❌ Pagine private/autenticate (usare `<x-layouts.app>`)
- ❌ Dashboard amministrative (usare layout specifici)
- ❌ API endpoints (non applicabile)

### Layout Guest Standard (`<x-layouts.guest>`)

**Usare per**:
- ✅ Applicazioni private/aziendali
- ✅ Progetti non PA
- ✅ Prototipi e sviluppo
- ✅ Pagine che non richiedono conformità AGID

## 🏗️ Struttura del Layout Guest AGID

### Componenti Inclusi Automaticamente

```blade
<!-- Header Slim AGID -->
<x-pub_theme::blocks.navigation.header-slim 
    :ente="config('app.name')"
    :links="[
        ['url' => route('pages.view', ['slug' => 'contacts']), 'text' => 'Contatti'],
        ['url' => route('pages.view', ['slug' => 'help']), 'text' => 'Assistenza']
    ]"
/>

<!-- Header Main AGID -->
<x-pub_theme::blocks.navigation.header-main 
    :logo="asset('images/logo-pa.svg')"
    :ente="config('app.name')"
    :tagline="config('app.tagline', 'Servizi digitali per i cittadini')"
/>

<!-- Breadcrumb AGID -->
<x-pub_theme::blocks.navigation.breadcrumb-agid 
    :items="[
        ['url' => route('home'), 'text' => 'Home'],
        ['text' => 'Accesso']
    ]"
/>

<!-- Footer Istituzionale AGID -->
<x-pub_theme::blocks.navigation.footer-institutional 
    :ente="config('app.name')"
    :links="[
        ['url' => route('pages.view', ['slug' => 'privacy']), 'text' => 'Privacy'],
        ['url' => route('pages.view', ['slug' => 'accessibility']), 'text' => 'Accessibilità'],
        ['url' => route('pages.view', ['slug' => 'legal-notes']), 'text' => 'Note legali'],
        ['url' => route('pages.view', ['slug' => 'help']), 'text' => 'Assistenza']
    ]"
/>
```

### Variabili Disponibili nel Layout

Il layout `guest-agid` fornisce automaticamente queste variabili:

```php
$theme_name = 'Sixteen';
$agid_compliant = true;
$accessibility_level = 'WCAG 2.1 AA';
```

## 🎨 Personalizzazione del Layout AGID

### Slot Disponibili

```blade
<x-layouts.guest-agid>
    <!-- Slot title per il tag <title> -->
    <x-slot name="title">
        Titolo Personalizzato - {{ config('app.name') }}
    </x-slot>

    <!-- Slot meta per meta tag aggiuntivi -->
    <x-slot name="meta">
        <meta name="description" content="Descrizione personalizzata">
        <meta name="keywords" content="parole, chiave, personalizzate">
    </x-slot>

    <!-- Slot styles per CSS aggiuntivi -->
    <x-slot name="styles">
        <style>
            .custom-style { color: #0066CC; }
        </style>
    </x-slot>

    <!-- Contenuto principale -->
    <div class="contenuto-principale">
        <!-- Il tuo contenuto qui -->
    </div>

    <!-- Slot scripts per JavaScript aggiuntivi -->
    <x-slot name="scripts">
        <script>
            console.log('Script personalizzato');
        </script>
    </x-slot>
</x-layouts.guest-agid>
```

## 📋 Configurazione Richiesta

### Variabili di Configurazione

Per il corretto funzionamento del layout AGID, configurare in `.env`:

```env
# Nome dell'ente/organizzazione
APP_NAME="Nome Ente PA"

# Tagline istituzionale
APP_TAGLINE="Servizi digitali per i cittadini"

# URL del sito istituzionale
APP_INSTITUTION_URL="https://www.ente.gov.it"

# Nome completo dell'istituzione
APP_INSTITUTION_NAME="Nome Completo Ente"
```

### Asset Richiesti

Assicurarsi che esistano questi asset:

```
public/
├── images/
│   ├── logo-pa.svg          # Logo PA per header
│   ├── logo-white.svg       # Logo bianco per footer
│   └── favicon.ico          # Favicon istituzionale
└── themes/
    └── Sixteen/
        └── dist/            # Asset compilati Vite
```

## 🔧 Esempi di Implementazione

### Pagina di Login AGID

```blade
<?php
declare(strict_types=1);
use function Laravel\Folio\{middleware, name};
middleware(['guest']);
name('login');
?>

<x-layouts.guest-agid>
    <x-slot name="title">
        Accesso - {{ config('app.name') }}
    </x-slot>

    <!-- Login Card AGID-Compliant -->
    <x-pub_theme::blocks.forms.login-card-agid 
        title="Accedi ai servizi digitali"
        subtitle="Utilizza le tue credenziali per accedere all'area riservata"
        livewire-component="\Modules\User\Http\Livewire\Auth\Login"
    />
</x-layouts.guest-agid>
```

### Pagina di Registrazione AGID

```blade
<x-layouts.guest-agid>
    <x-slot name="title">
        Registrazione - {{ config('app.name') }}
    </x-slot>

    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-blue-600 text-white px-6 py-4">
                <h1 class="text-2xl font-bold mb-2">Registrati ai servizi</h1>
                <p class="text-blue-100 text-sm">Crea un account per accedere ai servizi online</p>
            </div>
            
            <div class="px-6 py-8">
                @livewire(\Modules\User\Http\Livewire\Auth\Register::class)
            </div>
        </div>
    </div>
</x-layouts.guest-agid>
```

## 🚨 Errori Comuni e Soluzioni

### Errore: "View [layouts.guest-agid] not found"

**Causa**: Il layout non è stato registrato correttamente nel ThemeServiceProvider

**Soluzione**: Verificare che il ThemeServiceProvider includa la registrazione del layout:

```php
protected function registerLayoutShortcuts(): void
{
    $this->app['view']->addNamespace('layouts', __DIR__ . '/../../resources/views/layouts');
}
```

### Errore: Asset non caricati

**Causa**: Vite non configurato correttamente per il tema

**Soluzione**: Verificare che il layout usi la direttiva Vite corretta:

```blade
@vite(['resources/css/app.css', 'resources/js/app.js'], 'themes/Sixteen')
```

### Errore: Componenti blocchi non trovati

**Causa**: Namespace dei componenti non corretto

**Soluzione**: Usare sempre il namespace `pub_theme::` per i componenti:

```blade
<x-pub_theme::blocks.navigation.header-slim />
```

## 📊 Checklist Conformità AGID

Prima di pubblicare una pagina con layout AGID, verificare:

### Design System
- [ ] Palette colori conforme Bootstrap Italia (#0066CC)
- [ ] Font Titillium Web caricato e applicato
- [ ] Spaziature conformi alle specifiche AGID
- [ ] Componenti UI conformi alle linee guida

### Struttura HTML
- [ ] Header slim con link istituzionali presente
- [ ] Header main con logo e branding PA presente
- [ ] Breadcrumb navigation implementato
- [ ] Main content con struttura semantica corretta
- [ ] Footer istituzionale con link obbligatori presente

### Accessibilità WCAG 2.1 AA
- [ ] Skip links funzionanti
- [ ] Ruoli ARIA corretti (role="main", role="contentinfo")
- [ ] Focus management implementato
- [ ] Contrasto colori conforme (minimo 4.5:1)
- [ ] Navigazione da tastiera completa
- [ ] Screen reader compatibility testata

### Conformità PA
- [ ] Logo istituzionale presente e corretto
- [ ] Link privacy, accessibilità, note legali funzionanti
- [ ] Informazioni di contatto assistenza presenti
- [ ] Dichiarazione di accessibilità collegata
- [ ] Meta tag SEO corretti e completi

### Performance e Tecnico
- [ ] Asset Vite caricati correttamente
- [ ] Responsive design testato su tutti i dispositivi
- [ ] Tempi di caricamento ottimizzati
- [ ] JavaScript non bloccante
- [ ] CSS ottimizzato e minificato

## 📚 Documentazione Correlata

- [agid-login-refactoring-plan.md](agid-login-refactoring-plan.md) - Piano completo refactoring AGID
- [vite-configuration-rules.md](build/vite-configuration-rules.md) - Regole configurazione Vite
- [route-structure-rules.md](route-structure-rules.md) - Regole per le route CMS
- [layout-usage-rules.md](layout-usage-rules.md) - Regole generali layout tema

---
**Regola stabilita**: 31 Luglio 2025  
**Autorità**: Implementazione AGID-compliant per PA italiane  
**Stato**: REGOLA FONDAMENTALE  
**Priorità**: CRITICA per progetti PA

**Motivazione**: Le Pubbliche Amministrazioni italiane devono rispettare le linee guida AGID per accessibilità, usabilità e design. Questo layout garantisce la conformità automatica a tutti i requisiti.
