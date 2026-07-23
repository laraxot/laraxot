# geo-map-popup — confine tema sixteen / leaflet

## scopo

Il tema Sixteen non ridefinisce la struttura interna del popup Geo; limita conflitti con preflight Design Comuni e CSS cluster.

## responsabilità

| layer | cosa fa |
|-------|---------|
| **modulo geo** | Html popup (block `popup*`), `popupTicketStylesText` in `map-lit` |
| **tema sixteen** | Override popup header + marker glifo in `07-map-clusters-and-leaflet.css`; cluster; no transform su `.leaflet-marker-icon` |

## classi leaflet

- `leaflet-popup.popup-wrapper` — `L.popup({ className: 'popup-wrapper' })`
- **Deprecato:** `geo-popup-segnalazione-wrapper`, `geo-popup-wrapper`

## block bem interno (modulo)

- `popup`, `popup--loading`, `popup__footer` — [geo-map-popup-bem.md](../../../../Modules/Geo/docs/wiki/concepts/geo-map-popup-bem.md)

## marker (tema)

In `07-map-clusters-and-leaflet.css`:

- Cluster glifi: 14px (`.geo-cluster-type-icons`)
- Marker singolo: glifo **22px** su `__glyph-pad`; corpo colorato in JS

Confine pin: [geo-map-marker-civic-pin-theme-boundary.md](./geo-map-marker-civic-pin-theme-boundary.md)

## conflitto css — header popup (critico)

Su `/it` il body ha `.dc-homepage-parity`. Regole legacy tipo:

```css
.dc-homepage-parity header { min-height: 222px !important; }
```

colpiscono qualsiasi tag `<header>` nel DOM — **incluso** un vecchio `<header class="popup__header">`.

**Fix canonico:**

1. Modulo: `<div class="popup__header">`
2. Tema: `.dc-homepage-parity .leaflet-popup.popup-wrapper .popup__header { min-height: 0 !important }`
3. `13-final-runtime-overrides.css` — stesso override in coda cascade
4. Parity sito: `header` ristretto a `header.it-header-wrapper` dove possibile

Runbook: [map-popup-header-whitespace-fix.md](../../../../Modules/Geo/docs/wiki/troubleshooting/map-popup-header-whitespace-fix.md) · [global-header-css-leak-leaflet-popup.md](../troubleshooting/global-header-css-leak-leaflet-popup.md)

## ricostruzione

- [geo-map-lit-reconstruction-guide.md](../../../../Modules/Geo/docs/wiki/concepts/geo-map-lit-reconstruction-guide.md)
- [geo-map-fixes-registry.md](../../../../Modules/Geo/docs/wiki/concepts/geo-map-fixes-registry.md)
- [map-lit-reconstruction-hub.md](../../../../../docs/wiki/memories/map-lit-reconstruction-hub.md)

## collegamenti

- [marker-cluster-hover-stability.md](./marker-cluster-hover-stability.md)
- [leaflet-no-transform-on-marker-icon.md](./leaflet-no-transform-on-marker-icon.md)
- [map-lit-vite-build-troubleshooting.md](./map-lit-vite-build-troubleshooting.md)
