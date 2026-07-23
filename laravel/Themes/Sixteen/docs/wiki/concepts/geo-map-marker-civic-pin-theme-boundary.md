# marker mappa — confine tema (civic pin)

## scopo

Il tema Sixteen **non** ridefinisce la struttura HTML del marker (owned by Geo `marker-config.js`). Applica override cascade su cluster, contrasto glifo e conflitti con CSS parity Design Comuni.

---

## responsabilità

| layer | file | cosa fa |
|-------|------|---------|
| Geo JS | `marker-config.js` | DOM pin, `markerCardStylesText`, `createGeoMapLeafletIcon()` |
| Geo JS | `icon-glyph.js` | `buildMarkerGlyphHtml` → `geo-map-marker-glyph--img` |
| Tema | `app/07-map-clusters-and-leaflet.css` | Lock glifo 22px, `__glyph-pad` bianco, `__point` |
| Tema | `app/13-final-runtime-overrides.css` | Popup header (non marker) |

---

## classi bem marker (2026-06-03)

| Classe | Ruolo |
|--------|--------|
| `geo-map-marker-wrapper--card` | `className` divIcon Leaflet |
| `geo-map-marker-card` | 40×44px + CSS vars |
| `geo-map-marker-card__shell` | Hover lift (transform-origin basso) |
| `geo-map-marker-card__inner` | Corpo 36px stato |
| `geo-map-marker-card__glyph-pad` | Pad bianco 28px |
| `geo-map-marker-card__point` | Punta triangolo 8px |

Variabili inline: `--status-color`, `--status-fill`, `--status-glow`.

**Deprecato nel tema:** regole su `__body`, glifo 28px, `filter: brightness(0)` obbligatorio.

---

## override tema ammessi

In `07-map-clusters-and-leaflet.css`:

- `__glyph-pad { background: #fff !important }`
- Glifo singolo: **22px**, `filter: none`
- `__point { border-top-color: var(--status-color) !important }`

**Vietato:**

- `transform` / `scale` su `.leaflet-marker-icon` (STORY-123)
- `border: none` su `__inner` (rimuove alone stato)
- Sfondo bianco su `__inner`

---

## build

```bash
cd laravel/Themes/Sixteen && npm run build
```

---

## ricostruzione rapida

| Sintomo | Verifica |
|---------|----------|
| Pin tutto bianco | `__inner` ha gradiente + `--status-fill` inline |
| Senza punta | HTML contiene `geo-map-marker-card__point` |
| Anchor sbagliato | `iconAnchor: [20, 44]` |
| Glifo gigante | Tema non forza 28px su marker singolo |

SSoT: [geo-map-lit-reconstruction-guide.md](../../../../Modules/Geo/docs/wiki/concepts/geo-map-lit-reconstruction-guide.md) § marker.

---

## collegamenti

- [geo-map-popup-leaflet-boundary.md](./geo-map-popup-leaflet-boundary.md)
- [marker-cluster-hover-stability.md](./marker-cluster-hover-stability.md)
- [geo-map-marker-status-background.md](../../../../Modules/Geo/docs/wiki/concepts/geo-map-marker-status-background.md)
