---
name: segnalazione-crea-navbar-green-contract
description: Header navbar green contract for the segnalazione-crea wizard
type: concept
---

# Segnalazione Crea Navbar Green Contract

On `/it/tests/segnalazione-crea`, the header navbar items `Amministrazione`, `Novita`, `Servizi`, `Vivere il Comune`, `Iscrizioni`, `Estate in citta`, `Polizia locale`, and `Tutti gli argomenti` must use the same green background as the logo/slogan band (`#007a52`) with white text.

They must not render with Bootstrap Italia blue or with a white navbar background.

## Root Cause

The regression was recurring because:

- the old `header-color-parity.md` page documented a light navbar / `theme-light-desk` direction for the same route;
- `resources/css/app.css` contains repeated generic header blocks, so changing an earlier `.it-header-navbar-wrapper` rule does not guarantee the runtime result;
- Bootstrap Italia can style wrapper, lists, links, and spans independently, so changing only the wrapper can still leave visible blue/white link backgrounds.

## Contract

- Runtime owner: `laravel/Themes/Sixteen/resources/views/components/sections/header/v1.blade.php`.
- CSS owner: `laravel/Themes/Sixteen/resources/css/app.css`.
- Final rules must be scoped to `.it-header-wrapper.is-segnalazione-crea` and placed late in the cascade.
- The rule must cover wrapper, `.navbar`, `.navbar-nav`, `.navbar-secondary`, `.nav-item`, `.nav-link`, and `.nav-link span`.
- Do not use `theme-light-desk` for this route.
- Verify computed styles for every listed link after `npm run build && npm run copy`.
