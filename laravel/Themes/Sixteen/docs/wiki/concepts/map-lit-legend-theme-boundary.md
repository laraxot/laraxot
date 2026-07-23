# Legenda mappa — confine tema vs modulo Geo

## Scopo

Chiarire **chi possiede cosa** per la legenda tipologie su `/it` (STORY-094).

## Modulo Geo (SSoT funzionale)

- Logica: `Modules/Geo/resources/js/components/map/legend.js`
- Mount: `map-lit.js` → `_syncMapLegend()`
- Stili base: `map/styles.js` (`.geo-map-legend*` nel light-DOM Lit)

## Tema Sixteen (solo presentazione globale)

- Margine control Leaflet: `resources/css/app/07-map-clusters-and-leaflet.css`

```css
.leaflet-bottom.leaflet-left .geo-map-legend {
  margin: 0 0 12px 12px;
}
```

- **Non** duplicare tipi/colori in Blade (`map-legend.blade.php` legacy resta opzionale fuori mappa)

## Perché non in Blade

La legenda deve restare sincronizzata con:

- `/data/tickets.json`
- Filtri desktop (`filters-changed` → `filterByTypes`)

Solo il componente Lit conosce lo stato del layer marker.

## Verifica visuale

Legenda in basso a sinistra sulla mappa elenco; non sovrapposta ai controlli zoom (destra).

## Collegamenti

- Geo: [map-lit-legend-types.md](../../../../../Modules/Geo/docs/wiki/concepts/map-lit-legend-types.md)
- [ticket-list-map-integration.md](./ticket-list-map-integration.md)
