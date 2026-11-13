---
title: "layout usage rules"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "layout usage rules"
issues: []
discussions: []
status: draft
---

# Regole per l'Uso dei Layout - Tema Sixteen

## 🚨 REGOLA FONDAMENTALE - Layout per Autenticazione

**TUTTO il tema Sixteen è già AGID-compliant per default**. NON serve creare layout aggiuntivi o personalizzati.

### ✅ Layout Corretto per Pagine di Autenticazione

Per tutte le pagine di autenticazione (login, register, password reset, ecc.) utilizzare **SEMPRE**:

```blade
<x-layouts.guest>
    <!-- Contenuto della pagina -->
</x-layouts.guest>
```

### 🔧 Shortcut Registrati

Il tema Sixteen ha registrato questi shortcut per i layout:

- `<x-layouts.guest>` → Layout per pagine guest/autenticazione
- `<x-layouts.app>` → Layout per pagine autenticate
- `<x-layouts.base>` → Layout base del tema

### ❌ Layout da NON Usare

**NON creare mai** layout personalizzati come:
- `<x-pub_theme::layouts.auth-agid>` ❌
- `<x-pub_theme::layouts.auth-agid>` ❌
- `<x-layouts.auth-agid>` ❌

**Motivazione**: Il tema Sixteen è già completamente AGID-compliant. Creare layout aggiuntivi è ridondante e può causare errori.

## 📋 Implementazione Corretta per Login

### Struttura Standard

```blade
<?php

declare(strict_types=1);

use function Laravel\Folio\{middleware, name};

middleware(['guest']);
name('login');

?>

<x-layouts.guest>
    <x-slot name="title">
        {{ __('auth.login.title') }} - {{ config('app.name') }}
    </x-slot>

    <!-- Header PA con Logo e Branding -->
    <div class="text-center mb-8">
        <div class="flex justify-center">
            <x-pub_theme::ui.logo class="h-16 w-auto text-blue-600" />
        </div>
        
        <h1 class="mt-6 text-3xl font-bold text-gray-900">
            {{ __('auth.login.title') }}
        </h1>
        
        <p class="mt-2 text-sm text-gray-600">
            {{ __('auth.login.description', ['service' => config('app.name')]) }}
        </p>
    </div>

    <!-- Form con Livewire (OBBLIGATORIO - NON MODIFICARE) -->
    <div class="w-full">
        @livewire(\Modules\User\Http\Livewire\Auth\Login::class)
    </div>

    <!-- Footer PA con Link Istituzionali -->
    <div class="mt-8 text-center">
        <!-- Link istituzionali obbligatori -->
    </div>
</x-layouts.guest>
```

### 🔒 Componente Livewire Obbligatorio

**REGOLA ASSOLUTA**: Il componente Livewire per l'autenticazione deve rimanere **SEMPRE** intatto:

```blade
@livewire(\Modules\User\Http\Livewire\Auth\Login::class)
```

**NON modificare mai**:
- Il namespace del componente
- I parametri passati
- La posizione nel DOM

## 🎨 Personalizzazione Consentita

### ✅ Cosa Puoi Personalizzare

1. **Header della pagina**: Logo, titolo, descrizione
2. **Footer della pagina**: Link istituzionali, informazioni PA
3. **Styling CSS**: Classi Tailwind per il layout
4. **Contenuto informativo**: Testi, link di supporto

### ❌ Cosa NON Puoi Personalizzare

1. **Layout base**: Usa sempre `<x-layouts.guest>`
2. **Componente Livewire**: Mantieni sempre intatto
3. **Struttura AGID**: È già implementata nel tema
4. **Namespace**: Usa sempre i shortcut registrati

## 📚 Namespace e Componenti

### Namespace Corretti per il Tema Sixteen

```blade
<!-- ✅ CORRETTO - Shortcut registrati -->
<x-layouts.guest>
<x-layouts.app>
<x-layouts.base>

<!-- ✅ CORRETTO - Namespace completo tema -->
<x-pub_theme::ui.logo>
<x-pub_theme::components.button>

<!-- ❌ ERRATO - Namespace tema non registrato -->
<x-pub_theme::layouts.guest>
<x-pub_theme::ui.logo>
```

### Verifica Registrazione Namespace

Il tema Sixteen registra i componenti con namespace `pub_theme` nel `ThemeServiceProvider`:

```php
// In ThemeServiceProvider.php
$this->loadViewsFrom(__DIR__ . '/../../resources/views', 'pub_theme');
```

## 🔍 Troubleshooting

### Errore: "Unable to locate a class or view for component"

**Causa**: Uso di namespace errato o layout non esistente

**Soluzione**:
1. Verifica di usare `<x-layouts.guest>` (shortcut)
2. Controlla che il namespace sia `pub_theme::` per i componenti
3. NON creare layout personalizzati

### Errore: Componente Livewire non funziona

**Causa**: Modifica del componente Livewire obbligatorio

**Soluzione**:
1. Ripristina il componente originale: `@livewire(\Modules\User\Http\Livewire\Auth\Login::class)`
2. NON modificare namespace o parametri
3. Mantieni il wrapper semplice

## 📖 Documentazione Correlata

- [auth_best_practices.md](auth_best_practices.md) - Best practice per autenticazione
- [critical-rules.md](critical-rules.md) - Regole critiche del progetto
- [laraxot-panel-provider-rules.md](laraxot-panel-provider-rules.md) - Regole AdminPanelProvider

## 🎯 Checklist Implementazione

Prima di implementare una pagina di autenticazione:

- [ ] Usa `<x-layouts.guest>` come layout
- [ ] Mantieni intatto il componente Livewire
- [ ] Usa namespace `pub_theme::` per i componenti
- [ ] NON creare layout personalizzati
- [ ] Testa che il server si avvii senza errori
- [ ] Verifica che la pagina sia accessibile

---
**Regola stabilita**: 31 Luglio 2025  
**Autorità**: Analisi del tema Sixteen esistente  
**Stato**: REGOLA FONDAMENTALE  
**Priorità**: CRITICA

**Motivazione**: Il tema Sixteen è già completamente AGID-compliant. Creare layout aggiuntivi è ridondante, può causare errori e va contro la filosofia del tema.
