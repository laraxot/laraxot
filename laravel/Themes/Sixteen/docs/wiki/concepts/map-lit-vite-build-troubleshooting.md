# map-lit — build Vite e mappa non visibile

## Scopo

Diagnosticare e risolvere mappa vuota su `/it` quando `map-lit` non si registra o il bundle JS restituisce 404 (STORY-121).

## Sintomi

- Tab Mappa vuota (solo tile Leaflet o area grigia)
- Console: `Failed to load resource` su `map-lit-*.js` 404
- `customElements.get('map-lit')` → `undefined`
- Blade preloada hash diverso da file in `public_html/themes/Sixteen/assets/`

## Root cause

1. **Manifest Vite obsoleto** — viste Blade compilate con hash vecchio
2. **Alias mancanti** in `vite.config.js` per import Geo (`lit`, `leaflet`, `leaflet.markercluster`, `leaflet.heat`)
3. **Path markercluster errato** — `../../node_modules/...` invece di `node_modules/...`
4. **Asset non copiati** in `public_html` dopo build

## Fix

```bash
cd laravel/Themes/Sixteen
npm install          # prima build su clone pulito
npm run build        # include php artisan view:clear
npm run copy         # se serve sync public_html
```

Alias obbligatori in `vite.config.js`:

```javascript
resolve: {
  alias: {
    lit: path.resolve(__dirname, 'node_modules/lit'),
    leaflet: path.resolve(__dirname, 'node_modules/leaflet'),
    'leaflet.markercluster': path.resolve(__dirname, 'node_modules/leaflet.markercluster'),
    'leaflet.heat': path.resolve(__dirname, 'node_modules/leaflet.heat'),
  },
},
```

## Verifica smoke

```bash
cd laravel/Themes/Sixteen
npm run test:map-lit-smoke   # se definito in package.json
```

Attesi: 12 marker, `data-url=/data/tickets.json`, 0×404 bundle.

## Hard refresh browser

Dopo deploy: Ctrl+Shift+R — cache preload manifest.

## Collegamenti

- [vite-configuration-guide.md](../../vite-configuration-guide.md)
- [build-issues-resolution.md](../../build-issues-resolution.md)
- Themes shared: [map-lit-tickets-json-ssot.md](../../../../../Themes/docs/shared-components/map-lit-tickets-json-ssot.md)
- GitHub: [module_geo_fila5#23](https://github.com/laraxot/module_geo_fila5/issues/23)
