# Theme Sixteen: Integration Patterns

**Data:** 2026-06-03  
**Scope:** Map-lit integration, Vite build, component patterns  
**Status:** STORY-121/122/123 completati

---

## Map-Lit Integration Pattern

### Blade Component Usage

```blade
{{-- resources/views/components/sections/map-lit.blade.php --}}
@php
$lat = $lat ?? null;
$lng = $lng ?? null;
@endphp

<map-lit
    id="{{ $id ?? 'ticket-map' }}"
    data-url="{{ $dataUrl ?? '/data/tickets.json' }}"
    height="{{ $height ?? 'clamp(360px,58vh,560px)' }}"
    @if($lat !== null) lat="{{ $lat }}" @endif
    @if($lng !== null) lng="{{ $lng }}" @endif
></map-lit>
```

### View Model Pattern

```php
// In ViewModel
public function mapData(): array
{
    return [
        'dataUrl' => '/data/tickets.json',
        // NO lat/lng = GPS default (creazione)
    ];
}

public function editMapData(Model $model): array
{
    return [
        'dataUrl' => '/data/tickets.json',
        'lat' => $model->latitude,   // Esplicito = modifica
        'lng' => $model->longitude,
    ];
}
```

---

## Vite Build Configuration

### Alias Resolution
```javascript
// vite.config.js
export default defineConfig({
    resolve: {
        alias: {
            'leaflet': path.resolve(__dirname, 'node_modules/leaflet/dist/leaflet.js'),
            'leaflet.markercluster': path.resolve(__dirname, 'node_modules/leaflet.markercluster/dist/leaflet.markercluster.js'),
        }
    },
    build: {
        rollupOptions: {
            input: {
                app: './resources/js/app.js',
                'map-lit': '../../Modules/Geo/resources/js/components/map-lit.js',
            }
        }
    }
});
```

### Build Verification
```bash
cd laravel/Themes/Sixteen
npm run build

# Verifica output
cat public_html/themes/Sixteen/manifest.json | grep map-lit
# → "map-lit-2K3zgE8Q.js"
```

---

## Component Integration Checklist

- [ ] Vite alias config corretto
- [ ] Blade component passa attributi corretti
- [ ] ViewModel non usa flag booleani ridondanti
- [ ] Data URL usa SSoT (`/data/tickets.json`)
- [ ] Build include dipendenze (check bundle size)
- [ ] View cache cleared dopo build

---

## Error Resolution Log

### Error: `L is not defined`
**Fix:** Assicurati `window.L = L` in `_ensureLeafletPlugins()`

### Error: `markerClusterGroup is not a function`
**Fix:** Controlla vite.config.js alias per leaflet.markercluster

### Error: Mappa bianca
**Fix:** 
1. Verifica `data-url` punta a file esistente
2. Controlla console per errori CORS
3. Verifica bundle caricato (manifest.json)

---

## Collegamenti

- [Geo Module: map-lit-lessons-learned](../../Modules/Geo/docs/map-lit-lessons-learned.md)
- [Root: STORY-121](../../../docs/stories/STORY-121-map-lit-non-visibile-verifica-fix.md)
