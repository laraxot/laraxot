# Regole Critiche del Progetto - Tema Sixteen

## 🚨 REGOLE FONDAMENTALI - DA RICORDARE SEMPRE

### 1. REGOLA CRITICA - AdminPanelProvider

**LEGGE ASSOLUTA**: `AdminPanelProvider` deve SEMPRE estendere `XotBaseMainPanelProvider`

```php
<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use Modules\Xot\Providers\Filament\XotBaseMainPanelProvider;

class AdminPanelProvider extends XotBaseMainPanelProvider
{
    // Implementazione specifica del progetto
}
```

**❌ MAI FARE**:
```php
// ERRATO - NON USARE MAI
class AdminPanelProvider extends PanelProvider
{
    // Questo è SBAGLIATO!
}
```

**✅ SEMPRE FARE**:
```php
// CORRETTO - USARE SEMPRE
class AdminPanelProvider extends XotBaseMainPanelProvider
{
    // Questo è GIUSTO!
}
```

### 2. REGOLA CRITICA - Autenticazione

**LEGGE ASSOLUTA**: Per i form di autenticazione utilizzare SEMPRE widget Filament, NON Volt!

```blade
{{-- ✅ CORRETTO - Widget Filament per autenticazione --}}
@livewire(\Modules\User\Filament\Widget\Auth\Login::class)
@livewire(\Modules\User\Filament\Widget\Auth\Register::class)
@livewire(\Modules\User\Filament\Widget\Auth\PasswordReset::class)

{{-- ❌ ERRATO - NON usare Volt per autenticazione --}}
@volt('auth.login')
@volt('auth.register')
@volt('auth.password-reset')
```

### 3. REGOLA CRITICA - Namespace Widget

**LEGGE ASSOLUTA**: Namespace corretto per widget Filament

```php
// ✅ CORRETTO
namespace Modules\User\Filament\Widget\Auth;

// ❌ ERRATO
namespace Modules\User\Filament\Widgets\Auth;
```

### 4. REGOLA CRITICA - Componenti Blade

**LEGGE ASSOLUTA**: Verificare SEMPRE l'esistenza dei componenti prima di usarli!

```blade
{{-- ✅ CORRETTO - Componenti che esistono --}}
<x-filament::icon name="heroicon-o-user" />
<x-filament::button>Pulsante</x-filament::button>
<x-filament::card>Contenuto</x-filament::card>

{{-- ❌ ERRATO - Componenti che NON esistono --}}
<x-filament::layouts.card>  {{-- NON ESISTE --}}
<x-filament::section>       {{-- NON ESISTE --}}
<x-filament::input.wrapper> {{-- NON ESISTE --}}
```

### 5. REGOLA CRITICA - Icone Heroicons

**LEGGE ASSOLUTA**: Usare sempre il componente Filament per le icone

```blade
{{-- ✅ CORRETTO - Componente Filament per icone --}}
<x-filament::icon name="heroicon-o-x-mark" />
<x-filament::icon name="heroicon-o-user" />
<x-filament::icon name="heroicon-o-home" />

{{-- ❌ ERRATO - Componente diretto --}}
<x-heroicon-o-x-mark />  {{-- PUÒ NON FUNZIONARE --}}
<x-heroicon-o-user />    {{-- PUÒ NON FUNZIONARE --}}
```

### 6. REGOLA CRITICA - Layout Sixteen

**LEGGE ASSOLUTA**: Il tema Sixteen è completamente AGID compliant, usare sempre i layout standard

```blade
{{-- ✅ CORRETTO - Layout standard Sixteen (già AGID) --}}
<x-pub_theme::layouts.guest>
<x-layouts.guest>

{{-- ❌ ERRATO - Non serve specificare AGID --}}
<x-pub_theme::layouts.auth-agid>
<x-layouts.auth-agid>
```

**Motivazione**:
- Il tema Sixteen è **completamente AGID compliant** per design
- **Non serve** specificare `auth-agid` perché è il default
- **Layout guest** include già tutti gli elementi AGID necessari
- **Shortcut** sono già registrati per usare `<x-layouts.guest>`

