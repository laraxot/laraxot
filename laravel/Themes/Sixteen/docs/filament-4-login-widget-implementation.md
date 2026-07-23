# Implementazione Login Widget Filament 4 - Tema Sixteen

**Data**: 14 Ottobre 2025  
**Status**: ✅ Implementato  
**Riferimento Design**: https://docs.italia.it/accounts/login/

## 🎯 Obiettivo

Implementare un sistema di login conforme a:
- **AGID Bootstrap Italia** design guidelines
- **Filament 4.x** widget architecture
- **Laraxot Framework** best practices

## 📊 Analisi del Problema

### Problema Originale

Il widget `@livewire(\Modules\User\Filament\Widgets\Auth\LoginWidget::class)` **non renderizzava il form** perché:

1. **View Path Errato**: La view `pub_theme::filament.widgets.auth.login` non era correttamente configurata
2. **Mancava Wrapper Filament**: Il widget Filament 4 richiede wrapper specifici per funzionare
3. **Form Non Renderizzato**: Il metodo `{{ $this->form }}` non era presente nella view

### Riferimento AGID

Il design di https://docs.italia.it/accounts/login/ mostra:
- Login minimalista con GitHub OAuth
- Link a registrazione e password reset
- Design pulito e accessibile
- Conformità WCAG 2.1 AA

## ✅ Soluzione Implementata

### 1. Widget PHP (LoginWidget.php)

**File**: `/laravel/Modules/User/app/Filament/Widgets/Auth/LoginWidget.php`

```php
<?php

declare(strict_types=1);

namespace Modules\User\Filament\Widgets\Auth;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;
use Modules\Xot\Filament\Widgets\XotBaseWidget;
use Override;

class LoginWidget extends XotBaseWidget
{
    public ?array $data = [];

    /**
     * View path per il tema corrente.
     * IMPORTANTE: usa 'pub_theme::' per riferirsi al tema attivo
     */
    protected string $view = 'pub_theme::filament.widgets.auth.login';

    #[Override]
    public function getFormSchema(): array
    {
        return [
            TextInput::make('email')
                ->email()
                ->required()
                ->autofocus(),
            TextInput::make('password')
                ->password()
                ->required(),
            Checkbox::make('remember')
                ->label(__('user::auth.login.remember_me')),
        ];
    }

    public function login(): void
    {
        $data = $this->form->getState();

        $credentials = [
            'email' => is_string($data['email'] ?? null) ? $data['email'] : '',
            'password' => is_string($data['password'] ?? null) ? $data['password'] : '',
        ];

        if (Auth::attempt($credentials, $data['remember'] ?? false)) {
            session()->regenerate();
            redirect()->intended('/');
        }

        $this->addError('email', __('auth.failed'));
    }
}
```

### 2. View Blade (login.blade.php)

**File**: `/laravel/Themes/Sixteen/resources/views/filament/widgets/auth/login.blade.php`

```blade
{{-- Vista per il LoginWidget nel tema Sixteen --}}
{{-- Design ispirato a https://docs.italia.it/accounts/login/ --}}
{{-- Conforme AGID Bootstrap Italia + Filament 4.x --}}

<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-6">
            {{-- Header del form --}}
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-italia-gray-900 dark:text-white">
                    {{ __('user::auth.login.title') }}
                </h2>
                <p class="mt-2 text-sm text-italia-gray-600 dark:text-gray-400">
                    {{ __('user::auth.login.subtitle') }}
                </p>
            </div>

            {{-- Form renderizzato dal widget Filament 4 --}}
            <form wire:submit="login" class="space-y-6">
                {{ $this->form }}

                {{-- Submit Button AGID Style --}}
                <div class="mt-6">
                    <button 
                        type="submit" 
                        wire:loading.attr="disabled"
                        class="w-full flex justify-center items-center gap-2 py-3 px-4 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
                    >
                        <svg wire:loading class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>{{ __('user::auth.login.submit') }}</span>
                    </button>
                </div>
            </form>

            {{-- Links AGID Style --}}
            <div class="mt-6 space-y-4 text-center text-sm">
                <p class="text-italia-gray-600 dark:text-gray-400">
                    {{ __('user::auth.login.no_account') }}
                    <a 
                        href="{{ url('/' . app()->getLocale() . '/auth/register') }}" 
                        class="font-medium text-primary-600 hover:text-primary-500 underline"
                    >
                        {{ __('user::auth.login.register_now') }}
                    </a>
                </p>
                
                <p class="text-italia-gray-600 dark:text-gray-400">
                    {{ __('user::auth.login.forgot_password_text') }}
                    <a 
                        href="{{ url('/' . app()->getLocale() . '/auth/password/reset') }}" 
                        class="font-medium text-primary-600 hover:text-primary-500 underline"
                    >
                        {{ __('user::auth.login.reset_it') }}
                    </a>
                </p>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
```

