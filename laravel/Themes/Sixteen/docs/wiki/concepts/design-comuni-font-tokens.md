---
title: "design comuni font tokens"
type: concept
tags: [design-comuni, fonts, tokens, sixteen, bootstrap-italia, vite]
created: 2026-05-04
updated: 2026-10-15
qmd: "design comuni font tokens titillium lora roboto mono vite self-hosted"
status: active
related:
  - ../concepts/homepage-guest-visual-fix.md
  - ../../bmad/stories/dcp-183-font-come-asset-di-vite.story.md
  - ../../bmad/stories/dcp-221-homepage-tipografia-leggibilita-wcag.story.md
  - ../../accessibility.md
---

# Font tokens Design Comuni / Bootstrap Italia (Sixteen)

## Scopo

Allineare il FO alle [regole tipografiche Bootstrap Italia](https://italia.github.io/bootstrap-italia/docs/organizzare-i-contenuti/tipografia/):

| Famiglia | Ruolo | Classe BI |
|---|---|---|
| **Titillium Web** | sans UI / body / nav | `.font-sans-serif` |
| **Lora** | serif leggibile su display / testi lunghi | `.font-serif` |
| **Roboto Mono** | numeri, codici | `.font-monospace` |

## Incorporamento (Vite, no CDN)

Metodologia Laravel [Working with Fonts](https://laravel.com/docs/vite#working-with-fonts) nella variante **Local Fonts / Vite nativo** (DCP-183):

1. File OFL in `resources/fonts/{titillium-web,lora,roboto-mono}/*.woff2`
2. `@font-face` in `resources/css/design-comuni/fonts.css` con `url()` **relativi** (`../../fonts/...`)
3. Import da `resources/css/app.css` → Vite hash + voci in `manifest.json`
4. Guardia: `tests/Unit/FontsCssGuardTest.php`

Niente `fonts.googleapis.com` (privacy / DCP-137).

## Token Tailwind (`@theme` in `app.css`)

```css
--font-sans: "Titillium Web", system-ui, sans-serif;
--font-serif: "Lora", Georgia, serif;
--font-mono: "Roboto Mono", ui-monospace, monospace;
```

## Hero home `/it`

L'H1 «Segnalazioni e servizi per la tua comunità» usa **Lora Regular 400**
con `font-family` / `font-weight` in `!important` (batte
`body * { Titillium !important }` dello shell Filament FO in
`08-layout-type-and-header.css`). Self-hosted: solo 400/700; niente 600
sintetizzato. Scale BI h1 `clamp(2.5rem, 4vw, 3rem)`, `line-height: 1.2`.

Incorporamento: Vite nativo su `resources/fonts` + `fonts.css` relative url
([Working with fonts](https://laravel.com/docs/vite#working-with-fonts) —
Local Fonts; DCP-183). `laravel-vite-plugin/fonts` richiede Vite 8: non usato.

Vedi [dcp-221](../../bmad/stories/dcp-221-homepage-tipografia-leggibilita-wcag.story.md),
[homepage-guest-visual-fix](homepage-guest-visual-fix.md).
