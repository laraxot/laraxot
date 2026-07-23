# Guida Implementazione Login AGID - Tema Sixteen

> **Consolidato da**: login1-4.md, agid-login-*.md, login-agid-*.md  
> **Data consolidamento**: 2025-09-01  
> **Stato**: Implementazione completa e funzionante

## 🎯 **Panoramica**

Guida definitiva per l'implementazione del sistema di autenticazione AGID-compliant nel tema Sixteen. Questo documento consolidata tutte le implementazioni precedenti in una guida unificata e funzionante.

## 📋 **Requisiti Tecnici**

### Provider Panel
```php
<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use Modules\Xot\Providers\Filament\XotBaseMainPanelProvider;

class AdminPanelProvider extends XotBaseMainPanelProvider
{
    protected string $module = 'Predict';
    
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(
                in: app_path('Filament/Resources'),
                for: 'App\\Filament\\Resources'
            );
    }
}
```

### Widget di Autenticazione
**Namespace corretto**: `Modules\User\Filament\Widget\Auth`

```php
<?php

declare(strict_types=1);

namespace Modules\User\Filament\Widget\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Form;
use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('email')
                    ->label(__('user::auth.login.fields.email.label'))
                    ->email()
                    ->required()
                    ->autofocus()
                    ->extraInputAttributes(['tabindex' => 1]),
                TextInput::make('password')
                    ->label(__('user::auth.login.fields.password.label'))
                    ->password()
                    ->required()
                    ->extraInputAttributes(['tabindex' => 2]),
                Checkbox::make('remember')
                    ->label(__('user::auth.login.fields.remember.label'))
            ]);
    }
}
```

## 🎨 **Layout AGID**

### Layout Guest (AGID-compliant di default)
```blade
{{-- Themes/Sixteen/resources/views/layouts/guest.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- AGID Required Meta --}}
    <meta name="theme-color" content="#0066cc">
    <meta name="description" content="{{ $meta_description ?? __('sixteen::meta.default_description') }}">
    
    <title>{{ $title ?? config('app.name') }}</title>
    
    {{-- AGID Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'], 'themes/sixteen')
</head>
<body class="font-sans antialiased h-full bg-gray-50">
    {{-- AGID Skip Navigation --}}
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-blue-600 text-white px-4 py-2 rounded">
        {{ __('sixteen::accessibility.skip_to_content') }}
    </a>
    
    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            {{-- AGID Logo Area --}}
            <div class="text-center">
                <img class="mx-auto h-12 w-auto" src="{{ asset('images/logo.svg') }}" alt="{{ config('app.name') }}">
                <h1 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    {{ $heading ?? __('sixteen::auth.login.heading') }}
                </h1>
            </div>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <main id="main-content">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>
</body>
</html>
```

### Pagina Login
```blade
{{-- Themes/Sixteen/resources/views/pages/auth/login.blade.php --}}
<x-layouts.guest>
    <x-slot name="heading">
        {{ __('sixteen::auth.login.heading') }}
    </x-slot>
    
    <x-slot name="title">
        {{ __('sixteen::auth.login.title') }} - {{ config('app.name') }}
    </x-slot>

    {{-- Widget Filament per Login --}}
    @livewire(\Modules\User\Filament\Widget\Auth\Login::class)

    {{-- Links AGID --}}
    <div class="mt-6">
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300" />
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">{{ __('sixteen::auth.or') }}</span>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('pages.view', ['slug' => 'spid-login']) }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                {{ __('sixteen::auth.spid_login') }}
            </a>
        </div>
    </div>
</x-layouts.guest>
```

## 🌐 **Sistema Traduzioni AGID**

