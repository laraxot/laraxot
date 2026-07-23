# Regola: Solo Servizi Mappe Gratuiti

## Regola Critica per Tema Two

**🚨 MAI usare Google Maps API, Mapbox API o altri servizi a pagamento per mappe.**

**SEMPRE usare solo servizi gratuiti e open source.**

## Implementazione Corrente

### Mappa Statica Pagina Contatti

**Componente:** `pub_theme::components.blocks.map.static-clickable`

**Servizi Utilizzati:**
- ✅ Screenshot manuale (UI) per PNG statica (no API)
- ✅ Link Google Maps per navigazione (gratuito, NON API)

**Servizi NON Utilizzati:**
- ❌ Google Maps Static API
- ❌ Google Maps JavaScript API
- ❌ Mapbox API

## Pattern Implementazione

### Mappa Statica

```php
// Usare OpenStreetMap Export API (gratuito)
$bbox = 'min_lon,min_lat,max_lon,max_lat';
$imageUrl = "https://render.openstreetmap.org/cgi-bin/export?bbox={$bbox}&scale=10000&format=png";
```

### Link Destinazione

```php
// Link Google Maps (gratuito, NON API)
$mapUrl = "https://www.google.com/maps/search/?api=1&query={$address}";
```

## Verifica

Prima di ogni commit, verificare:
- [ ] Nessuna chiamata a Google Maps API
- [ ] Nessuna chiamata a Mapbox API
- [ ] PNG statica via screenshot manuale (no API)
- [ ] Link destinazione: solo Google Maps link (NON API)

## Documentazione Correlata

- [Regola Cursor](../../../../.cursor/rules/free-maps-only.mdc)
- [Modulo GEO: Mappe Solo Gratuite](../../Modules/Geo/docs/mappe-solo-gratuite.md)
- [Implementazione Completa](./mappa-statica-implementazione-completa.md)
