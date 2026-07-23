# Map Integration - Theme Two


## Panoramica

Il tema Two utilizza OpenStreetMap per le mappe di localizzazione, senza necessità di API key.

## Componenti

### Embed Map Block

**View**: `pub_theme::components.blocks.map.embed`

**Posizione**: `Themes/Two/resources/views/components/blocks/map/embed.blade.php`

**Proprietà**:
- `title` (string): Titolo della sezione
- `address` (string): Indirizzo completo
- `coordinates` (array): Coordinate `{lat: float, lng: float}`

**Utilizzo in JSON**:
```json
{
    "type": "map",
    "slug": "location-map",
    "data": {
        "view": "pub_theme::components.blocks.map.embed",
        "title": "Dove Siamo",
        "address": "Via Vanzo 86/A, 31021 Mogliano Veneto TV",
        "coordinates": {
            "lat": 45.5648,
            "lng": 12.2347
        }
    }
}
```

## Integrazione GEO Module

Il modulo Geo fornisce i modelli per la gestione dei dati geografici:
- `Address`: Per indirizzi completi
- `Comune`: Per comuni italiani
- `Place`: Per luoghi generici

## Coordinate per Sottana Service

- **Indirizzo**: Via Vanzo 86/A, 31021 Mogliano Veneto TV
- **Latitudine**: 45.5648
- **Longitudine**: 12.2347

## Note Tecniche

- OpenStreetMap non richiede API key
- L'embed usa `iframe` con bbox dinamico
- Link indicazioni: Google Maps (per navigazione turn-by-turn)