### File Traduzioni Core
```php
// Themes/Sixteen/lang/it/auth.php
return [
    'login' => [
        'heading' => 'Accedi al Portale',
        'title' => 'Accesso',
        'fields' => [
            'email' => [
                'label' => 'Indirizzo Email',
                'placeholder' => 'Inserisci la tua email',
                'help' => 'Utilizza l\'email associata al tuo account',
            ],
            'password' => [
                'label' => 'Password',
                'placeholder' => 'Inserisci la password',
                'help' => 'La password deve contenere almeno 8 caratteri',
            ],
            'remember' => [
                'label' => 'Ricordami',
                'help' => 'Mantieni l\'accesso per 30 giorni',
            ],
        ],
        'actions' => [
            'submit' => 'Accedi',
            'forgot_password' => 'Password dimenticata?',
            'register' => 'Non hai un account? Registrati',
        ],
        'messages' => [
            'success' => 'Accesso effettuato con successo',
            'failed' => 'Credenziali non valide',
            'throttle' => 'Troppi tentativi. Riprova tra :seconds secondi',
        ],
    ],
    'register' => [
        'heading' => 'Registrazione al Portale',
        'title' => 'Registrazione',
        // ... altri campi
    ],
    'spid_login' => 'Accedi con SPID',
    'cie_login' => 'Accedi con CIE',
    'or' => 'oppure',
];

// Themes/Sixteen/lang/it/accessibility.php
return [
    'skip_to_content' => 'Vai al contenuto principale',
    'skip_to_navigation' => 'Vai alla navigazione',
    'close_modal' => 'Chiudi finestra modale',
    'open_menu' => 'Apri menu',
    'close_menu' => 'Chiudi menu',
];

// Themes/Sixteen/lang/it/meta.php
return [
    'default_description' => 'Portale dei servizi digitali - Pubblica Amministrazione',
    'default_keywords' => 'servizi, digitali, pubblica amministrazione, cittadini',
];
```

## 🛣️ **Routing AGID**

### Route Register
```php
// routes/web.php o in Folio
Route::middleware('guest')->group(function () {
    Folio::path(resource_path('views/pages'))->middleware(['web']);
});

// Folio pages
// pages/auth/login.blade.php → /auth/login
// pages/auth/register.blade.php → /auth/register
// pages/auth/forgot-password.blade.php → /auth/forgot-password
```

### Route Pattern CMS
```blade
{{-- Pattern corretto per pagine CMS --}}
<a href="{{ route('pages.view', ['slug' => 'privacy']) }}">Privacy Policy</a>
<a href="{{ route('pages.view', ['slug' => 'accessibility']) }}">Dichiarazione Accessibilità</a>
<a href="{{ route('pages.view', ['slug' => 'legal-notice']) }}">Note Legali</a>
<a href="{{ route('pages.view', ['slug' => 'spid-login']) }}">Accesso SPID</a>
```

## 🔧 **Configurazione Vite**

### vite.config.js
```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    build: {
        outDir: '../../public_html/themes/sixteen',
        emptyOutDir: true,
        manifest: true,
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js'
            ],
            refresh: true,
            buildDirectory: 'themes/sixteen',
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
            '@css': '/resources/css',
        },
    },
});
```

## ✅ **Checklist Implementazione**

### Setup Iniziale
- [ ] Provider estende `XotBaseMainPanelProvider`
- [ ] Widget auth in namespace `Widget\Auth`
- [ ] Layout guest AGID-compliant
- [ ] Sistema traduzioni strutturato

### Pagine Auth
- [ ] Login page con widget Filament
- [ ] Register page con validazione
- [ ] Password reset funzionante
- [ ] Route CMS pattern corretto

### Accessibilità AGID
- [ ] Skip navigation
- [ ] Contrast ratio WCAG AA
- [ ] Keyboard navigation
- [ ] Screen reader support
- [ ] Meta tags appropriate

### Testing
- [ ] Login funzionale
- [ ] Registrazione funzionale
- [ ] Responsive design
- [ ] Performance ottimizzate

## 🚨 **Errori Comuni**

### 1. Namespace Widget Errato
```php
// ❌ ERRATO
namespace Modules\User\Filament\Widgets\Auth;

// ✅ CORRETTO  
namespace Modules\User\Filament\Widget\Auth;
```

### 2. Layout AGID Errato
```blade
{{-- ❌ ERRATO - Layout specifico non necessario --}}
<x-pub_theme::layouts.auth-agid>

{{-- ✅ CORRETTO - Layout standard (già AGID) --}}
<x-layouts.guest>
```

### 3. Route CMS Errate
```blade
{{-- ❌ ERRATO - Route dirette --}}
<a href="{{ route('privacy') }}">

{{-- ✅ CORRETTO - Pattern CMS --}}
<a href="{{ route('pages.view', ['slug' => 'privacy']) }}">
```

## 📚 **Riferimenti**

- [Linee Guida Design PA](https://designers.italia.it/)
- [AGID Guidelines](https://www.agid.gov.it/)
- [WCAG 2.1 AA](https://www.w3.org/WAI/WCAG21/Understanding/)
- [Filament Auth](https://filamentphp.com/docs/panels/users)

---

**Versione**: 3.0 (Consolidata)  
**Status**: Implementazione verificata e funzionante  
**Compatibilità**: Laravel 10+, Filament 3.x, AGID 2024
