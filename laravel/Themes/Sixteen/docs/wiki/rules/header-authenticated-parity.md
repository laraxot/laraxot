---
title: Header Authenticated State Parity
description: Regole per il rendering dell'header quando l'utente è autenticato
tags: [header, authentication, area-personale, bootstrap-italia]
sources:
  - ../../../resources/views/components/header-comune.blade.php
  - ../../../resources/views/components/sections/header/partials/user-dropdown.blade.php
  - https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-area-personale.html
---

# Header Authenticated State Parity

## Obiettivo
Quando l'utente è **loggato**, l'header deve rispettare il pattern Design Comuni.

## Pattern di Riferimento
```
.it-user-wrapper .dropdown
  └─ <a class="btn btn-primary btn-icon btn-full">
       ├─ .rounded-icon > img (avatar)
       ├─ .d-none.d-lg-block (nome utente - nascosto su mobile)
       └─ .icon.d-none.d-lg-block > .it-expand (chevron - nascosto su mobile)
```

## Mobile View Rules
- **Avatar**: Sempre visibile
- **Nome utente**: NASCOSTO su mobile (`d-none d-lg-block`)
- **Icona expand**: NASCOSTA su mobile (`d-none d-lg-block`)

## Desktop View Rules
- **Avatar + Nome + Expand icon**: Tutti visibili
- **Dropdown menu**: Deve aprirsi con `data-bs-toggle="dropdown"`

## Asset Path
```
/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-expand
```
> Se questo asset restituisce 404, verificare symlink in `public/`

## Tracciamento Issue
- Issue: #269 - Header image parity
- Discussione: #259 - Design decisions

## Acceptance Criteria
- [ ] Mobile: solo avatar visibile nell'header slim
- [ ] Desktop: avatar + nome + dropdown presenti
- [ ] SVG sprites non generano 404
- [ ] Dropdown menu funziona correttamente