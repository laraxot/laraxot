---
title: "login card component analysis"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "login card component analysis"
issues: []
discussions: []
status: draft
---

# Analisi Componente Login Card AGID - Tema Sixteen

## 🚨 PROBLEMA IDENTIFICATO

**ERRORE**: `Unable to locate a class or view for component [pub_theme::blocks.forms.login-card-agid]`

**CAUSA**: Il componente esiste ma il namespace non è corretto

## 📋 ANALISI DEL PROBLEMA

### 1. **Componente Esistente**
Il componente `login-card-agid.blade.php` esiste in:
```
laravel/Themes/Sixteen/resources/views/components/blocks/forms/login-card-agid.blade.php
```

### 2. **Namespace Errato**
**PROBLEMA**: Sto usando un namespace sbagliato
```blade
<!-- ❌ ERRATO - Namespace sbagliato -->
<x-pub_theme::forms.login-card-agid />
```

**SOLUZIONE**: Usare il namespace corretto
```blade
<!-- ✅ CORRETTO - Namespace corretto -->
<x-forms.login-card-agid />
```

### 3. **Registrazione Componenti**
Il tema Sixteen registra i componenti con namespace `blocks` non `pub_theme::blocks`

## 🏗️ ARCHITETTURA CORRETTA

### 1. **Struttura Componenti Blocks**
```
laravel/Themes/Sixteen/resources/views/components/blocks/
├── forms/
│   ├── login-card-agid.blade.php     ← ESISTE
│   ├── register-card-agid.blade.php  ← DA CREARE
│   ├── password-reset-card-agid.blade.php ← DA CREARE
│   ├── input.blade.php
│   ├── select.blade.php
│   └── ...
├── cards/
├── buttons/
├── alerts/
└── ...
```

### 2. **Namespace Corretti**
```blade
<!-- ✅ CORRETTO - Namespace blocks -->
<x-forms.login-card-agid />
<x-forms.register-card-agid />
<x-forms.password-reset-card-agid />

<!-- ✅ CORRETTO - Namespace cards -->
<x-cards.basic />
<x-cards.featured />

<!-- ✅ CORRETTO - Namespace buttons -->
<x-buttons.primary />
<x-buttons.cta />
```

### 3. **Props Standard Forms**
Secondo il sistema blocks, tutti i componenti forms dovrebbero avere props uniformi:

```php
@props([
    'title' => '',           // string - Titolo del form
    'subtitle' => '',        // string - Sottotitolo (opzionale)
    'livewireComponent' => '', // string - Componente Livewire
    'variant' => 'default',  // string - Variante stile
    'size' => 'md',          // string - Dimensione
    'shadow' => true,        // bool - Abilita ombra
    'border' => true,        // bool - Abilita bordo
    'footer' => '',          // string - Contenuto footer (opzionale)
    'helpText' => '',        // string - Testo di aiuto
    'errorText' => '',       // string - Testo di errore
    'successText' => ''      // string - Testo di successo
])
```

## 🎯 IMPLEMENTAZIONE CORRETTA

### 1. **File Login Corretto**
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
    <x-forms.login-card-agid 
        title="Accedi ai servizi digitali"
        subtitle="Utilizza le tue credenziali per accedere all'area riservata dei servizi online"
        livewire-component="\Modules\User\Http\Livewire\Auth\Login"
    />
</x-layouts.guest-agid>
```

### 2. **Componente Login Card AGID Migliorato**
Il componente esistente è buono ma può essere migliorato per seguire il sistema blocks:

```blade
@props([
    'title' => 'Accedi ai servizi',
    'subtitle' => 'Utilizza le tue credenziali per accedere all\'area riservata',
    'livewireComponent' => '\Modules\User\Http\Livewire\Auth\Login',
    'variant' => 'default',
    'size' => 'md',
    'shadow' => true,
    'border' => true,
    'footer' => '',
    'helpText' => '',
    'errorText' => '',
    'successText' => ''
])

