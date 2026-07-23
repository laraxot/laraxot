# Stabilità cluster marker al hover (Leaflet.markercluster)

## Scopo

Evitare che i cluster sulla mappa `/it` "scappino" o tremino al passaggio del mouse.
Root cause reale verificata STORY-123 (Playwright: `movedPx 0/0`).

## Perché succede

Leaflet posiziona ogni `.leaflet-marker-icon` (inclusi i cluster divIcon) con CSS:

```css
transform: translate3d(Xpx, Ypx, 0px);
```

Se **qualsiasi** regola CSS (tema bundlato, light-DOM Lit, `!important`) imposta `transform` sull'icona o su un figlio che partecipa al box model dell'anchor — ad es. `transform: scale(1.1)` — **sovrascrive** la traslazione Leaflet. Il cluster salta verso l'origine del pane; l'hover si spegne; torna indietro; loop → tremolio.

`transition: transform` **peggiora** l'effetto (animazione del salto).

## Fix applicato (2026-06-03)

### 1. CSS tema — fix principale

File: `resources/css/app/07-map-clusters-and-leaflet.css`

**Prima (bug):**

```css
.geo-cluster-wrapper:hover { transform: scale(1.1) !important; }
.geo-cluster-wrapper { transition: transform 0.2s ease !important; }
```

**Dopo (corretto):**

```css
.geo-cluster-circle { transition: box-shadow 0.2s ease !important; }
.geo-cluster-wrapper:hover .geo-cluster-circle {
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4), ... !important;
}
/* MAI transform su .geo-cluster-wrapper (= .leaflet-marker-icon) */
```

### 2. Dimensioni coerenti

- divIcon in `map-lit.js`: 80×80, `iconAnchor: (40, 40)`
- `listing-parity.css`: cluster su `/it` allineati a 80px (non 2.75rem)

### 3. JS modulo Geo

- **Mai** `refreshClusters()` manuale (`_scheduleClusterRefresh` rimosso)
- `refreshWhenVisible()` → solo `invalidateSize`, no refresh cluster
- Opzioni: `removeOutsideVisibleBounds: false` (STORY-124, marker restano dopo GPS lontano)

### 4. Light-DOM Lit (`map/styles.js`)

Già conforme: hover solo `box-shadow`. Da solo **non basta** se il tema vince con `!important`.

## Pattern farmshops.eu

[direktvermarkter.js](https://github.com/CodeforKarlsruhe/farmshops.eu/blob/master/js/direktvermarkter.js): feedback hover su colore/ombra, **no** `transform` sui marker-icon.

## Verifica

```bash
cd laravel/Themes/Sixteen && npm run build
cd /var/www/_bases/base_fixcity_fila5
npx playwright test --config=laravel/Modules/Geo/playwright.config.js \
  laravel/Modules/Geo/tests/Playwright/map-lit-cluster-hover-stability.spec.js
```

## Anti-pattern

| ❌ Vietato | Perché |
|-----------|--------|
| `transform: scale()` su `.leaflet-marker-icon` | Rompe translate3d Leaflet |
| `transition: transform` sul wrapper cluster | Anima il salto |
| `refreshClusters()` dopo ogni moveend/GPS | Race con plugin |
| Fix solo in `map/styles.js` ignorando `app.css` | Cascade tema vince |

## Collegamenti

- [leaflet-no-transform-on-marker-icon.md](./leaflet-no-transform-on-marker-icon.md) — lezioni trasversali STORY-123 (cascade light-DOM vs tema, disciplina git multi-repo)
- [map-lit-vite-build-troubleshooting.md](./map-lit-vite-build-troubleshooting.md)
- [ticket-list-map-integration.md](./ticket-list-map-integration.md)
- Modulo Geo: `docs/wiki/troubleshooting/map-lit-it-incidents-2026-06.md`
- GitHub: [module_geo_fila5#27](https://github.com/laraxot/module_geo_fila5/issues/27)
- Story: `docs/stories/STORY-123-map-lit-cluster-hover-escape-fix.md`

## Pallini tipo nel cluster (incidente #12)

Oltre al `transform` sul wrapper, i **contenuti** del cluster devono restare 14px. Vedi modulo Geo: [map-lit-cluster-type-icons.md](../../../../../Modules/Geo/docs/wiki/concepts/map-lit-cluster-type-icons.md).
