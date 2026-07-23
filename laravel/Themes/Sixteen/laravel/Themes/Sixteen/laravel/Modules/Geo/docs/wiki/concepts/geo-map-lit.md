# geo-map-lit Component

## Overview

`geo-map-lit` is a web component (LitElement) that renders an interactive Leaflet map with ticket data from a JSON source.

## Usage in Blade

```blade
<geo-map-lit
    data-url="{{ asset('data/tickets.json') }}"
    height="500px"
></geo-map-lit>
```

## Required Assets

The component is bundled via Vite. After `npm run build` the compiled JS files are in:
- `public/assets/geo-map-lit-*.js`
- `public/assets/map-lit-*.js`

The component definition is in:
- `laravel/Themes/Sixteen/src/components/my-map.js`
- `laravel/Themes/Sixteen/src/components/my-map.ts`

## Data Source

The component expects a GeoJSON FeatureCollection at the provided `data-url`. Example:

```json
{
    "type": "FeatureCollection",
    "features": [
        {
            "type": "Feature",
            "geometry": {"type": "Point", "coordinates": [12.4964, 41.9028]},
            "properties": {"id": 1, "title": "Ticket title"}
        }
    ]
}
```

## Initialization

The component auto-initializes when the DOM is ready. It uses Leaflet via the bundled assets. No manual JS initialization required.

## Troubleshooting

- Verify `data-url` returns valid JSON (use browser dev tools network tab)
- Confirm `npm run build` has been run after any JS changes
- Check browser console for Leaflet or component errors

## Related Files

- `laravel/Themes/Sixteen/src/components/my-map.js`
- `laravel/Modules/Geo/public/assets/geo-map-lit-*.js`
- `public/data/tickets.json`
