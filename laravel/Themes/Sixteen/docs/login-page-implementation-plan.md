# Piano di Implementazione Pagina Login AGID-Compliant

## Panoramica del Piano

Questo documento definisce il piano dettagliato per l'implementazione di una pagina di login conforme alle linee guida AGID e Bootstrap Italia, mantenendo l'integrazione obbligatoria con Filament Livewire.

## Analisi della Situazione Attuale

### Struttura Esistente Analizzata
```blade
<!-- File: resources/views/pages/auth/login.blade.php -->
<x-pub_theme::layouts.main>
    <div class="flex flex-col items-center justify-center min-h-screen py-10">
        <div class="w-full max-w-md">
            <x-pub_theme::ui.logo class="w-auto h-10 text-gray-700 fill-current dark:text-gray-100 mx-auto mb-6" />
            <h2 class="mt-5 text-2xl font-extrabold leading-9 text-center text-gray-800 dark:text-gray-200">
                {{ __('auth.login.title') }}
            </h2>
            <div class="text-sm leading-5 text-center text-gray-600 dark:text-gray-400 space-x-0.5">
                <span>{{ __('auth.login.or') }}</span>
                <x-pub_theme::ui.text-link href="{{ url('/' . app()->getLocale() . '/auth/register') }}">
                    {{ __('auth.login.create_account') }}
                </x-pub_theme::ui.text-link>
            </div>
        </div>
        
        <div class="mt-8 w-full max-w-md">
            <div class="px-10 py-8 bg-white dark:bg-gray-950/50 border border-gray-200/60 dark:border-gray-200/10 rounded-lg shadow-sm">
                @livewire(\Modules\User\Http\Livewire\Auth\Login::class)
            </div>
        </div>
    </div>
</x-pub_theme::layouts.main>
```

### Componenti Esistenti Verificati
Basandomi sull'analisi della documentazione esistente (`auth_best_practices.md`), ho verificato:

1. **Namespace Corretto**: `pub_theme` è il namespace registrato per i temi
2. **Componenti Disponibili**: 
   - `<x-pub_theme::layouts.main>`
   - `<x-pub_theme::ui.logo>`
   - `<x-pub_theme::ui.text-link>`
3. **Livewire Obbligatorio**: `@livewire(\Modules\User\Http\Livewire\Auth\Login::class)` deve rimanere invariato

## Requisiti AGID da Implementare

### 1. Header Istituzionale
- **Slim Header**: Informazioni ente di appartenenza
- **Main Header**: Logo, denominazione, tagline del servizio
- **Navigazione**: Menu principale e breadcrumb

### 2. Struttura Contenuto
- **Main Content**: Container principale con landmark appropriati
- **Form Container**: Card strutturata con header, body, footer
- **Informazioni Contestuali**: Descrizione servizio e istruzioni

### 3. Footer Istituzionale
- **Link Obbligatori**: Privacy, Note legali, Accessibilità
- **Informazioni Ente**: Logo e denominazione
- **Contatti**: Link a supporto e assistenza

### 4. Accessibilità WCAG 2.1 AA
- **Skip Links**: Navigazione rapida per screen reader
- **Landmark ARIA**: Struttura semantica corretta
- **Focus Management**: Indicatori visibili e logici
- **Contrasto**: Colori conformi alle specifiche

## Piano di Implementazione

### Fase 1: Creazione Layout AGID-Compliant

#### 1.1 Nuovo Layout Specializzato
Creare `resources/views/layouts/auth-agid.blade.php` con:
- Header istituzionale completo
- Breadcrumb di navigazione
- Main content con landmark corretti
- Footer istituzionale
- Skip links per accessibilità

#### 1.2 Componenti Header
Utilizzare il sistema di blocchi esistente per creare:
- `blocks/navigation/header-slim.blade.php`
- `blocks/navigation/header-main.blade.php`
- `blocks/navigation/breadcrumb.blade.php`

#### 1.3 Componenti Footer
Creare:
- `blocks/navigation/footer-institutional.blade.php`

### Fase 2: Ottimizzazione Form Container