### 7. REGOLA CRITICA - Route Pagine CMS

**LEGGE ASSOLUTA**: Tutte le pagine statiche usano il pattern `pages.view` con slug

```blade
{{-- ✅ CORRETTO - Pattern standard per pagine CMS --}}
<a href="{{ route('pages.view', ['slug' => 'privacy']) }}">
<a href="{{ route('pages.view', ['slug' => 'accessibility']) }}">
<a href="{{ route('pages.view', ['slug' => 'contacts']) }}">
<a href="{{ route('pages.view', ['slug' => 'legal-notice']) }}">

{{-- ❌ ERRATO - Route dirette che non esistono --}}
<a href="{{ route('privacy') }}">
<a href="{{ route('accessibility') }}">
<a href="{{ route('contacts') }}">
```

**Motivazione**:
- **Tutte le pagine** sono gestite dal sistema CMS con Folio
- **Slug dinamici** permettono flessibilità e localizzazione
- **`pages.view`** è la route standard per pagine CMS
- **Consistenza** in tutto il progetto

## 🏗️ Architettura Fondamentale

### 1. Struttura Provider

```
app/Providers/Filament/
├── AdminPanelProvider.php  ← DEVE estendere XotBaseMainPanelProvider
├── AuthPanelProvider.php   ← DEVE estendere XotBaseAuthPanelProvider
└── TenantPanelProvider.php ← DEVE estendere XotBaseTenantPanelProvider
```

### 2. Struttura Widget

```
Modules/User/Filament/Widget/Auth/
├── Login.php              ← Widget per login
├── Register.php           ← Widget per registrazione
├── PasswordReset.php      ← Widget per reset password
└── Logout.php             ← Widget per logout
```

### 3. Struttura Componenti

```
Themes/Sixteen/resources/views/components/
├── layouts/               ← Layout components
├── ui/                    ← UI components
├── blocks/                ← Block components
└── forms/                 ← Form components
```

### 4. Struttura Layout

```
Themes/Sixteen/resources/views/layouts/
├── guest.blade.php        ← Layout AGID per autenticazione
├── app.blade.php          ← Layout AGID per applicazione
├── auth.blade.php         ← Layout deprecato
├── base.blade.php         ← Layout base AGID
└── navigation.blade.php   ← Layout con navigazione AGID
```

### 5. Struttura Route CMS

```
Themes/Sixteen/resources/views/pages/
├── pages/
│   └── [slug].blade.php  ← Gestore dinamico pagine CMS
├── auth/
│   ├── login.blade.php   ← Pagina login
│   └── register.blade.php ← Pagina registrazione
└── index.blade.php       ← Homepage
```

## 🔧 Configurazione Critica

### 1. Service Provider Registration

```php
// config/app.php
'providers' => [
    // ...
    App\Providers\Filament\AdminPanelProvider::class,
    App\Providers\Filament\AuthPanelProvider::class,
    App\Providers\Filament\TenantPanelProvider::class,
],
```

### 2. Composer Autoload

```json
// composer.json
{
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Modules\\": "Modules/",
            "Themes\\": "Themes/"
        }
    }
}
```

### 3. Filament Configuration

```php
// config/filament.php
'panels' => [
    'admin' => [
        'provider' => App\Providers\Filament\AdminPanelProvider::class,
        'path' => 'admin',
        'domain' => null,
    ],
    'auth' => [
        'provider' => App\Providers\Filament\AuthPanelProvider::class,
        'path' => 'auth',
        'domain' => null,
    ],
],
```

## 📋 Checklist Critica

### Prima di ogni implementazione, verificare:

