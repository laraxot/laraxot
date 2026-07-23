---
title: Playwright — Visual Testing Tema Sixteen
description: Verifica visuale e regressione per il tema Sixteen (Bootstrap Italia) nel 2026
tags: [playwright, visual-testing, sixteen, bootstrap-italia, laravel, frontend]
---

# Playwright — Visual Testing Tema Sixteen

Il testing visivo per il tema Sixteen assicura la parità con il design "Comuni" e previene regressioni nell'header, footer e wizard.

## 1. Tooling & Workflow

Seguire gli standard in [Visual Control Mastery](../../../../docs/wiki/concepts/visual-control-mastery.md).

### Laravel Headless Browser Tester
Comando rapido per screenshot veloci del tema:

```bash
# Screenshot della homepage in mobile
php artisan browser:test /it --screenshot-path=themes/sixteen/mobile-home.png --screenshot-width=mobile
```

### Pest v4 / Playwright
Test strutturati per stati complessi (es. header autenticato):

```php
it('header shows user profile when logged in', function () {
    $user = User::factory()->create();
    actingAs($user);
    
    visit('/')
        ->assertSee($user->name)
        ->assertScreenshotsMatches();
});
```

## 2. Punti di Controllo Sixteen

### Header State (Guest vs Auth)
L'header è il componente più critico. Verificare:
- **Guest**: Logo, Slogan, CTA "Accedi".
- **Auth**: Avatar, Nome utente, Dropdown menu.
- **SSoT**: Sempre testare `v1.blade.php`.

### Design Comuni Parity
- **Colori**: Navbar deve essere verde branding.
- **Spacing**: Margini tra sezioni conformi agli standard Italia.
- **Responsive**: Verificare visualmente i breakpoint 375px, 768px, 1440px.

## 3. Screenshot Organization

Conservare gli screenshot di riferimento in:
`laravel/Themes/Sixteen/docs/wiki/assets/screenshots/`

- `header/guest-desktop.png`
- `header/auth-desktop.png`
- `wizard/step-2-location.png`

## 4. Troubleshooting Visuale

- **Fonts**: Se i test falliscono in CI per font-rendering, usare Docker.
- **Dynamic Content**: Mascherare date e ID variabili usando `mask: [selector]`.
- **Transitions**: Disabilitare le animazioni Bootstrap Italia durante i test.

---
*Vedi anche:*
- [Visual Control Mastery](../../../../docs/wiki/concepts/visual-control-mastery.md)
- [Header Authenticated State](./concepts/header-authenticated-state.md)
