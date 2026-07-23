# Header Login Button - Decisione e Implementazione

## Problema

Nella navigazione header del tema Two (`pub_theme::components.sections.header.v1`):
- ✅ Utenti loggati: hanno avatar dropdown con Dashboard, Profilo, Logout
- ✅ Pulsante CTA: "Richiedi Consulenza"
- ❌ Utenti NON loggati: nessun modo per accedere!

## Soluzione Implementata

Aggiunto pulsante di login per gli utenti non autenticati, posizionato accanto al pulsante CTA.

### Posizionamento

```
[Brand] [Nav Links] ... [Lang] [Accedi] [CTA]
```

Il login button è posizionato:
- Tra language switcher e CTA button
- Stesso stile del pulsante CTA (border white/transparent)
- Testo: "Accedi" con icon user

### Comportamento

| Stato | Contenuto Header Destro |
|-------|------------------------|
| Guest | [Lang] [Accedi] [CTA] |
| Auth  | [Lang] [User Avatar] [CTA] |

### File Modificato

- `Themes/Two/resources/views/components/sections/header/v1.blade.php`

### Implementazione

#### Desktop
```blade
@guest
    <a href="{{ route('login') }}" class="...">
        <x-filament::icon icon="ui-login" class="w-4 h-4" />
        {{ __('Accedi') }}
    </a>
@endguest
```

#### Mobile Menu
```blade
@guest
    <a href="{{ route('login') }}" class="...">
        <x-filament::icon icon="ui-login" class="w-4 h-4" />
        {{ __('Accedi') }}
    </a>
    @if(Route::has('register'))
        <a href="{{ route('register') }}">{{ __('Registrati') }}</a>
    @endif
@endauth
```

### Icone Utilizzate

- **Login**: `<x-filament::icon icon="ui-login" />` (da `Modules/UI/resources/svg/login.svg`)
- **User**: Icona nel dropdown utente (avatar)
- **Phone**: `<x-filament::icon icon="heroicon-o-phone" />` per CTA

### Test Effettuati

- [x] Utente non loggato vede pulsante "Accedi"
- [x] Utente loggato vede avatar dropdown
- [x] Link punta a `/auth/login` (route login)
- [x] Accessibilità: aria-label, focus states
- [x] Responsività: visibile su desktop, nel menu mobile

### Traduzioni

Già presenti in `Modules/Lang/lang/it/auth.php` e `header.php`:
- `Accedi` = "Accedi"
- `Esci` = "Esci"

## Note UX

Il pulsante di login è standard per siti che offrono:
- Area personale
- Servizi autenticati
- Consulenze riservate

La posizione (accanto al CTA) è coerente con pattern Bootstrap Italia.

## Riferimenti

- [Regola SVG no hardcoded](../../Modules/UI/docs/no-svg-hardcoded-in-blade.md)
- [Icon System UI Module](../../Modules/UI/docs/icon-system.md)
- [Header Component](components/sections/header/v1.blade.php)
