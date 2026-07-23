---
title: "AI harness — disciplina agenti tema Sixteen"
type: concept
module: Sixteen
tags: [sixteen, theme, ai, harness, folio, wcag, frontoffice]
created: 2026-06-05
updated: 2026-06-05
qmd: "sixteen theme ai harness folio volt wcag design comuni frontoffice blade"
issues:
  - "https://github.com/laraxot/theme_sixteen_fila5/issues/54"
discussions:
  - "https://github.com/laraxot/theme_sixteen_fila5/discussions/55"
related:
  - ../../folio-page-pattern.md
  - ../../r2-ux-register-form-stacked-password.md
  - ../../../../docs/wiki/concepts/hackernoon-ai-coding-tips-fixcity-map.md
  - ../../../../docs/wiki/bmad/architecture.md
---

# AI harness — Sixteen

Estensione locale della [mappa HackerNoon root](../../../../docs/wiki/concepts/hackernoon-ai-coding-tips-fixcity-map.md).

## Scope tema

- Folio + Volt + Blade (no Controller FO)
- Design Comuni class names, Tailwind `@apply`, WCAG
- Boundary tema vs modulo Geo/Fixcity

## Tip applicati qui

| Tip | Sixteen |
|-----|---------|
| 003 | Folio `[container0]/[slug0]`: `mount($container0)` — mai `request()->route()` in `@php` (STORY-141 solo anti-`@volt` dinamico) |
| 006 | Review UX auth (password stacked, contrasto) — issue tema #58 |
| 009 | No dump intero `resources/views/` — qmd + file mirati |
| 015 | Filament-first, no inline JS (`docs/rules/NO-INLINE-JS.md`) |
| 020 | Wiki tema `docs/wiki/concepts/` |

## Regole critiche

- `@volt('...')` **statico** a compile-time — mai concatenazione con variabili route
- Mappe/marker: owner Module Geo; composizione CMS: owner Sixteen

## Collegamenti

- [folio-page-pattern.md](../../folio-page-pattern.md)
- [filament-first-frontoffice.md](./filament-first-frontoffice.md)
- [no-controllers-folio-volt-filament.md](./no-controllers-folio-volt-filament.md)