<div class="max-w-md mx-auto">
    <!-- Card Login AGID -->
    <div class="bg-white rounded-lg {{ $shadow ? 'shadow-lg' : '' }} {{ $border ? 'border border-gray-200' : '' }} overflow-hidden">
        <!-- Header Card con Colori AGID -->
        <div class="bg-primary-blue text-white px-6 py-4">
            <h1 class="text-2xl font-bold mb-2">{{ $title }}</h1>
            <p class="text-blue-100 text-sm">{{ $subtitle }}</p>
        </div>
        
        <!-- Body Card -->
        <div class="px-6 py-8">
            <!-- Livewire Login Component (OBBLIGATORIO - NON MODIFICARE) -->
            @livewire($livewireComponent)
        </div>
        
        <!-- Footer Card con Info Assistenza -->
        @if($footer)
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {!! $footer !!}
            </div>
        @else
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="text-center text-sm text-gray-600">
                    <p class="mb-2">
                        <strong>Problemi di accesso?</strong>
                    </p>
                    <p>
                        Contatta l'assistenza tecnica al numero 
                        <a href="tel:+390123456789" class="text-blue-600 hover:text-blue-800 font-semibold underline">
                            01 2345 6789
                        </a>
                    </p>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Info Accessibilità AGID -->
    <div class="mt-6 text-center text-sm text-gray-600">
        <p class="mb-2">
            <strong>Accessibilità:</strong> Questo servizio è conforme alle linee guida WCAG 2.1 AA.
        </p>
        <p>
            <a href="{{ route('pages.view', ['slug' => 'accessibility']) }}" 
               class="text-blue-600 hover:text-blue-800 underline">
                Dichiarazione di accessibilità
            </a>
        </p>
        <p class="mt-2 text-xs">
            <strong>Navigazione da tastiera:</strong> Usa Tab per navigare tra i campi e Invio per inviare il modulo.
        </p>
    </div>
</div>
```

### 3. **Componenti Forms AGID da Creare**

#### A. Register Card AGID
```blade
<!-- File: laravel/Themes/Sixteen/resources/views/components/blocks/forms/register-card-agid.blade.php -->
@props([
    'title' => 'Registrazione',
    'subtitle' => 'Crea il tuo account per accedere ai servizi',
    'livewireComponent' => '\Modules\User\Http\Livewire\Auth\Register',
    'variant' => 'default',
    'size' => 'md',
    'shadow' => true,
    'border' => true,
    'footer' => '',
    'helpText' => '',
    'errorText' => '',
    'successText' => ''
])

<div class="max-w-md mx-auto">
    <!-- Card Register AGID -->
    <div class="bg-white rounded-lg {{ $shadow ? 'shadow-lg' : '' }} {{ $border ? 'border border-gray-200' : '' }} overflow-hidden">
        <!-- Header Card con Colori AGID -->
        <div class="bg-primary-blue text-white px-6 py-4">
            <h1 class="text-2xl font-bold mb-2">{{ $title }}</h1>
            <p class="text-blue-100 text-sm">{{ $subtitle }}</p>
        </div>
        
        <!-- Body Card -->
        <div class="px-6 py-8">
            <!-- Livewire Register Component -->
            @livewire($livewireComponent)
        </div>
        
        <!-- Footer Card -->
        @if($footer)
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {!! $footer !!}
            </div>
        @else
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="text-center text-sm text-gray-600">
                    <p class="mb-2">
                        <strong>Hai già un account?</strong>
                    </p>
                    <p>
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-semibold underline">
                            Accedi qui
                        </a>
                    </p>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Info Accessibilità AGID -->
    <div class="mt-6 text-center text-sm text-gray-600">
        <p class="mb-2">
            <strong>Accessibilità:</strong> Questo servizio è conforme alle linee guida WCAG 2.1 AA.
        </p>
        <p>
            <a href="{{ route('pages.view', ['slug' => 'accessibility']) }}" 
               class="text-blue-600 hover:text-blue-800 underline">
                Dichiarazione di accessibilità
            </a>
        </p>
        <p class="mt-2 text-xs">
            <strong>Navigazione da tastiera:</strong> Usa Tab per navigare tra i campi e Invio per inviare il modulo.
        </p>
    </div>
