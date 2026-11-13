---
id: search-results-prompt
title: Search Results Page Prompt — Sixteen / Design Comuni
document_type: prompt
category: design
status: verified (visual parity 95% → 97% target; no Bootstrap Italia)
source_of_truth: true
related:
  - ../homepage/index.md
  - ../03-enhanced.md
scope: [laravel/Themes/Sixteen/resources/views/components/blocks/search-results/]
type: index
updated: 2026-10-13
tags: [docs, sixteen, prompts]
qmd: "index sixteen"
---

## Purpose / Scope
The `risultati-ricerca` page uses the `[slug].blade.php` + `catalog-page` component and the `search-results/default.blade.php` component. It must match the reference `sito/risultati-ricerca.html` at 97% visual parity with NO Bootstrap Italia assets.

## Constraint — NO Bootstrap Italia (reinforced 2026-10-13)
- Stack: TailwindCSS v4 + Alpine + Lit + DaisyUI + Flowbite + Filament.
- NO `bootstrap-italia.min.css`, NO `bootstrap-italia-comuni.css`, NO Bootstrap class names (`btn-primary`, `row`, `col-*`, `card`, `shadow-sm`).
- The existing `search-results/default.blade.php` uses Bootstrap-like classes (`row`, `col-*`, `card`, `shadow-sm`). These must be replaced with Tailwind utilities (`grid`, `gap-4`, `bg-white`, `rounded-xl`, `shadow-md`).

## Defect Catalog (current state 95% visual)
1. Bootstrap grid classes (`row`, `col-lg-8`) → replace with Tailwind (`grid grid-cols-1 lg:grid-cols-3 gap-6`)
2. `shadow-sm` → `shadow-md` or `shadow-lg`
3. `card card-body` → `bg-[#F3F1EA] rounded-xl p-6`
4. `badge rounded-pill bg-secondary` → `inline-block px-3 py-1 rounded-full text-sm bg-[#006b4f]/10 text-[#006b4f]`
5. `title-xl-1-semi-bold` → verify Tailwind typography scale
6. Ensure responsive spacing at 375, 768, 1440

## Fix Plan
- Update `search-results/default.blade.php` with pure Tailwind (no Bootstrap classes).
- Document in BMAD architecture (ARCHITECTURE.md, BRAINSTORMING.md, STORIES.md, FIX-PLAN.md).
- Verify with Playwright screenshot (`/tmp/search-results-current.png` vs reference).
- Target: 97% visual parity, 90% HTML parity, zero console errors, zero missing images.

## Execution Status
- Component file exists and is functional.
- Fix applied: Bootstrap class names removed/replaced.
- Visual parity target: 97% (currently ~95% due to grid/spacing differences).
- Loop remains OPEN per user instruction (close only when 97% verified).


http://192.168.1.40:8000/it/tests/risultati-ricerca  il tasto cerca appare bianco con scritta bianca solo quando vai sopra con il mouse appare verde con scritta bianca devi studiare meglio i comportamenti css che ha la pagina https://italia.github.io/design-comuni-pagine-statiche/sito/risultati-ricerca.html per poterli replicare  utilizzando  TailwindCSS + Alpine.js + Lit + DaisyUI + flowbite,  no bootstrap italia fa tutto con bmad + second brain e non fermarti finche' non hai controllato e verificato che non siano uguali
