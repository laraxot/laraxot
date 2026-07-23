---
title: CoordinatePicker Design Comuni Parity Rule
type: concept
tags: [sixteen, design-comuni, coordinate-picker, leaflet, css, wizard]
created: 2026-04-22
updated: 2026-04-22
sources:
  - ../../../../../Modules/Geo/docs/wiki/concepts/coordinate-picker-design-comuni-runtime-rule.md
  - ../../../../../Modules/Fixcity/docs/wiki/concepts/segnalazione-data-step-spacing-rule.md
---

# CoordinatePicker Design Comuni Parity Rule

## Regola Permanente

Nel tema Sixteen, il wizard segnalazione deve rendere `coordinate-picker-lit` come parte del form pubblico Design Comuni:

- mappa non trasparente;
- tile, marker e pane Leaflet con `opacity: 1`;
- controlli visibili sopra la mappa, con fondo bianco, bordo e contrasto adeguato;
- fullscreen e zoom non devono dipendere da controlli Leaflet nascosti o dal CDN.

## Owner CSS

La parity visiva appartiene a:

```text
laravel/Themes/Sixteen/resources/css/segnalazione-parity.css
```

Dopo ogni modifica CSS tema:

```bash
npm run build
npm run copy
```

da `laravel/Themes/Sixteen`.

## Spaziatura Step Dati

La distanza tra heading `Disservizio` e primo campo `Tipo di disservizio` deve essere governata dal CSS tema, non da padding inline nella Blade o nel widget PHP.