#### 2.1 Container Strutturato
Creare wrapper per il componente Livewire obbligatorio:
```html
<div class="form-container-agid">
  <div class="form-header">
    <!-- Intestazione con logo e titolo -->
  </div>
  <div class="form-body">
    @livewire(\Modules\User\Http\Livewire\Auth\Login::class)
  </div>
  <div class="form-footer">
    <!-- Link di supporto e utility -->
  </div>
</div>
```

#### 2.2 Informazioni Contestuali
Aggiungere:
- Descrizione del servizio
- Istruzioni per l'accesso
- Link a documentazione e supporto
- Informazioni su privacy e sicurezza

### Fase 3: Implementazione Accessibilità

#### 3.1 Skip Links
```html
<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-blue-600 text-white px-4 py-2 rounded z-50">
  Salta al contenuto principale
</a>
<a href="#login-form" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-32 bg-blue-600 text-white px-4 py-2 rounded z-50">
  Vai al form di login
</a>
```

#### 3.2 Landmark ARIA
```html
<header role="banner" aria-label="Intestazione del sito">
<nav role="navigation" aria-label="Navigazione principale">
<main role="main" id="main-content" aria-label="Contenuto principale">
<footer role="contentinfo" aria-label="Informazioni del sito">
```

#### 3.3 Struttura Heading
```html
<h1>Nome del Servizio - Accesso</h1>
<h2>Inserisci le tue credenziali</h2>
<h3>Link utili</h3>
```

### Fase 4: Conversione Tailwind CSS

#### 4.1 Header Istituzionale
```html
<!-- Slim Header -->
<div class="bg-blue-600 text-white py-2">
  <div class="container mx-auto px-4">
    <div class="flex justify-between items-center text-sm">
      <span>Ente di appartenenza</span>
      <a href="#" class="hover:underline">Vai al sito dell'ente</a>
    </div>
  </div>
</div>

<!-- Main Header -->
<div class="bg-white border-b border-gray-200 py-4">
  <div class="container mx-auto px-4">
    <div class="flex items-center space-x-4">
      <img src="logo.svg" alt="Logo Ente" class="h-12 w-auto">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Nome Ente</h1>
        <p class="text-gray-600">Tagline del servizio</p>
      </div>
    </div>
  </div>
</div>
```

#### 4.2 Breadcrumb
```html
<nav class="bg-gray-50 border-b border-gray-200 py-3" aria-label="Percorso di navigazione">
  <div class="container mx-auto px-4">
    <ol class="flex items-center space-x-2 text-sm">
      <li>
        <a href="/" class="text-blue-600 hover:text-blue-800 transition-colors">Home</a>
      </li>
      <li class="text-gray-400">/</li>
      <li class="text-gray-700 font-medium" aria-current="page">Accedi</li>
    </ol>
  </div>
</nav>
```

#### 4.3 Form Container
```html
<main role="main" id="main-content" class="py-12 bg-gray-50">
  <div class="container mx-auto px-4">
    <div class="max-w-lg mx-auto">
      <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-white text-center">
          <h2 class="text-2xl font-bold text-gray-900 mb-2">Accedi al servizio</h2>
          <p class="text-gray-600">Inserisci le tue credenziali per accedere ai servizi online</p>
        </div>
        
        <!-- Body -->
        <div class="px-6 py-6" id="login-form">
          @livewire(\Modules\User\Http\Livewire\Auth\Login::class)
        </div>
        
        <!-- Footer -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
          <div class="text-center space-y-3">
            <div class="text-sm text-gray-600">
              <a href="/password/reset" class="text-blue-600 hover:text-blue-800 underline transition-colors">
                Password dimenticata?
              </a>
            </div>
            <div class="text-sm text-gray-600">
              Non hai un account? 
              <a href="/register" class="text-blue-600 hover:text-blue-800 underline transition-colors">
                Registrati
              </a>
            </div>
            <div class="text-sm text-gray-600">
              <a href="/help" class="text-blue-600 hover:text-blue-800 underline transition-colors">
                Hai bisogno di aiuto?
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
```

