---
title: "No parallel DesignComuni layout"
type: rule
status: active
created: 2026-10-11
updated: 2026-10-11
tags: [sixteen, design-comuni, layout, frontoffice, bmad]
qmd: "sixteen design comuni no parallel layout x-layouts app"
issues: []
discussions: []
related:
  - ../concepts/sixteen-design-comuni-theme-identity.md
  - ../../bmad/design-comuni-theme-identity-decision.md
  - ./frontend-stack-canonical.md
---

# No parallel DesignComuni layout

## Regola

Sixteen è già il tema DesignComuni del front office. Non creare e non usare un
layout separato chiamato `design-comuni`.

## Vietato

- `resources/views/layouts/design-comuni.blade.php`
- `resources/views/components/layouts/design-comuni.blade.php`
- `<x-layouts.design-comuni>`
- prompt o story che chiedono di switchare pagine verso un layout DesignComuni parallelo

## Contratto canonico

```text
Folio/CMS/page Blade
        ↓
<x-layouts.app> oppure resources/views/layouts/app.blade.php
        ↓
components/layouts/main.blade.php
        ↓
sections/header + sections/footer + blocchi Sixteen
```

## Perché

DesignComuni non è un tema alternativo dentro Sixteen: è l'identità stessa del
tema Sixteen. Un layout `design-comuni` parallelo crea due shell pubbliche, duplica
markup e asset, rende ambigua la parity visuale e fa divergere header, footer,
focus, Vite e accessibilità.

## Come lavorare

- Migliorare `x-layouts.app`, `layouts/app.blade.php`, `components/layouts/main.blade.php`, sezioni, blocchi e CSS del tema.
- Considerare le vecchie citazioni di `design-comuni.blade.php` come storico di migrazione o anti-pattern, non come istruzioni runtime.
- Scrivere decisioni e contratti visuali in `Themes/Sixteen/docs/`, non nel root del repository.
- Mantenere lo stack runtime: TailwindCSS + Alpine.js + Lit + DaisyUI + Flowbite + Filament, senza Bootstrap Italia runtime.
