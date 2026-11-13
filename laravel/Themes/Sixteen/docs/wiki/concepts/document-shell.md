---
title: "Document shell: modello autonomo"
type: decision
confidence: high
created: 2026-11-09
updated: 2026-11-11
tags: [shell, head, foot, document, layout]
related:
  - ../../../../../Modules/Cms/docs/foot-component-architecture.md
  - ../../../../../Modules/Cms/docs/seo-head-architecture.md
  - ../../../../TwentyOne/docs/manuale-uso-front-office.md
---

# Document shell in Sixteen

> **Revisione in corso (2026-11-11).** Questa decisione descrive lo stato attuale e resta vera finché la migrazione non è
> **implementata** (story `head-sixteen-layout-migration`, D-1…D-6 approvate): `<head>` è delegato a `<x-cms::head>`
> (modulo Cms, `laravel/head`); Sixteen aggiunge solo il suo comportamento (Alpine `mobileMenu`, FOUC guard) via
> `@push('head')`/`@push('styles')`. Policy SEO del tenant in `config/{tenant}/fixcity/head.php`. Riguarda solo `<head>`:
> `<x-cms::foot>` non è in discussione.
> A implementazione conclusa questo documento sarà riscritto come decisione rivista, non cancellato.

Sixteen usa `<x-cms::head />` per la testa (decisione aggiornata: la autonomia sul `<head>` è superata) e resta autonomo
per il corpo e il footer: non usa `<x-cms::foot />`.

## Modello

- I layout di produzione delegano `<head>` a `<x-cms::head />`; ogni layout possiede ancora `<html>/<body>` e chiude `</body></html>` in fondo. Eccezioni scritte: `offline.blade.php` (funziona offline), `livewire/auth/logout.blade.php` (documento standalone), `layouts/test.blade.php` (carica `app-test.css`, solo test).
- Assets registrati localmente con `@vite([...], 'themes/Sixteen')` e `@filamentScripts` (con `withCore: true` dove serve Alpine/auth).
- Nessun componente di coda emette tag di chiusura per elementi non aperti — coerente con la regola CMS Foot/TwentyOne.

## Differenza da TwentyOne

TwentyOne delega `<head>` a `cms::head` e la coda a `cms::foot` (layout chiude il documento). Sixteen è self-contained: se in futuro si volesse uniformare, il candidato è `layouts/main.blade.php` + `components/layouts/main.blade.php`, non i layout `bootstrap-italia*` (fixture di studio).

## Regola condivisa

Qualunque tema: un componente non deve mai emettere `</body>`/`</html>` se non ha aperto lui stesso quei tag. Vedi [CMS foot architecture](../../../../../Modules/Cms/docs/foot-component-architecture.md).
