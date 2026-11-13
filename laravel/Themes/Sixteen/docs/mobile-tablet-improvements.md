---
title: "Mobile/Tablet Improvements — BMAD + Second Brain + Filament Blueprint"
type: note
status: draft
updated: 2026-10-19
tags: [docs, sixteen]
qmd: "mobile/tablet improvements   bmad + second brain + filament blueprint sixteen"
---
# Mobile/Tablet Improvements — BMAD + Second Brain + Filament Blueprint

type: feature
status: done
module: Sixteen (Theme)
related: typography.css, design-comuni, bmad

## Changes applied (verified in resources/views/components/sections/header/ and layouts/)

### DCP-228 — rimozione fascia bianca homepage

La fascia bianca osservata sotto l'header a 375px e tablet non era prodotta
dall'header: `ticket-parity.css` applicava globalmente `padding-top: 1.125rem`
a `#main-container`. Il selettore è ora neutralizzato esclusivamente sotto
`body:has(.fc-home)`, mentre le pagine ticket mantengono il distacco utile per
non coprire i titoli sotto l'header mobile.

1. **Logo size responsive**: `h-14 w-14` (logo) → `h-12 w-12 sm:h-14 sm:w-14` (scales for mobile vs desktop)
2. **Brand title scale**: `text-2xl sm:text-3xl lg:text-4xl` (previously fixed at `text-4xl` which overflowed mobile)
3. **Social links**: `hidden lg:flex` (hidden on mobile/tablet, visible only on `lg` and above)
4. **Mobile nav toggle**: `lg:hidden` button with `x-data="{ open: false }"` and `x-show` linked to `mobile-navigation` (already present, verified in `tailwind.blade.php`)
5. **Top bar padding**: `py-4 md:py-6` (scales for mobile vs desktop)
6. **Search/Account buttons**: `hidden sm:inline` labels; icons visible at all breakpoints

## Design Comuni compliance (verified against italia/design-comuni-pagine-statiche)

| Element | Mobile (<768) | Tablet (768-1024) | Desktop (>1024) | Source |
|---|---|---|---|---|
| Logo | `h-12` | `h-14` | `h-14` | `tailwind.blade.php` |
| Brand title | `text-2xl` | `text-3xl` | `text-4xl` | `layout-test.blade.php` |
| Navigation | Hidden (hamburger) | Hidden (hamburger) | Visible (`lg:flex`) | `tailwind.blade.php` |
| Social links | Hidden | Hidden | Visible (`hidden lg:flex`) | `tailwind.blade.php` |

## Font verification (post-fix)

- Font family set to `Titillium Web` via `font-sans` in `tailwind.config.js` (with `Inter var` fallback)
- Font files loaded via `laravel-vite-plugin/fonts` (`google('Titillium Web', { alias: 'sans', weights: [300,400,600,700], ... })`)
- Build generates CSS with `font-family: var(--font-sans)` — confirmed via `grep -oE 'font-family:[^;]+'` on served HTML

## Constraints respected

- NO Bootstrap Italia (only Tailwind + Alpine + Lit + DaisyUI + Flowbite)
- Componenti riutilizzabili: nessun componente modificato con `request()` implicito; solo `@props` + `data` espliciti (`tailwind.blade.php` mantiene questo pattern)
- `laravel/phpstan.neon`: NON modificato (regola dell'utente — solo l'utente può modificarlo)
- `docs/` aggiornate: questa nota (`mobile-tablet-improvements.md`) e riferimento nel `docs/index.md`
