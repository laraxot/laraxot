---
title: "WCAG and Accessibility Audit — Homepage FixCity"
type: audit
status: completed
updated: 2026-10-23
tags: [accessibility, wcag, homepage, fixcity, sixteen, axe, bmad]
qmd: "axe wcag homepage audit sixteen guest fo measured"
related:
  - ./bmad/stories/dcp-143-accessibilita-difetti-aria-e-audit-manuale-wcag-21-aa.story.md
  - ./bmad/homepage-guest-visual-contract.md
  - ./bmad/filament-blueprint.md
  - ../../../../docs/second-brain/process/civic-homepage-marketing.md
  - ../../../Modules/Fixcity/docs/homepage-content-contract.md
---

# WCAG 2.1 AA Audit — Homepage (misurato)

**Quando:** 2026-10-23  
**URL:** `http://192.168.1.40:8000/it` (+ `/en` e altre guest FO)  
**Tool:** `@axe-core/playwright` 4.x via `tests/browser/a11y-axe.spec.mjs`  
**Tag:** `wcag2a`, `wcag2aa`, `wcag21a`, `wcag21aa`  
**Esclusi dal contratto FO:** `.phpdebugbar` / tooling locale (false positive
`button-name` + `color-contrast` su UI debug Laravel — non codice prodotto).

## Esito axe (blocking serious/critical = 0)

| Pagina | 375px | 1440px |
|--------|-------|--------|
| `/it` | 0 | 0 |
| `/en` | 0 | 0 |
| `/it/auth/login` | 0 | 0 |
| `/it/tickets` | 0 | 0 |
| `/it/tickets/track` | 0 | 0 |
| `/it/domande-frequenti` | 0 | 0 |

Dump homepage multi-viewport (375/768/1440) senza tooling: **0 violazioni**
totali → `/tmp/fc-a11y/axe-home-it.json` (artefatto locale, non commit).

Prima del fix exclude tooling: il test falliva con `critical button-name` e
`serious color-contrast` su bottoni debugbar (`copyToClipboard`, label
«LARAVEL»). Remediation: dismiss cookie + exclude debugbar in
`a11y-axe.spec.mjs`.

## Confronto screenshot pixel (local vs fixcity.eu)

Tool: Puppeteer + `pixelmatch` (threshold 0.1), first viewport, debugbar nascosta.
Artefatti: `/tmp/fc-pixel/{local,eu,diff}-{375,768,1440}.png` + `report.json`.

| Viewport | Match % | Diff % | Note |
|----------|---------|--------|------|
| 1440 | 95.537 | 4.463 | hero locale verde+mappa vs .eu charcoal (scelta desiderabilità pages_old→avanti) |
| 768 | 88.445 | 11.555 | idem + layout stack |
| 375 | 89.049 | 10.951 | idem |

Non è un obiettivo di parity pixel 100% con `.eu` (produzione ancora charcoal):
è una baseline misurata del delta intenzionale.

## Vite JS chunks (misurato)

Entry homepage attuale (`manifest` → `resources/js/app.js`):
`assets/app-CF5BdshJ.js` (~15 KiB) + chunk condivisi
(`theme-map` ~309 KiB, `vendor-ui` ~125 KiB, `theme-behavior` ~42 KiB,
`vendor-alpine` ~25 KiB).

Chunk grandi **fuori first-paint** o orphan:

| Chunk | Size | Ruolo |
|-------|------|--------|
| `vendor-image-conversion-*.js` | ~1.29 MiB | lazy HEIC (`dynamicImport`) — ok |
| `vendor-core-*.js` | ~1.33 MiB | residuo split; non nell’import chain HTML home attuale |
| `app-6gc02iPv.js` / `app-DWB2TmZD.js` ecc. | ~1.8 MiB | **orphan** build precedenti (`emptyOutDir: false`) |

`chunkSizeWarningLimit: 1400` in `vite.config.js` è intenzionale per HEIC.
Il warning Vite “Some chunks are larger than 500 kB” resta informativo sul
lazy HEIC / vendor-core; non blocca la home.

Azione consigliata (forward): GC periodico degli `app-*.js` orphan in
`Themes/Sixteen/public/assets/` non referenziati dal `manifest.json`.

## Non sostituito da questo audit

- Prova manuale tastiera / screen reader (story DCP-143)
- Lighthouse CI score (opzionale)
- Account pages / wizard (richiedono `FIXCITY_STORAGE_STATE`)

## Come ripetere

```bash
cd laravel/Themes/Sixteen
FIXCITY_BASE_URL=http://192.168.1.40:8000 \
  npx playwright test tests/browser/a11y-axe.spec.mjs --grep 'axe /it' --reporter=list
```
