---
title: "Frontend Stack Canonico — Tailwind + Alpine + Lit + DaisyUI + Flowbite + Filament"
type: rule
confidence: high
created: 2026-06-01
updated: 2026-06-01
tags: [frontend, tailwind, alpine, lit, daisyui, flowbite, filament, no-bootstrap, stack]
related:
  - rules/cms-block-naming-tailwind-flowbite.md
  - rules/00-TRIGGER_MAP.md
---

# Frontend Stack Canonico

## Mantra (SSoT)

```
Tailwind v4 + Alpine.js v3 + Lit v3 + DaisyUI v5 + Flowbite + Filament v5 + Vite
```

## Stack (NO Bootstrap)

| Layer | Tecnologia | Versione | Scopo |
|-------|-----------|----------|-------|
| CSS Framework | **Tailwind CSS** | v4 | Utility-first CSS |
| Componenti UI | **DaisyUI** | v5 | Componenti Tailwind-ready |
| Componenti UI extra | **Flowbite** | latest | Componenti aggiuntivi (dropdown, datepicker, ecc.) |
| Interattività | **Alpine.js** | v3 | Reattività leggera (toggle, modal, tabs, form) |
| Web Components | **Lit** | v3 | Mappa interattiva (Leaflet + marker) |
| Admin Panel | **Filament** | 5.x | Backoffice (Livewire + Tailwind) |
| Build | **Vite** | latest | Bundling, HMR, production |

## Regola

> **MAI** usare classi Bootstrap (`col-md-6`, `btn btn-primary`, `d-flex`, ecc.) nei file Blade del tema.
> Usare sempre equivalenti Tailwind/DaisyUI/Flowbite.

## Equivalenze Bootstrap → Tailwind/DaisyUI

| Bootstrap | Tailwind/DaisyUI |
|-----------|-----------------|
| `btn btn-primary` | `btn btn-primary` (DaisyUI) |
| `col-md-6` | `md:w-1/2` o `grid-cols-2` |
| `d-flex` | `flex` |
| `d-none` | `hidden` |
| `d-lg-block` | `lg:block` |
| `text-center` | `text-center` |
| `mt-3` | `mt-3` (Tailwind spacing) |
| `mb-40` | `mb-40` (Tailwind spacing) |
| `shadow-sm` | `shadow-sm` |
| `border` | `border` |
| `rounded` | `rounded` |

## Naming blocchi CMS

Le sottocartelle di `resources/views/components/blocks/` prendono i nomi da:
- https://tailwindcss.com/plus/ui-blocks
- https://flowbite.com/blocks/

Vedi regola completa: `rules/cms-block-naming-tailwind-flowbite.md`

## Story di riferimento

- STORY-112: `docs/stories/STORY-112-frontend-stack-canonical-rule.md`
- STORY-133: `docs/stories/STORY-133-frontend-stack-religion-tailwind-alpine-lit.md`
