# Mappa Statica Cliccabile - Pagina Contatti

## Problema
La pagina contatti (`/it/pages/contacts`) attualmente usa un iframe OpenStreetMap che:
- Richiede caricamento JavaScript esterno
- Può essere lento su connessioni lente
- Non è ottimale per mobile
- Non è chiaro che sia interattiva

## Soluzione Implementata
Mappa statica PNG cliccabile che:
- Carica immediatamente (immagine statica)
- Al click apre Google Maps sul punto/indirizzo (link gratuito, NON API)
- Performance migliori
- UX più chiara

## Indirizzo
**Via Vanzo 86/A, 31021 Mogliano Veneto TV**
- Coordinate: 45.5633, 12.2506 (calcolate con Nominatim/OpenStreetMap)

## Componente Creato
`pub_theme::components.blocks.map.static-clickable`

### Posizione File
`laravel/Themes/Two/resources/views/components/blocks/map/static-clickable.blade.php`

### Immagine Mappa
`laravel/public/modules/techplanner/images/map-via-vanzo.png`

## Integrazione JSON

Nel file `contacts.json`, aggiungere/modificare il blocco mappa:

```json
{
    "type": "map",
    "slug": "location-map",
    "data": {
        "view": "pub_theme::components.blocks.map.static-clickable",
        "title": "Dove Siamo",
        "address": "Via Vanzo 86/A, 31021 Mogliano Veneto TV",
        "coordinates": {
            "lat": 45.5633,
            "lng": 12.2506
        },
        "image_path": "techplanner::images.map-via-vanzo"
    }
}
```

## Link Destinazione

**Pattern utilizzato (Google Maps - link gratuito, NON API):**

```
https://www.google.com/maps?q=45.5633,12.2506
```

Oppure con coordinate (marker + zoom):

```
https://www.openstreetmap.org/?mlat=45.5633&mlon=12.2506#map=17/45.5633/12.2506
```

## 🚨 Regola Critica: Solo Servizi Gratuiti

**MAI usare:**
- ❌ Google Maps Static API
- ❌ Google Maps JavaScript API
- ❌ Mapbox API
- ❌ Qualsiasi servizio che richiede API key a pagamento

**SEMPRE usare:**
- ✅ Screenshot manuale da Google Maps UI (per mappe statiche PNG) - **RACCOMANDATO**
- ✅ Google Maps iframe embed (gratuito, NON API) - fallback quando manca PNG
- ✅ Link Google Maps per navigazione (gratuito, non è API)

## Vantaggi

1. **Performance:**
   - Nessun JavaScript esterno
   - Caricamento immediato immagine
   - Riduzione richieste HTTP

2. **UX:**
   - Chiaro che è cliccabile
   - Apertura diretta OpenStreetMap
   - Funziona su tutti i dispositivi

3. **Manutenibilità:**
   - Immagine statica facilmente sostituibile
   - Nessuna dipendenza API runtime
   - Controllo completo su visualizzazione

## Verifica

- [ ] Immagine PNG salvata correttamente
- [ ] Componente Blade creato e funzionante
- [ ] Link OpenStreetMap funziona correttamente
- [ ] Responsive su mobile
- [ ] Accessibilità verificata
- [ ] Test su browser diversi

## Collegamenti

- [Modulo GEO - Documentazione Mappe Statiche](../../Modules/Geo/docs/static-map-clickable-implementation.md)
- [Componente Blade](../../resources/views/components/blocks/map/static-clickable.blade.php)
- [Immagine Mappa](../../../Modules/TechPlanner/resources/images/map-via-vanzo.png)
