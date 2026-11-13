---
title: "Fix duplicate HTML in profile delete-user-form (Sixteen)"
type: story
status: completed
created: 2026-10-15
updated: 2026-10-15
module: Sixteen
tags: [blade, duplicate, cleanup]
---

# Fix duplicate HTML in profile delete-user-form (Sixteen)

## Richiesta
I due file `profile/delete-user-form.blade.php` e `profile/partials/delete-user-form.blade.php` contenevano righe duplicate
(da un merge non risolto): due `<x-input type="password">`, due `<x-input-error for="password">`, due `<x-action-section>`
aperti.

## Analisi
- `profile/delete-user-form.blade.php` (usato da `@livewire('profile.delete-user-form')` in `show.blade.php:44`) aveva
  `<x-action-section>` duplicato all'inizio e due input password.
- `profile/partials/delete-user-form.blade.php` (usato da `edit.blade.php:24`) aveva due `<x-input-label>` e due
  `<x-text-input>`.

## Modifiche
- Entrambi i file riscritti come markup unico e corretto, mantenendo la struttura originale (action-section / modal /
  password input / error / pulsanti).

## Verifica
```bash
php -l <file>  # entrambi OK
```

## Decisioni
- **D-1**: i due file mantengono la loro duplice natura (uno per Livewire, uno per il partial Alpine) — non sono stati
  fusi perché servono contesti diversi.

## Aperto
- Nessuno.