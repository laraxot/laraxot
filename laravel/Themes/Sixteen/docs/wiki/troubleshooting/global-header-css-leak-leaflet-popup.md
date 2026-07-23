---
title: "Leak CSS header sito su popup Leaflet"
type: troubleshooting
confidence: high
created: 2026-06-03
updated: 2026-06-03
tags: [css, leaflet, popup, header, design-comuni, parity]
related:
  - ../concepts/geo-map-popup-leaflet-boundary.md
  - ../../../../Modules/Geo/docs/wiki/concepts/geo-map-popup-bem.md
  - ../../../../Modules/Geo/docs/wiki/concepts/geo-map-lit-reconstruction-guide.md
  - ../../../../Modules/Geo/docs/wiki/troubleshooting/map-popup-header-whitespace-fix.md
  - ../concepts/map-lit-vite-build-troubleshooting.md
---

# Leak CSS `header` sito → popup mappa

## Sintomo

Nel tooltip/popup marker, **enorme fascia bianca** tra titolo segnalazione e riga «Tipologia» (area evidenziata in review UX).

## Causa radice

`Themes/Sixteen/resources/css/design-comuni-visual-fix.css` (e in passato regole parity su `header` generico) applicava:

```css
header {
  min-height: 222px !important;
}
```

Il popup usava il tag semantico `<header class="popup__header">` → ereditava l’altezza del masthead istituzionale.

## Correzioni (2026-06)

1. **Markup Geo:** `<div class="popup__header">` — classi BEM invariate (`popup__header`).
2. **Tema:** selettore ristretto a `header.it-header-wrapper` (e `.it-header`).
3. **Override difensivo:**

```css
.leaflet-popup.popup-wrapper .popup__header,
.dc-homepage-parity .leaflet-popup.popup-wrapper .popup__header {
  min-height: 0 !important;
  height: auto !important;
}
```

4. **Geo JS:** `.popup__header { min-height: 0; }` in `popupTicketStylesText`.

## Prevenzione

- Vietato `header { … }` senza scope nel tema FO.
- Nuovi componenti overlay mappa: preferire `<div role="dialog">` o `<article>` + classi BEM, non tag `header`/`footer` globali.
- Verifica su `/it` con body `.dc-homepage-parity` dopo ogni modifica CSS header.

## Collegamenti

- [geo-map-popup-leaflet-boundary.md](../concepts/geo-map-popup-leaflet-boundary.md)
- [map-lit-it-incidents-2026-06.md](../../../../Modules/Geo/docs/wiki/troubleshooting/map-lit-it-incidents-2026-06.md) — incidente popup spacing
