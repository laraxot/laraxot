# Login Page Enhancement - Documentazione

## Stato Attuale

### Profile URL ✅
- Il dropdown utente loggato va già a `/{locale}/profile`
- Il file `profile.json` esiste già in `config/local/techplanner/database/content/pages/`
- Codice in `auth-button.blade.php`:
```php
$profileUrl = url($locale . '/profile'); // genera /it/profile o /en/profile
```

## 1. Mostra Password

### Widget Login
Il widget `LoginWidget` ha già `->revealable()`:
```php
TextInput::make('password')
    ->password()
    ->required()
    ->revealable(),
```

Questo abilità il toggle "mostra/nascondi password" nativo di Filament.

## 2. Logo Non Visibile

### Analisi
Il logo dovrebbe essere visibile nel panel sinistro della login page:
```blade
<x-pub_theme::ui.logo class="h-14 w-auto" color="white" />
```

Possibili cause:
- Il componente `ui.logo` potrebbe non supportare il parametro `color`
- Il logo potrebbe non essere visibile su sfondo chiaro

### Soluzione
Verificare che il logo sia visibile sullo sfondo scuro del panel sinistro.

## 3. Effetto Particles

### Opzioni
1. **Canvas-based**: libreria JavaScript che disegna particelle
2. **CSS-only**: animazioni CSS con pseudo-elementi
3. **SVG**: particelle SVG animate

### Soluzione Proposta
Usare una libreria leggera come `particles.js` o creare un effetto CSS leggero.

## File da Modificare

1. `Modules/User/app/Filament/Widgets/LoginWidget.php` - già OK (revealable)
2. `Themes/Two/resources/views/pages/auth/login.blade.php` - logo + particles
3. `Themes/Two/docs/login-page-enhancements.md` - documentazione

## Riferimenti

- [Auth Login Page](./auth-login-page.md)
- [Social Login Icons](./auth-social-login-translations.md)
