---
title: "FO PA tokens — uniformità Design Comuni"
type: concept
confidence: high
created: 2026-06-04
updated: 2026-06-04
tags: [sixteen, design-comuni, css, filament, auth]
related:
  - ../../architecture/fo-pa-tokens-uniformity.md
  - design-comuni-site-wide-component-css-rule.md
  - ../../../../../docs/wiki/decisions/fo-pa-tokens-no-per-page-hex.md
  - ../../../../../Modules/Xot/docs/wiki/concepts/filament-pa-design-colors.md
---

# FO PA tokens — uniformità

## Scopo

Allineare form Filament FO (login, register, wizard) al modello [design-comuni-pagine-statiche](https://github.com/italia/design-comuni-pagine-statiche): **token + componenti**, non CSS con hex ripetuti per `data-page`.

## Stack

```text
PaDesignColors.php
  → FilamentColor::register()
  → :root --primary-*
  → civic-design-tokens.css (--fixcity-primary)
  → .fo-filament-form-shell + <x-filament::button color="primary">
```

## File

| Ruolo | Path |
|-------|------|
| Token | `resources/css/components/civic-design-tokens.css` |
| Shell campi | `resources/css/components/fo-filament-form-shell.css` |
| Layout auth | `resources/css/app/14-auth-login.css` (no colori CTA) |
| Vista login | `resources/views/filament/widgets/auth/login.blade.php` |

## Zen

Il tema **veste**; il modulo **logica**. Il colore CTA si decide **una volta** in PHP/CSS token, non per ogni URL.

## Collegamenti

- [Architettura](../architecture/fo-pa-tokens-uniformity.md)
- [ADR root](../../../../docs/wiki/decisions/fo-pa-tokens-no-per-page-hex.md)
- [Auth login UX](../design/auth-login-ux-design-wcag.md)
