# Mappa Statica - Analisi e Implementazione

**Progetto**: Sottana Service - Theme Two

## Problema

La mappa dinamica (OpenStreetMap iframe) non si visualizza correttamente nella pagina contatti. La mappa attuale ha проблемi di rendering.

## Richiesta

1. Mappa statica (PNG/JPG) che mostra la posizione
2. Al click sulla mappa → si apre Google Maps con l'indirizzo come destinazione
3. L'indirizzo: Via Vanzo 86/A, 31021 Mogliano Veneto TV

## Opzioni Considerate

### Opzione 1: Google Maps Static API
- **Pro**: Qualità alta, sempre aggiornato
- **Contro**: Richiede API key con billing

### Opzione 2: Screenshot manuale
- **Pro**: Nessun costo, controllo totale
- **Contro**: Non si aggiorna automaticamente

### Opzione 3: OpenStreetMap statico (Nominatim)
- **Pro**: Gratuito
- **Contro**: Qualità limitata, potrebbe non funzionare

## Decisione

Scaricare una mappa statica (screenshot) da Google Maps e usarla come immagine statica con link esterno.

## Dettagli Implementazione

### Posizione immagine
- **Path**: `Themes/Two/Main_files/images/`
- **Nome**: `map-location.png`

### Componente
- Modificare `Themes/Two/resources/views/components/blocks/map/embed.blade.php`
- Usare `<a>` wrapper con `target="_blank"`
- Link: `https://www.google.com/maps/dir/?api=1&destination=Via+Vanz o+86/A+31021+Mogliano+Veneto+TV`

### Integrazione Geo Module (Futuro)
Il modulo Geo dovrebbe fornire:
- Modello Address per indirizzi
- Servizio di geocoding
- Generazione URL mappa statica

## Coordinate

- **Latitudine**: 45.5648
- **Longitudine**: 12.2347

## Todo

- [ ] Scaricare mappa statica
- [ ] Modificare componente mappa
- [ ] Testare visualizzazione
- [ ] Verificare link Google Maps
