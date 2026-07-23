# Implementazione Login AGID-Compliant - Tema Sixteen

## Panoramica

Questa implementazione fornisce una pagina di login completamente conforme alle linee guida AGID, utilizzando il layout standard del tema Sixteen.

## Struttura Corretta

### ✅ Pattern Corretto (IMPLEMENTATO)
```blade
<x-layouts.guest>
    <x-slot name="title">
        {{ __('auth.login.title') }}
    </x-slot>
    
    <x-slot name="description">
        {{ __('auth.login.description', ['service' => config('app.name')]) }}
    </x-slot>

    <!-- AGID-Compliant Login Form with Mandatory Livewire Component -->
    <div class="space-y-6">
        <!-- Livewire Login Component (MANDATORY - DO NOT MODIFY) -->
        @livewire(\Modules\User\Http\Livewire\Auth\Login::class)
        
        <!-- Registration Link (if enabled) -->
        @if (Route::has('register'))
            <div class="text-center pt-4 border-t border-gray-200">
                <p class="text-sm text-gray-600">
                    {{ __('auth.login.no_account') }}
                    <a href="{{ route('register') }}" 
                       class="font-medium text-blue-600 hover:text-blue-800 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded px-1 transition-colors">
                        {{ __('auth.login.create_account') }}
                    </a>
                </p>
            </div>
        @endif
        
        <!-- Security Information -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                </svg>
                <div class="text-sm">
                    <h3 class="font-medium text-blue-900 mb-1">{{ __('auth.login.security_title') }}</h3>
                    <p class="text-blue-700">{{ __('auth.login.security_message') }}</p>
                </div>
            </div>
        </div>
        
        <!-- AGID Compliance Information -->
        <div class="mt-8 text-center text-sm text-gray-600 max-w-md mx-auto">
            <p class="mb-2">
                Questo servizio è conforme alle 
                <a href="https://www.agid.gov.it/" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   class="text-blue-600 hover:text-blue-800 underline">
                    linee guida AGID
                </a>
            </p>
            <p class="mb-4">
                Per informazioni sulla privacy consulta la 
                <a href="{{ route('page_slug.view', ['slug' => 'privacy']) }}" 
                   class="text-blue-600 hover:text-blue-800 underline">
                    informativa sulla privacy
                </a>
            </p>
            
            <!-- Accessibility Information -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="mb-2">
                    <strong>Accessibilità:</strong> Questo servizio è accessibile secondo le linee guida WCAG 2.1 AA.
                    <a href="{{ route('page_slug.view', ['slug' => 'accessibility']) }}" 
                       class="text-blue-600 hover:text-blue-800 underline">
                        Dichiarazione di accessibilità
                    </a>
                </p>
                <p class="text-xs">
                    <strong>Navigazione da tastiera:</strong> Usa Tab per navigare tra i campi e Invio per inviare il modulo.
                </p>
            </div>
        </div>
    </div>
</x-layouts.guest>
```

### ❌ Pattern Errato (NON USARE)
```blade
<!-- ERRORE: Layout specifico non necessario -->
<x-pub_theme::layouts.auth-agid>
    <!-- contenuto -->
</x-pub_theme::layouts.auth-agid>
```

## Motivazione della Correzione

### Problema Originale
- **Errore**: `Unable to locate a class or view for component [pub_theme::layouts.auth-agid]`
- **Causa**: Layout specifico non esistente e non necessario

### Soluzione Implementata
1. **Utilizzo Layout Standard**: `<x-layouts.guest>` invece di layout custom
2. **Tema AGID-Ready**: Il tema Sixteen è già conforme alle linee guida AGID
3. **Shortcut Disponibile**: Utilizzo dello shortcut registrato
4. **Semplicità**: Meno codice, più manutenibilità

## Caratteristiche AGID-Compliant

### ✅ Conformità Automatica
Il tema Sixteen fornisce automaticamente:
- Header istituzionale con logo e nome ente
- Breadcrumb navigation
- Footer con link obbligatori (privacy, accessibilità, assistenza)
- Accessibilità WCAG 2.1 AA
- Responsive design
- Keyboard navigation
- Screen reader support

### ✅ Componenti Specifici
- **Livewire Login**: Componente obbligatorio per autenticazione
- **Security Info**: Informazioni sulla sicurezza della connessione
- **Accessibility Info**: Link alla dichiarazione di accessibilità
- **Registration Link**: Link opzionale per registrazione

## File di Implementazione

### Pagina Login
- **File**: `resources/views/pages/auth/login.blade.php`
- **Layout**: `<x-layouts.guest>`
- **Componente**: `@livewire(\Modules\User\Http\Livewire\Auth\Login::class)`

### Layout Guest
- **File**: `resources/views/layouts/guest.blade.php`
- **Caratteristiche**: Centrato, sfondo grigio, logo in alto
- **AGID Compliance**: Eredita automaticamente dal tema
- **Vite Integration**: CSS e JavaScript separati con tema specificato
  ```blade
  <!-- CSS del Tema -->
  @vite(['resources/css/app.css'], 'themes/Sixteen/dist')
  
  <!-- JavaScript del Tema -->
  @vite(['resources/js/app.js'], 'themes/Sixteen/dist')
  ```

## Traduzioni Richieste

### Chiavi di Traduzione (Solo per Testi Dinamici)
```php
// auth.login.*
'title' => 'Accesso ai servizi',
'description' => 'Inserisci le tue credenziali per accedere a :service',
'no_account' => 'Non hai un account?',
'create_account' => 'Registrati',
```

### Note sui Testi Hardcoded
I seguenti testi sono **intenzionalmente hardcoded** per garantire conformità AGID:
- "Informativa sulla privacy" - Testo standard AGID
- "Dichiarazione di accessibilità" - Testo standard AGID
- "Navigazione da tastiera" - Istruzioni specifiche AGID
- "Linee guida AGID" - Riferimento ufficiale

**Motivazione**: Questi testi sono standardizzati dalle linee guida AGID e non devono essere tradotti per mantenere coerenza e conformità.

## Best Practices

### ✅ DO
- Utilizzare `<x-layouts.guest>` per pagine di login
- Utilizzare il componente Livewire obbligatorio
- Passare slot appropriati (title, description)
- Utilizzare traduzioni per tutti i testi
- Mantenere la struttura semantica

### ❌ DON'T
- Creare layout custom per AGID (non necessario)
- Duplicare funzionalità del componente Livewire
- Hardcodare testi invece di usare traduzioni
- Modificare la struttura del componente Livewire

## Collegamenti

- [Layout Usage Patterns](layout-usage-patterns.md)
- [Theme Namespace Issue](theme-namespace-issue.md)
- [Layout Guest](resources/views/layouts/guest.blade.php)
- [Login Component](../../../laravel/Modules/User/app/Http/Livewire/Auth/Login.php)

