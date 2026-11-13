---
title: "[STORY] Head SEO centralizzato per il tema Sixteen"
type: story
module: Sixteen
status: draft
priority: high
created: 2026-11-12
updated: 2026-11-12
tags: [bmad, seo, head, sixteen, laravel-head, docs-first]
qmd: "head SEO sixteen tema component CMS laravel/head documentazione BMAD pianificazione"
related:
  - "../../../../Modules/Cms/docs/stories/seo-head-component.story.md"
  - "../../../../Modules/Cms/docs/seo-head-architecture.md"
  - "../../../../Modules/Cms/docs/wiki/concepts/headernavblock-filament-builder.md"
issues: []
discussions: []
---

# [STORY] Head SEO centralizzato per il tema Sixteen

## Perché

Il tema Sixteen ha attualmente un `<head>` custom e duplicato in più punti:

- `resources/views/pages/index.blade.php` (home) non contiene `<head>` esplicito ma delega a `@extends('layouts.app')`
- `resources/views/livewire/auth/logout.blade.php` ha `<head>` esplicito
- `resources/views/mobile/layout.blade.php` ha `<head>` esplicito
- `resources/views/offline.blade.php` ha `<head>` esplicito

Il modulo Cms possiede già un componente Head completo (`Modules/Cms/app/View/Components/Head.php`) che usa `laravel/head` ed è documentato in `seo-head-architecture.md`. Il componente è già implementato e testato per il tema Two. Questa storia copre l'estensione al tema Sixteen: eliminare i `<head>` duplicati e centralizzare la gestione SEO attraverso il componente Cms.

## Decisione di ownership

- **Cms** possiede il componente visuale/layout `<x-cms::head>` e la sua integrazione con Vite, Livewire, Filament, Alpine e gli stack Blade. La logica SEO (title, description, robots, canonical, hreflang, Schema.org) risiede in Cms.
- **Sixteen** consuma il componente Cms, fornendo solo eventuali override semantici tramite props o stack.
- `laravel/head` è già una dipendenza del modulo Cms (documentato in `seo-head-architecture.md`).

## Acceptance criteria

- [ ] Un solo `<head>` per ciascun layout pubblico di Sixteen che adotta il componente.
- [ ] Charset, viewport, title, description, robots opzionale, theme-color, OG, Twitter, canonical/alternate e asset SEO coerenti con la locale corrente.
- [ ] JSON-LD `Organization` + `WebSite` sulla homepage; sulle pagine interne vengono usati entità pagina + `BreadcrumbList` risolti dal Cms.
- [ ] Dark-mode anti-FOUC e `[x-cloak]` restano prima del primo paint.
- [ ] Nessuna stringa utente hardcoded nelle Blade.
- [ ] PHPStan e test mirati superano i gate del repo.
- [ ] Browser check del tema Sixteen a desktop e 375/768/1440 px senza errori console, head duplicato o contenuto hero nascosto.

## Piano di verifica

1. Testare il render con title/description/robots e homepage/non-homepage.
2. Verificare presenza/assenza dei tag condizionali e unicità dei meta principali.
3. Usare Playwright/Puppeteer se l'app è avviabile: desktop, mobile, dark mode, reduced motion, console e screenshot.
4. Eseguire PHPStan globale e Pest del modulo Cms/Seo.

## Documentazione correlata

- `seo-head-architecture.md` — architettura del componente Head
- `wiki/concepts/headernavblock-filament-builder.md` — header CMS builder
- Storie correlate in `Modules/Cms/docs/stories/`

## Note

Questa storia è in modalità **docs-first** come da istruzioni utente: prima documentare tutto, poi implementare. La documentazione BMAD deve essere creata prima di qualsiasi modifica al codice.