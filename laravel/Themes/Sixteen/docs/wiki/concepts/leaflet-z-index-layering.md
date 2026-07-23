# Leaflet Z-Index Layering Rule

## Context
Leaflet uses a pane-based layering system to render map components (tiles, vectors, overlays, markers, shadows, and popups). These panes rely on specific, graduated `z-index` values to ensure markers render on top of tiles, and popups render on top of markers.

If a custom theme style overrides these values incorrectly, markers or overlays can slip below the tile layer, making them invisible to the user even if they are correctly present in the DOM.

## Leaflet Standard Layering (Source of Truth)
By default, Leaflet defines the following pane layering (from back to front):

| Pane | Selector | Default Z-Index | Purpose |
|------|----------|-----------------|---------|
| Map Pane | `.leaflet-map-pane` | `400` | Containment layer for all other panes |
| Tile Pane | `.leaflet-tile-pane` | `200` | Render map background tiles |
| Overlay Pane | `.leaflet-overlay-pane` | `400` | Vector layers (paths, polygons, GeoJSON lines) |
| Shadow Pane | `.leaflet-shadow-pane` | `500` | Drop shadows for markers |
| Marker Pane | `.leaflet-marker-pane` | `600` | Interactive marker icons |
| Tooltip Pane | `.leaflet-tooltip-pane` | `650` | Text tooltips |
| Popup Pane | `.leaflet-popup-pane` | `700` | Info windows / Popups |

## The Bug
In the Sixteen theme, a typo in the custom CSS files:
- `listing-parity.css`
- `map-visual-fix.css`

involuntarily set:
- `.leaflet-marker-pane { z-index: 6 !important; }`
- `.leaflet-shadow-pane { z-index: 5 !important; }`
- `.leaflet-overlay-pane { z-index: 6 !important; }`
- `.leaflet-popup-pane { z-index: 7 !important; }`

Because these values were set to single units (`6`, `5`, `7`) instead of hundreds (`600`, `500`, `700`), they fell way below the `.leaflet-tile-pane` (`200`). Consequently, all markers, shadows, overlays, and popups were rendered underneath the map tiles.

## Rule & Enforcement
1. **Never** override Leaflet pane z-indexes unless absolutely required.
2. If overrides are necessary (for example, to force layering bounds or fix stacking conflicts with theme headers/modals), always use the **standard Leaflet hundreds scale** (`200`, `400`, `500`, `600`, `700`).
3. Under no circumstances should `.leaflet-marker-pane` have a `z-index` value lower than `.leaflet-tile-pane` (`200`).
4. Keep marker and cluster wrapper classes (e.g. `.geo-map-marker-wrapper`, `.geo-cluster-wrapper`) above `600` if custom z-indexing is applied directly to marker icons.

## Related
- [leaflet-no-transform-on-marker-icon](./leaflet-no-transform-on-marker-icon.md) — regola complementare: mai `transform` sul marker-icon (rompe l'anchor `translate3d` di Leaflet, i cluster "scappano" al hover).