</div>
```

#### B. Password Reset Card AGID
```blade
<!-- File: laravel/Themes/Sixteen/resources/views/components/blocks/forms/password-reset-card-agid.blade.php -->
@props([
    'title' => 'Reimposta Password',
    'subtitle' => 'Inserisci la tua email per ricevere il link di reset',
    'livewireComponent' => '\Modules\User\Http\Livewire\Auth\PasswordReset',
    'variant' => 'default',
    'size' => 'md',
    'shadow' => true,
    'border' => true,
    'footer' => '',
    'helpText' => '',
    'errorText' => '',
    'successText' => ''
])

<div class="max-w-md mx-auto">
    <!-- Card Password Reset AGID -->
    <div class="bg-white rounded-lg {{ $shadow ? 'shadow-lg' : '' }} {{ $border ? 'border border-gray-200' : '' }} overflow-hidden">
        <!-- Header Card con Colori AGID -->
        <div class="bg-primary-blue text-white px-6 py-4">
            <h1 class="text-2xl font-bold mb-2">{{ $title }}</h1>
            <p class="text-blue-100 text-sm">{{ $subtitle }}</p>
        </div>
        
        <!-- Body Card -->
        <div class="px-6 py-8">
            <!-- Livewire Password Reset Component -->
            @livewire($livewireComponent)
        </div>
        
        <!-- Footer Card -->
        @if($footer)
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {!! $footer !!}
            </div>
        @else
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="text-center text-sm text-gray-600">
                    <p class="mb-2">
                        <strong>Ricordi la password?</strong>
                    </p>
                    <p>
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-semibold underline">
                            Accedi qui
                        </a>
                    </p>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Info Accessibilità AGID -->
    <div class="mt-6 text-center text-sm text-gray-600">
        <p class="mb-2">
            <strong>Accessibilità:</strong> Questo servizio è conforme alle linee guida WCAG 2.1 AA.
        </p>
        <p>
            <a href="{{ route('pages.view', ['slug' => 'accessibility']) }}" 
               class="text-blue-600 hover:text-blue-800 underline">
                Dichiarazione di accessibilità
            </a>
        </p>
        <p class="mt-2 text-xs">
            <strong>Navigazione da tastiera:</strong> Usa Tab per navigare tra i campi e Invio per inviare il modulo.
        </p>
    </div>
</div>
```

## 🚨 REGOLE CRITICHE CONFERMATE

### 1. **Namespace Blocks - SEMPRE USARE**
```blade
<!-- ✅ CORRETTO -->
<x-forms.login-card-agid />
<x-forms.register-card-agid />
<x-forms.password-reset-card-agid />