- [ ] **AdminPanelProvider** estende `XotBaseMainPanelProvider`
- [ ] **Widget di autenticazione** usano Filament, non Volt
- [ ] **Namespace** widget è `Widget\Auth`, non `Widgets\Auth`
- [ ] **Componenti Blade** esistono prima di usarli
- [ ] **Icone** usano `<x-filament::icon>`
- [ ] **Layout** usano `<x-layouts.guest>` o `<x-layouts.app>`
- [ ] **Route pagine** usano `route('pages.view', ['slug' => 'nome'])`
- [ ] **Service Provider** sono registrati in composer.json
- [ ] **Configurazione Filament** è corretta

### Prima di ogni commit, verificare:

- [ ] **Regole critiche** sono rispettate
- [ ] **Namespace** sono corretti
- [ ] **Estensioni** sono corrette
- [ ] **Componenti** esistono
- [ ] **Documentazione** è aggiornata

## 🚨 Errori Comuni da Evitare

### 1. Errore: "Unable to locate a class or view for component [heroicon-o-x]"

**Causa**: Uso diretto di componenti Heroicons senza Filament

**Soluzione**:
```blade
{{-- ❌ ERRATO --}}
<x-heroicon-o-x-mark />

{{-- ✅ CORRETTO --}}
<x-filament::icon name="heroicon-o-x-mark" />
```

### 2. Errore: "Class 'XotBaseMainPanelProvider' not found"

**Causa**: AdminPanelProvider non estende la classe corretta

**Soluzione**:
```php
<?php
use Modules\Xot\Providers\Filament\XotBaseMainPanelProvider;

class AdminPanelProvider extends XotBaseMainPanelProvider
{
    // Implementazione
}
```

### 3. Errore: "Widget not found"

**Causa**: Namespace sbagliato per widget

**Soluzione**:
```php
// ✅ CORRETTO
namespace Modules\User\Filament\Widget\Auth;

// ❌ ERRATO
namespace Modules\User\Filament\Widgets\Auth;
```

### 4. Errore: "Unable to locate a class or view for component [pub_theme::layouts.auth-agid]"

**Causa**: Uso errato di layout AGID specifici

**Soluzione**:
```blade
{{-- ❌ ERRATO - Non serve specificare AGID --}}
<x-pub_theme::layouts.auth-agid>

{{-- ✅ CORRETTO - Layout standard (già AGID) --}}
<x-layouts.guest>
<x-pub_theme::layouts.guest>
```

### 5. Errore: "Route [privacy] not defined"

**Causa**: Uso errato di route dirette per pagine CMS

**Soluzione**:
```blade
{{-- ❌ ERRATO - Route che non esistono --}}
<a href="{{ route('privacy') }}">
<a href="{{ route('accessibility') }}">

{{-- ✅ CORRETTO - Pattern standard per pagine CMS --}}
<a href="{{ route('pages.view', ['slug' => 'privacy']) }}">
<a href="{{ route('pages.view', ['slug' => 'accessibility']) }}">
```

## 📚 Documentazione di Riferimento

### File Critici da Consultare:

1. **`laravel/app/Providers/Filament/AdminPanelProvider.php`** - Esempio corretto
2. **`Modules/Xot/Providers/Filament/XotBaseMainPanelProvider.php`** - Classe base
3. **`laravel/Themes/Sixteen/docs/critical-rules.md`** - Questo file
4. **`laravel/Themes/Sixteen/docs/login_correction_implementation.md`** - Implementazione login
5. **`laravel/Themes/Sixteen/docs/layout-namespace-correction.md`** - Correzione layout
6. **`laravel/Themes/Sixteen/docs/icon-error-correction.md`** - Correzione icone
7. **`laravel/Themes/Sixteen/docs/route-pattern-correction.md`** - Correzione route

### Comandi Critici:

```bash
# Verifica configurazione
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Verifica autoload
composer dump-autoload

# Verifica provider
php artisan config:show app.providers | grep Filament

# Verifica route
php artisan route:list | grep pages
```

---

**Data Creazione**: Dicembre 2024  
**Versione**: 1.0  
**Status**: Regole Critiche Documentate  
**Priorità**: ASSOLUTA - Da ricordare sempre 