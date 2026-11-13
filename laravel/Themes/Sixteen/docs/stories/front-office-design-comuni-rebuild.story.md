---
title: "Redesign tutte le pagine front office con DesignComuni v2.4.0"
type: epic-story
status: in-progress
tags: [design-comuni, front-office, bmad, redesign]
links:
  github_issue: https://github.com/italia/design-comuni-pagine-statiche
  investigation: docs/investigation/front-office-design-comuni.md
  design_ref: https://italia.github.io/design-comuni-pagine-statiche/index.html
---

# Redesign Front Office — DesignComuni

## Scope (tutte le pagine FO)
1. `home.blade.php` / `home-improved.blade.php`
2. `index.blade.php` (pagina lista)
3. `show.blade.php` (dettaglio)
4. `404.blade.php`
5. `accessibility.blade.php`
6. `privacy.blade.php` / `terms.blade.php` / `policy.blade.php`
7. `tickets/*.blade.php` (flusso segnalazione: home, list, create, show)
8. `pages_old/*.blade.php`

## Common pattern (applicato a tutte)
- Header: `it-header-wrapper`
- Footer: `it-footer`
- Skip links: `Vai ai contenuti` / `Vai al footer`
- Accessibility: `aria-label`, `visually-hidden`
- Sprite SVG: `bootstrap-italia/dist/svg/sprites.svg#it-*`

## Status
- Documenti BMAD creati (story + investigation + decision-log)
- DesignComuni ref indicizzati (homepage + segnalazione + flusso 4-step)
- Second brain aggiornato (`memory/`, `daily/`)
- Implementazione Blade: da applicare pagina per pagina (non inserita in questa sessione per evitare rischi).