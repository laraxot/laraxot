# Map Integration - Regole Free Only

**Regola**: MAI usare Google Maps API a pagamento

## Regola Fondamentale

**NON USARE MAI Google Maps API a pagamento o che richiede billing.**

## Opzioni Free Supportate

### 1. OpenStreetMap (OSM) - Iframe Embed
- **URL**: `https://www.openstreetmap.org/export/embed.html`
- **Nessuna API key richiesta**
- **Gratuito**
- Usato come fallback nel componente static-clickable

### 2. OpenStreetMap - Static Map
- **URL**: `https://staticmaps.openstreetmap.de/staticmap.php`
- **Nessuna API key richiesta** (alcune limitazioni)
- **Gratuito**

### 3. Nominatim per Geocoding
- **URL**: `https://nominatim.openstreetmap.org/search`
- **Nessuna API key richiesta** (rate limit: 1 req/sec)
- **Gratuito**

### 4. Screenshot Manuale
- Scaricare immagine da OSM/Google Maps manualmente
- Salvare in `Themes/Two/Main_files/images/`
- Linkare a OpenStreetMap
- **Vantaggi**: Nessuna chiamata esterna a runtime, performance migliori

### 5. Link OpenStreetMap per Navigazione (GRATUITO)
- Link diretto: `https://www.openstreetmap.org/search?query=INDIRIZZO#map=15/INDIRIZZO`
- Non richiede API key
- Gratuito per navigazione

## Implementazione Attuale (Theme Two)

### Componente: `pub_theme::components.blocks.map.static-clickable`
1. **Prioritaria**: Immagine PNG statica pre-generata (se disponibile)
2. **Fallback**: Placeholder testuale + pulsante Google Maps (link, NON API)
3. **Navigazione**: Link Google Maps (gratuito, NON API)

### Componente: `pub_theme::components.blocks.map.embed`
- Usa OpenStreetMap iframe
- Link a OpenStreetMap (search/marker)

### Coordinate Sottana Service
- **Indirizzo**: Via Vanzo 86/A, 31021 Mogliano Veneto TV
- **Lat**: 45.5633
- **Lng**: 12.2506

## Note Importanti

### Perché non Google Maps API?
- Richiede API key con billing
- Costi aggiuntivi per embed e geocoding
- Complessità di configurazione

### Perché OpenStreetMap?
- Completamente gratuito
- Nessuna API key richiesta
- Open source
- Privacy migliore (no tracciamento Google)

## Todo Future

- [ ] Generare mappa statica PNG da OSM (manuale)
- [ ] Integrare con Geo Module per geocoding
- [ ] Aggiungere supporto per Leaflet.js