### 3. Pagina Login (login.blade.php)

**File**: `/laravel/Themes/Sixteen/resources/views/pages/auth/login.blade.php`

```blade
<x-layouts.app>
    <x-slot name="title">
        {{ __('Login') }}
    </x-slot>

    <!-- AGID Login Section -->
    <section class="min-h-screen flex items-center justify-center bg-gradient-to-br from-primary-50 to-primary-100 dark:from-gray-900 dark:to-gray-800 py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            {{-- Logo/Brand Header --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-600 rounded-full mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-italia-gray-900 dark:text-white mb-2">
                    {{ __('Accedi ai servizi') }}
                </h1>
                <p class="text-italia-gray-600 dark:text-gray-400">
                    {{ __('Utilizza le tue credenziali per accedere alla piattaforma') }}
                </p>
            </div>

            {{-- Login Widget Filament 4 --}}
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg overflow-hidden">
                @livewire(\Modules\User\Filament\Widgets\Auth\LoginWidget::class)
            </div>
        </div>
    </section>
</x-layouts.app>
```

## 🔑 Punti Chiave

### 1. Wrapper Filament Obbligatori

```blade
<x-filament-widgets::widget>
    <x-filament::section>
        <!-- Contenuto widget -->
    </x-filament::section>
</x-filament-widgets::widget>
```

**Perché**: Filament 4 richiede questi wrapper per:
- Gestire correttamente gli stili
- Applicare il tema
- Integrare Livewire
- Gestire gli errori

### 2. Rendering del Form

```blade
<form wire:submit="login">
    {{ $this->form }}
    <!-- Bottoni e altri elementi -->
</form>
```

**Perché**: `{{ $this->form }}` renderizza automaticamente tutti i campi definiti in `getFormSchema()`

### 3. View Path con pub_theme

```php
protected string $view = 'pub_theme::filament.widgets.auth.login';
```

**Perché**: `pub_theme::` è un alias dinamico che punta al tema attivo, permettendo di sovrascrivere le view per tema

## 📋 Checklist Implementazione

- [x] Widget estende `XotBaseWidget`
- [x] View usa wrapper Filament (`<x-filament-widgets::widget>`)
- [x] Form renderizzato con `{{ $this->form }}`
- [x] Submit button con wire:loading
- [x] Link a registrazione e password reset
- [x] Design conforme AGID
- [x] Traduzioni con `__('user::auth.login.*')`
- [x] Accessibilità WCAG 2.1 AA
- [x] Responsive design
- [x] Dark mode support

## 🎨 Design AGID Compliance

### Colori
- Primary: `bg-primary-600` (blu PA)
- Text: `text-italia-gray-900`
- Links: `text-primary-600 hover:text-primary-500`

### Tipografia
- Heading: `text-3xl font-bold`
- Body: `text-sm text-italia-gray-600`

### Spacing
- Container: `max-w-md`
- Padding: `py-12 px-4`
- Gap: `space-y-6`

## 🔧 Troubleshooting

### Widget non si carica

**Problema**: View not found  
**Soluzione**: Verificare che il file esista in `/Themes/Sixteen/resources/views/filament/widgets/auth/login.blade.php`

### Form non appare

**Problema**: `{{ $this->form }}` non renderizza nulla  
**Soluzione**: Verificare che `getFormSchema()` ritorni un array con componenti Filament

### Stili non applicati

**Problema**: Il widget appare senza stili  
**Soluzione**: Verificare che i wrapper `<x-filament-widgets::widget>` e `<x-filament::section>` siano presenti

## 📚 Riferimenti

- [Filament 4 Widgets Documentation](https://filamentphp.com/docs/4.x/widgets)
- [AGID Design System](https://designers.italia.it/)
- [Bootstrap Italia](https://italia.github.io/bootstrap-italia/)
- [Laraxot Widget Rules](../../Modules/User/docs/auth_widget_rules.md)

## 🎯 Prossimi Passi

1. [ ] Implementare OAuth con SPID
2. [ ] Aggiungere CIE 3.0 authentication
3. [ ] Implementare 2FA
4. [ ] Aggiungere rate limiting
5. [ ] Test automatizzati
