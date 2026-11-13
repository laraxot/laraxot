---
title: "BMAD — Riordino docs Sixteen (auth + categoria + second brain)"
type: plan
status: completed
updated: 2026-10-27
tags: [docs, sixteen, bmad, second-brain, ponytail]
qmd: "bmad docs cleanup sixteen auth components reorg lista-categorie"
related:
  - ../auth-components-reorg-plan.md
  - ../auth-login-architettura.md
decisions:
  - D-DOC-01: auth component files spostati da `components/pages/auth-*.blade.php` → `components/pages/auth/*.blade.php` (no prefisso ridondante, risolto merge conflict).
  - D-DOC-02: "`lista-categorie` è pagina CMS dinamica (`lista-categorie.index`) via `[container0]/index.blade.php`, NON una cartella dedicata; componente `categories.blade.php`; filosofia DRY per pagine list."
  - D-DOC-03: "Ponytail applicato: un solo piano BMAD, un solo aggiornamento index, nessun nuovo foglio CSS, nessun controller."
constraints:
  - Docs BMAD solo in `Themes/*/docs/` (mai `bmad-output/`)
  - Second Brain: riferimento a `docs/bmad/auth-login-architettura.md`, `folio-pages-architecture.md`
  - Ponytail: solo file necessari, nessun duplicato, nessun refactoring delle pagine esistenti.
---

# Riordino docs — cosa è stato fatto

## 1. `lista-categorie` — a cosa serve
- URL `/it/lista-categorie` → `container0.index` con `$container0 = 'lista-categorie'`.
- JSON: `lista-categorie.index.json` → `slug`, `layout`, blocco `categories-page` con componente `pub_theme::components.pages.categories`.
- Componente: `categories.blade.php` mostra categorie servizi (report, altri) con breadcrumb e griglia; è il vestito della pagina di esplorazione.
- Non ho creato io questa pagina: esiste come CMS-driven via `[container0]/`. La regola FOLIO_PAGES_ARCHITECTURE (`docs/folio-pages-architecture.md`) vieta cartelle `categories/` dedicate.

## 2. Auth component reorg (completato)
- `components/pages/auth-login.blade.php` (merge conflict + `<style>` privato) → ripulito, nuovo componente in `components/pages/auth/login.blade.php`, alias nel vecchio file.
- `register.blade.php`, `password-reset.blade.php`, `password-token.blade.php` → ricreati in `auth/`, alias mantenuti per CMS JSON.
- Test `AuthLoginStyleContractTest.php`: verificato (le classi `.auth-login-*` restano in CSS, il componente usa `data-dc`, nessun `body-page=`).

## 3. Docs cleanup (ponytail — minimo)
- Piano BMAD: `docs/bmad/auth-components-reorg-plan.md` (già creato)
- Aggiornamento indice operativo `00-INDEX.md`: riferimento al piano + stato `auth-components-reorg-plan.md`
- Second Brain link: `auth-login-architettura.md` (architettura Folio+Volt), `folio-pages-architecture.md` (DRY [container0])
- Nessun file duplicato creato, nessun foglio CSS nuovo, nessun controller.

## 4. Verifica
- `php -l` sui nuovi blade: OK
- Nessun merge conflict nei nuovi file
- Alias `auth-login.blade.php` punta a `auth/login.blade.php`; CMS JSON (`auth.login`) non rotto
- Pagina `lista-categorie`: funziona via JSON + componente esistente

## 5. Commit
- Tutti i file nuovi/aggiornati in `laravel/Themes/Sixteen/` (docs, components, pages/password/reset/[token].blade.php include aggiornato, modulo User reset updated)
