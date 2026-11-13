---
title: "BMAD + Second Brain: Folio Architecture Analysis (index vs auth/login)"
type: note
status: draft
updated: 2026-10-18
tags: [docs, sixteen]
qmd: "bmad + second brain: folio architecture analysis (index vs auth/login) sixteen"
---
# BMAD + Second Brain: Folio Architecture Analysis (index vs auth/login)

## Benchmark / Root Cause
- `pages/index.blade.php` usa Volt (`new class extends Component`) + `@volt('home')` + `name('home')` + `middleware(PageSlugMiddleware::class)` + `<x-layouts.app>` + JSON CMS (`<x-page>`)
- `pages/auth/login.blade.php` era standalone Blade (senza Volt, senza `name()`, senza JSON, con `@vite` errato)
- Difformità architetturale = mancanza di design-comuni coerente

## Design (Filament Blueprint + Folio + Volt)
- **Folio** genera route dinamica dal percorso file (`pages/auth/login.blade.php` → `/auth/login`)
- **Volt** gestisce stato (mount, design JSON, dati CMS)
- **name('auth.login')** nome route per riferimento
- **JSON config** (`Themes/Sixteen/config/auth-login.json`) per design-comuni (gradient, shadow, accessibilità)
- **Layout**: `<x-layouts.app>` (non `folio.layouts.default` che non esiste)
- **Form**: `LoginWidget` (email/password) + `SocialLoginWidget` o pulsing manuale con `route('oauth.redirect')`
- **Accessibilità**: `aria-label`, `role="main"`, focus ring, contrasto WCAG 2.1 AA

## Execution
- [x] `pages/auth/login.blade.php` riscritto con Volt + `name('auth.login')` + JSON design
- [x] `@vite` corretto (`resources/css/app.css`, `resources/js/app.js`, `'themes/Sixteen'`)
- [x] Design-comuni applicato (gradient blue-700→blue-900, rounded-3xl, shadow-xl, border-slate-200)
- [x] Form credenziali + Google OAuth + register + forgot-password
- [x] Docs aggiornati: `docs/social-login-docs.md` e `docs/bmad/model-migration-parity.md`

Initiative: `KP-174d15a58ff94901b2b0df0d86b8907a`

- **D10**: Eliminare ridondanza design-comuni (`cmp-card-simple` + `card-wrapper`)
- **D11**: Usare Folio (`auth/login/[provider]/index.blade.php`) per redirect OAuth, senza modificare `web.php` o `api.php`
- **Stack**: Tailwind + Alpine + Lit + DaisyUI + Flowbite (NO Bootstrap Italia)
- **Visual parity**: 95%+ DesignComuni
- **DB Engine**: `mysql` (MariaDB), `DB_USERNAME=fixcity_usr`, `DB_PASSWORD=Oo0JDogyDS50vVKc7hA2`, `DB_DATABASE=fixcity_data`
