---
type: investigation
title: Header and Footer Element Inventory and Duplication Analysis
links:
  github_issue: "#55"
status: draft
updated: 2026-10-11
tags: [docs, sixteen, investigation]
qmd: "investigation: header & footer   elements, duplication, data flow sixteen"
---

# Investigation: Header & Footer — Elements, Duplication, Data Flow

## Header Elements (Design-Comuni Reference)
| Element | Source | Status |
|---|---|---|
| Logo (SVG) | `design-comuni/assets/images/logo-comune.svg` | ✅ |
| Site name (h1) | `Il mio Comune` | ✅ |
| Tagline (subtitle) | `Un comune da vivere` | ✅ |
| Primary nav | Amministrazione, Novità, Servizi, Vivere il Comune, Iscrizioni, Estate in città, Polizia locale | ✅ |
| Secondary nav | Elenco segnalazioni (→ `tickets.index`) | ✅ |
| Language switcher | IT/DE/EN/ES dropdown (Alpine) | ✅ |
| Personal area | Login button / user dropdown (authenticated) | ✅ |
| Search trigger | Modal (Alpine + `x-data`) | ✅ |
| Social icons | Optional (config `comune.social`) | ✅ |
| Mobile overlay | Alpine `mobileOpen` toggle | ✅ |

## Footer Elements (Design-Comuni Reference)
| Element | Source | Status |
|---|---|---|
| EU funding badge | `design-comuni/assets/images/eu-badge.svg` | ✅ |
| 4-column links | Comune, Servizi, Amministrazione, Contatti | ✅ |
| Contacts | Email, phone, address, PEC | ✅ |
| Bottom bar | Privacy, Note legali, Accessibilità | ✅ |
| Slim variant | Bottom bar only | ✅ |

## Duplication Map
| File | Type | Issue |
|---|---|---|
| `header-comune.blade.php` | Static | Hardcoded; duplicates `pub_theme::sections.header.v1` |
| `footer-comune.blade.php` | Static | Hardcoded; duplicates `sections/footer-full.blade.php` |
| `components/sections/header.blade.php` | CMS | Includes `pub_theme::components.sections.header.tailwind` (ridondante) |
| `sections/footer-full.blade.php` | CMS | Uses `$data` from CMS |
| `bootstrap-italia/header-slim.blade.php` | Legacy | Bootstrap-based, no longer used |

## Data Flow
- **Static header**: reads `auth()->user()`, `config('comune')`, `config('comune.social')`
- **CMS sections**: reads `data` array from JSON page content (`layout`, `navigation`, `tabs`, `sections`)
- **Layout JSON**: `"layout": "test"` → `<x-layouts.test>` (no `<x-dynamic-component>`)

## Stack
- **CSS**: Tailwind + DaisyUI + Flowbite (NO Bootstrap)
- **JS**: Alpine.js (mobile menu, language dropdown, search modal)
- **Icons**: Filament (`<x-filament::icon>`), Bootstrap Italia SVG sprites
- **Build**: Vite + Lit web components

## BMAD Outline
1. Investigation → `docs/investigation/header-footer-consolidation.md` ✅
2. Story → `docs/bmad/stories/story-header-footer-consolidation.md` ✅
3. Decision log → `docs/decision-log.md` (entry: consolidate header/footer)
4. Second Brain → `sixteen-header-footer-consolidation`
5. QMD queries → `docs/qmd-index/QUERIES.md`

## QMD Queries
```bash
qmd search "header" --limit 5
qmd search "footer" --limit 5
qmd search "duplication" --limit 5
qmd search "layout json" --limit 5
```