<!-- ❌ ERRATO -->
<x-pub_theme::forms.login-card-agid />
<x-pub_theme::forms.login-card-agid />
```

### 2. **Props Uniformi - SEMPRE MANTENERE**
```php
@props([
    'title' => '',
    'subtitle' => '',
    'livewireComponent' => '',
    'variant' => 'default',
    'size' => 'md',
    'shadow' => true,
    'border' => true,
    'footer' => '',
    'helpText' => '',
    'errorText' => '',
    'successText' => ''
])
```

### 3. **Colori AGID - SEMPRE USARE**
```css
/* ✅ CORRETTO - Colori AGID */
.bg-primary-blue { background-color: #0066CC; }
.text-primary-blue { color: #0066CC; }
.bg-primary-dark { background-color: #004080; }
.text-primary-dark { color: #004080; }
```

### 4. **Accessibilità - SEMPRE INCLUDERE**
```blade
<!-- ✅ CORRETTO - Info accessibilità AGID -->
<div class="mt-6 text-center text-sm text-gray-600">
    <p class="mb-2">
        <strong>Accessibilità:</strong> Questo servizio è conforme alle linee guida WCAG 2.1 AA.
    </p>
    <p>
        <a href="{{ route('pages.view', ['slug' => 'accessibility']) }}" 
           class="text-blue-600 hover:text-blue-800 underline">
            Dichiarazione di accessibilità
        </a>
    </p>
    <p class="mt-2 text-xs">
        <strong>Navigazione da tastiera:</strong> Usa Tab per navigare tra i campi e Invio per inviare il modulo.
    </p>
</div>
```

## 📋 CHECKLIST IMPLEMENTAZIONE

### ✅ FASE 1: Correzione Namespace
- [ ] Usare `<x-forms.login-card-agid>` invece di `<x-pub_theme::forms.login-card-agid>`
- [ ] Verificare che il componente esista
- [ ] Testare che la pagina renderizzi correttamente

### ✅ FASE 2: Miglioramento Componente
- [ ] Aggiornare props per seguire sistema blocks
- [ ] Aggiungere supporto per footer personalizzato
- [ ] Migliorare accessibilità
- [ ] Testare componenti

### ✅ FASE 3: Creazione Componenti Mancanti
- [ ] Creare `register-card-agid.blade.php`
- [ ] Creare `password-reset-card-agid.blade.php`
- [ ] Testare intercambiabilità
- [ ] Documentare componenti

### ✅ FASE 4: Testing
- [ ] Test funzionali
- [ ] Test accessibilità
- [ ] Test responsive
- [ ] Test integrazione Filament

## 🔧 COMANDI PER IMPLEMENTAZIONE

### 1. Verificare Componente Esistente
```bash
# Verificare esistenza componente
ls laravel/Themes/Sixteen/resources/views/components/blocks/forms/login-card-agid.blade.php

# Verificare contenuto
cat laravel/Themes/Sixteen/resources/views/components/blocks/forms/login-card-agid.blade.php
```

### 2. Testare Namespace Corretto
```bash
# Clear cache
php artisan view:clear
php artisan cache:clear
php artisan config:clear

# Test server
php artisan serve

# Visit: http://localhost:8000/it/auth/login
```

### 3. Creare Componenti Mancanti
```bash
# Creare register card
touch laravel/Themes/Sixteen/resources/views/components/blocks/forms/register-card-agid.blade.php

# Creare password reset card
touch laravel/Themes/Sixteen/resources/views/components/blocks/forms/password-reset-card-agid.blade.php
```

## 🎯 RISULTATO FINALE

### Pagina Login AGID Corretta
```blade
<x-layouts.guest-agid>
    <x-slot name="title">
        Accesso - {{ config('app.name') }}
    </x-slot>

    <x-forms.login-card-agid 
        title="Accedi ai servizi digitali"
        subtitle="Utilizza le tue credenziali per accedere all'area riservata dei servizi online"
        livewire-component="\Modules\User\Http\Livewire\Auth\Login"
    />
</x-layouts.guest-agid>
```

### Componenti Forms AGID Completi
- ✅ **login-card-agid**: Esistente e funzionante
- ✅ **register-card-agid**: Da creare
- ✅ **password-reset-card-agid**: Da creare
- ✅ **Props uniformi**: Sistema blocks
- ✅ **Accessibilità**: WCAG 2.1 AA
- ✅ **Design AGID**: Colori e typography corretti

---
**Data Analisi**: Dicembre 2024  
**Problema Identificato**: ✅ Namespace sbagliato  
**Soluzione Proposta**: ✅ Namespace corretto + componenti migliorati  
**Prossimo Passo**: Implementazione Correzione 
