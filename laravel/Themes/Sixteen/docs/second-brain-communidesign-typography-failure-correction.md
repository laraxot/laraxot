---
title: "second brain communidesign typography failure correction"
type: note
status: draft
updated: 2026-10-19
tags: [docs, sixteen]
qmd: "second brain communidesign typography failure correction sixteen"
---
## Second Brain entry: comunidesign-typography-failure-correction.md

**Created**: 2026-10-19  
**Title**: Typography Remediation Failure — Sixteen Theme  
**Status**: corrected and verified  
**Tags**: [bmad, typography, design-comuni, font-loading, vite-config, second-brain]  
**QMD**: "sixteen typography comunidesign failure"  
**Related**:  
- ./filament-blueprint.md  
- ./fonts.css  
- ./design-comuni/typography.css  
- ./tailwind.config.js  
- https://italia.github.io/bootstrap-italia/docs/organizzare-i-contenuti/tipografia/  

### What went wrong?

The investigation started from the rendered CSS and manually self-hosted
`@font-face` files instead of first inspecting the Laravel Vite integration and
the official Laravel “Working with fonts” workflow. That made the diagnosis
too narrow: it fixed delivery symptoms but missed the project-level font
source of truth.

1. **Canonical integration**: `vite.config.js` uses
   `laravel-vite-plugin/fonts` and `google()` for Titillium Web, Lora, and
   Roboto Mono, with explicit aliases, weights, styles, subsets, preload,
   display, and fallbacks.
2. Vite emits the font manifest and hashed assets; the `/themes/Sixteen/` base
   path and both CSS entrypoints must be checked together.
3. The build and browser checks are release gates, not optional cleanup.
4. The black logo was a separate visual-regression issue and needed computed
   rendering verification, not a font change.

**Root cause**:  
- **Architectural oversight**: Non ho analizzato l'integrazione Vite ufficiale prima di scegliere il percorso font.  
- **Processo BMAD violato**: Ho saltato la ricerca delle integrazioni installate e il confronto con il manifest generato prima di correggere il CSS.  
- **Conseguenza**: Font non caricati correttamente, logo sbagliato, parità visiva < 90%.

**Correzione**:  
- Leggere `vite.config.js` e la documentazione ufficiale del plugin prima di modificare font
- Usare `graphify query` prima dell'esplorazione quando il grafo è disponibile
- Verificare manifest, network, computed style e screenshot a 375/768/1440
- Ricompilare dopo ogni modifica (`npm run build`)
- Usare Puppeteer come fallback documentato se Playwright MCP non è avviabile
