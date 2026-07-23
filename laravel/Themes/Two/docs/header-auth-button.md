# Header Auth Button - Documentazione

## Scopo

Il pulsante Login nell'header nav permette agli utenti guest di accedere rapidamente alla pagina di login da qualsiasi pagina del sito. È un pattern UX standard: l'header deve sempre offrire un accesso chiaro all'area riservata.

## Implementazione Attuale (Header v1)

**File**: `Themes/Two/resources/views/components/sections/header/v1.blade.php`

### Desktop (Right Actions)
- **@auth**: dropdown con avatar, Dashboard, Profilo, Esci
- **@guest**: link "Accedi" (`route('login')`) prima del CTA "Richiedi Consulenza"
- **Register**: link opzionale se `Route::has('register')`

### Mobile Menu
- **@auth**: blocco utente con avatar, Dashboard, Profilo, Esci
- **@guest**: link "Accedi" prima del CTA

### Traduzioni
- `pub_theme::header.auth.login` → "Accedi" (IT) / "Login" (EN)
- `pub_theme::header.auth.register` → "Registrati" (IT) / "Register" (EN)
- File: `Themes/Two/lang/{locale}/header.php` (namespace `pub_theme` da CmsServiceProvider)

### Route
- Login: `route('login')` (locale-aware, es. `/it/auth/login`)
- Register: `route('register')` se registrazione abilitata
- Profilo: `/{locale}/profile` (es. `/it/profile`) - vedi [profile-page](profile-page.md)

## Situazione Precedente (header_bi5)

In `header_bi5.blade.php` il bottone aveva URL hardcoded. La soluzione v1 usa route dinamiche e @auth/@guest.

## Soluzione Proposta (storica)

### 1. Struttura Componenti (Module)

Creare componente riutilizzabile in `Modules/User/resources/views/components/ui/app/auth-button.blade.php`:

```blade
@auth
    {{-- Utente loggato: mostra dropdown con profilo/logout --}}
    <div class="dropdown">
        <button class="btn btn-primary btn-icon" data-bs-toggle="dropdown">
            <img src="{{ auth()->user()->avatar_url ?? avatar(auth()->user()) }}" />
        </button>
        <ul class="dropdown-menu">
            <li><a href="{{ route('profile') }}">{{ __('user::auth.profile') }}</a></li>
            <li><a href="{{ route('logout') }}">{{ __('user::auth.logout') }}</a></li>
        </ul>
    </div>
@else
    {{-- Ospite: mostra bottone login --}}
    <a href="{{ route('login') }}" class="btn btn-primary btn-icon btn-full">
        <span class="rounded-icon">
            <svg class="icon icon-primary"><use href="#it-user"></use></svg>
        </span>
        <span>{{ __('user::auth.login') }}</span>
    </a>
@endauth
```

### 2. Integrazione Theme

In `Themes/Two/resources/views/components/sections/header_bi5.blade.php`:

```blade
{{-- Sostituire il bottone statico con --}}
@include('user::components.ui.app.auth-button')
```

### 3. Traduzioni Necessarie

In `Modules/User/lang/en/auth.php`:
```php
'auth_button' => [
    'login' => 'Accedi',
    'logout' => 'Esci',
    'profile' => 'Il mio profilo',
    'dashboard' => 'Dashboard',
],
```

### 4. Convenzioni

| Aspetto | Regola |
|---------|--------|
| Posizione | `Modules/User/resources/views/components/ui/app/` |
| Naming | `auth-button.blade.php` (kebab-case) |
| Traduzioni | `user::auth.auth_button.*` |
| Route | `route('login')`, `route('logout')` |
| Auth check | `@auth` / `@guest` Blade directive |

## File da Modificare

1. `Themes/Two/resources/views/components/sections/header_bi5.blade.php` - Sostituire bottone statico
2. `Modules/User/lang/en/auth.php` - Aggiungere traduzioni
3. (Opzionale) `Modules/User/resources/views/components/ui/app/auth-button.blade.php` - Creare componente riutilizzabile

## Riferimenti

- [header-components.md](../Modules/User/docs/header-components.md)
- [header-nav-spec.md](./header-nav-spec.md) - Sezione "Auth Integration"
