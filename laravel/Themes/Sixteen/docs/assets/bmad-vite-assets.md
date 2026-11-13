---
title: "BMAD: Vite Assets + Theme Build"
type: note
tags: [design-comuni, vite, assets, bmadd, second-brain]
created: 2026-10-18
updated: 2026-10-18
status: draft
qmd: "vite + theme assets   design comuni stack sixteen"
---

# Vite + Theme Assets — Design Comuni Stack

## BMAD + Second Brain + Filament Blueprint

- **B (Benchmark)**: Theme uses `vite.config.js` with entry points (`main`, `login`)
- **M (Model)**: Theme manifest at `public/themes/sixteen/dist/manifest.json`
- **A (Audit)**: Login page uses `@vite(['...'], 'themes/Sixteen')` — no Bootstrap Italia, pure Tailwind
- **D (Deliver)**: Assets loaded correctly via theme build; no `foli.layouts.default` errors
