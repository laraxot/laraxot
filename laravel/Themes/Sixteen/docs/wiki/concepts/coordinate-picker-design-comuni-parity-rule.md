---
qmd: "coordinate picker design comuni parity rule"
issues: []
discussions: []
title: CoordinatePicker Design Comuni Parity Rule
type: concept
tags: [sixteen, design-comuni, coordinate-picker, leaflet, css, wizard]
created: 2026-04-22
updated: 2026-10-14
sources:
  - ../../../../../Modules/Geo/docs/wiki/concepts/coordinate-picker-design-comuni-runtime-rule.md
  - ../../../../../Modules/Fixcity/docs/wiki/concepts/segnalazione-data-step-spacing-rule.md
status: draft
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

## Aggiornamento 2026-10-14: pagine `data-dc` e sola lettura (DCP-152)

- Nelle pagine con `.page-content[data-dc]` (passo 2 del wizard, dettaglio ticket) le regole del tema per `coordinate-picker-lit .ctrl-btn` sono spente dal plugin PostCSS `isolate-legacy-important` (`Themes/Sixteen/postcss.config.js`, cause e specificita' nella story, non rimisurate qui). L'opacita' dei pulsanti e' garantita dal modulo Geo, owner del componente: regola `.map-container .ctrl-btn.ctrl-btn` in `Modules/Geo/resources/js/components/map/styles.js`. Un `button { background: transparent }` generico delle pagine DC non deve piu' svuotarli.
- Prova a pixel: `Themes/Sixteen/tests/browser/wizard-step2-map-center.spec.mjs` (pulsanti opachi) e `Themes/Sixteen/tests/browser/ticket-detail-marker.spec.mjs` (marker del dettaglio).
- Uso in sola lettura (`readonly`, `marker-icon-url`, `marker-color`, `marker-label`) nel blocco `ticket-detail/design-comuni`: tabella degli attributi in [coordinate-picker](../coordinate-picker.md). Story: [DCP-152 (Geo)](../../../../../Modules/Geo/docs/stories/dcp-152-marker-dettaglio-ticket-e-pulsanti-mappa-su-pagine-dc.story.md).
- La regola sopra ("controlli visibili ... con fondo bianco") resta valida; il GPS su HTTP non e' una regola CSS: `requestGeolocation` non chiede la posizione se `window.isSecureContext` e' falso e resta sulla zona di partenza (`map/controls/geolocation.js`).

## Vedi anche

- [CSS di terzi sulle pagine data-dc](vendor-css-on-data-dc-pages-needs-guard-exemption.md): la stessa esenzione del guard PostCSS vale per l'editor Markdown dei commenti (CMT-022).