#### 4.4 Footer Istituzionale
```html
<footer role="contentinfo" class="bg-gray-900 text-white py-8">
  <div class="container mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <div class="flex items-center space-x-4">
        <img src="logo-white.svg" alt="Logo Ente" class="h-10 w-auto">
        <div>
          <h3 class="text-lg font-semibold">Nome Ente</h3>
          <p class="text-gray-300 text-sm">Servizi digitali per i cittadini</p>
        </div>
      </div>
      <div>
        <h4 class="text-lg font-semibold mb-4">Link utili</h4>
        <ul class="space-y-2 text-sm">
          <li><a href="/privacy" class="text-gray-300 hover:text-white transition-colors">Privacy</a></li>
          <li><a href="/note-legali" class="text-gray-300 hover:text-white transition-colors">Note legali</a></li>
          <li><a href="/accessibilita" class="text-gray-300 hover:text-white transition-colors">Dichiarazione di accessibilità</a></li>
          <li><a href="/help" class="text-gray-300 hover:text-white transition-colors">Assistenza</a></li>
        </ul>
      </div>
    </div>
  </div>
</footer>
```

## Struttura File da Creare/Modificare

### 1. Layout Principale
```
resources/views/layouts/auth-agid.blade.php
```

### 2. Blocchi di Navigazione
```
resources/views/components/blocks/navigation/header-slim.blade.php
resources/views/components/blocks/navigation/header-main.blade.php
resources/views/components/blocks/navigation/breadcrumb.blade.php
resources/views/components/blocks/navigation/footer-institutional.blade.php
```

### 3. Componenti Form
```
resources/views/components/blocks/forms/auth-container.blade.php
```

### 4. Pagina Login Aggiornata
```
resources/views/pages/auth/login.blade.php (modifica)
```

## Checklist di Implementazione

### Fase 1: Preparazione
- [ ] Studiare documentazione esistente ✅
- [ ] Analizzare requisiti AGID ✅
- [ ] Creare piano di implementazione ✅
- [ ] Verificare componenti disponibili ✅

### Fase 2: Sviluppo Layout
- [ ] Creare layout auth-agid.blade.php
- [ ] Implementare header istituzionale
- [ ] Implementare breadcrumb
- [ ] Implementare footer istituzionale
- [ ] Aggiungere skip links

### Fase 3: Ottimizzazione Form
- [ ] Creare container strutturato per Livewire
- [ ] Aggiungere informazioni contestuali
- [ ] Implementare link di supporto
- [ ] Ottimizzare responsive design

### Fase 4: Accessibilità
- [ ] Implementare landmark ARIA
- [ ] Verificare struttura heading
- [ ] Testare navigazione da tastiera
- [ ] Validare contrasto colori
- [ ] Testare con screen reader

### Fase 5: Testing e Validazione
- [ ] Test funzionale login
- [ ] Test accessibilità automatico
- [ ] Test responsive design
- [ ] Validazione HTML
- [ ] Test performance

## Considerazioni Tecniche

### 1. Mantenimento Compatibilità
- Il componente Livewire DEVE rimanere invariato
- Namespace `pub_theme` deve essere rispettato
- Layout esistente non deve essere compromesso

### 2. Performance
- Minimizzare CSS aggiuntivo
- Ottimizzare caricamento immagini
- Utilizzare lazy loading dove appropriato

### 3. Sicurezza
- Mantenere protezioni CSRF
- Non compromettere validazioni esistenti
- Preservare rate limiting

### 4. Manutenibilità
- Documentare ogni modifica
- Utilizzare sistema di blocchi esistente
- Seguire convenzioni del tema

## Risultato Atteso

### Prima (Attuale)
- Layout semplice centrato
- Mancanza elementi istituzionali
- Accessibilità limitata
- Non conforme AGID

### Dopo (Target)
- Layout istituzionale completo
- Header e footer PA-compliant
- Accessibilità WCAG 2.1 AA
- Piena conformità AGID
- Mantenimento funzionalità Livewire

---

*Piano creato: Luglio 2025*
*Conforme a: AGID, Bootstrap Italia, WCAG 2.1 AA*
*Compatibile con: Filament Livewire, Tema Sixteen*
