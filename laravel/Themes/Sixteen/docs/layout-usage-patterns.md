# Pattern di Utilizzo Layout - Tema Sixteen

## Problema Identificato

L'errore `Unable to locate a class or view for component [pub_theme::layouts.auth-agid]` è causato da un utilizzo errato del layout.

## Analisi del Problema

### ❌ Utilizzo Errato
```blade
<x-pub_theme::layouts.auth-agid>
    <!-- contenuto -->
</x-pub_theme::layouts.auth-agid>
```

### Problemi Identificati
1. **Layout non necessario**: Il tema Sixteen è già AGID-compliant di default
2. **Layout specifico non esistente**: `auth-agid` non è un layout standard
3. **Shortcut disponibile**: Esiste già lo shortcut `<x-layouts.guest>`

## Soluzioni Corrette

### ✅ Pattern 1: Utilizzo Shortcut (RACCOMANDATO)
```blade
<x-layouts.guest>
    <x-slot name="title">
        {{ __('auth.login.title') }}
    </x-slot>
    
    <!-- Contenuto del form -->
    @livewire(\Modules\User\Http\Livewire\Auth\Login::class)
</x-layouts.guest>
```

### ✅ Pattern 2: Utilizzo Namespace Completo
```blade
<x-pub_theme::layouts.guest>
    <x-slot name="title">
        {{ __('auth.login.title') }}
    </x-slot>
    
    <!-- Contenuto del form -->
    @livewire(\Modules\User\Http\Livewire\Auth\Login::class)
</x-pub_theme::layouts.guest>
```

### ✅ Pattern 3: Utilizzo Layout Auth Standard
```blade
<x-pub_theme::layouts.auth>
    <x-slot name="title">
        {{ __('auth.login.title') }}
    </x-slot>
    
    <!-- Contenuto del form -->
    @livewire(\Modules\User\Http\Livewire\Auth\Login::class)
</x-pub_theme::layouts.auth>
```

## Layout Disponibili

### Layout Guest
- **File**: `resources/views/layouts/guest.blade.php`
- **Scopo**: Layout per utenti non autenticati
- **Caratteristiche**: Centrato, sfondo grigio, logo in alto
- **Shortcut**: `<x-layouts.guest>`

### Layout Auth
- **File**: `resources/views/layouts/auth.blade.php`
- **Scopo**: Layout per pagine di autenticazione
- **Caratteristiche**: Estende base layout, sfondo grigio
- **Shortcut**: `<x-layouts.auth>`

### Layout App
- **File**: `resources/views/layouts/app.blade.php`
- **Scopo**: Layout per applicazioni autenticate
- **Caratteristiche**: Header, sidebar, footer
- **Shortcut**: `<x-layouts.app>`

### Layout Base
- **File**: `resources/views/layouts/base.blade.php`
- **Scopo**: Layout base per tutti i temi
- **Caratteristiche**: HTML base, meta tags, scripts
- **Shortcut**: `<x-layouts.base>`

## AGID Compliance

### Tema Sixteen AGID-Ready
Il tema Sixteen è **già AGID-compliant** di default:
- ✅ Header istituzionale
- ✅ Breadcrumb navigation
- ✅ Footer con link obbligatori
- ✅ Accessibilità WCAG 2.1 AA
- ✅ Responsive design
- ✅ Keyboard navigation
- ✅ Screen reader support

### Non Serve Layout Specifico
**NON** serve creare layout specifici come `auth-agid` perché:
1. Il tema è già conforme alle linee guida AGID
2. Tutti i layout ereditano la compliance AGID
3. La personalizzazione avviene tramite componenti, non layout

## Best Practices

### 1. Utilizzare Shortcut
```blade
<!-- ✅ CORRETTO -->
<x-layouts.guest>

<!-- ❌ ERRATO -->
<x-pub_theme::layouts.auth-agid>
```

### 2. Utilizzare Layout Standard
```blade
<!-- ✅ CORRETTO -->
<x-layouts.guest>
<x-layouts.auth>
<x-layouts.app>

<!-- ❌ ERRATO -->
<x-layouts.auth-agid>
<x-layouts.custom-agid>
```

### 3. Passare Slot Appropriati
```blade
<x-layouts.guest>
    <x-slot name="title">
        {{ __('auth.login.title') }}
    </x-slot>
    
    <x-slot name="description">
        {{ __('auth.login.description') }}
    </x-slot>
    
    <!-- Contenuto principale -->
    {{ $slot }}
</x-layouts.guest>
```

### 4. Utilizzare Componenti Livewire
```blade
<!-- ✅ CORRETTO -->
@livewire(\Modules\User\Http\Livewire\Auth\Login::class)

<!-- ❌ ERRATO -->
<!-- Form custom che duplica funzionalità -->
```

## Errori Comuni

### Errore: Layout non trovato
```
Unable to locate a class or view for component [pub_theme::layouts.auth-agid]
```

**Soluzione**: Usare layout standard
```blade
<!-- Da -->
<x-pub_theme::layouts.auth-agid>

<!-- A -->
<x-layouts.guest>
```

### Errore: Namespace errato
```
View [pub_theme::layouts.custom] not found
```

**Soluzione**: Verificare che il layout esista
```bash
# Verificare layout disponibili
ls laravel/Themes/Sixteen/resources/views/layouts/
```

### Errore: Shortcut non funzionante
```
Component [layouts.guest] not found
```

**Soluzione**: Verificare registrazione componenti
```bash
php artisan view:clear
php artisan cache:clear
```

## Collegamenti

- [Documentazione AGID Compliance](agid-login-implementation.md)
- [Layout Guest](resources/views/layouts/guest.blade.php)
- [Layout Auth](resources/views/layouts/auth.blade.php)
- [Layout App](resources/views/layouts/app.blade.php)
- [Layout Base](resources/views/layouts/base.blade.php)

