## REGOLA PERMANENTE: Visibilità mappa nel wizard di segnalazione

### Visibilità absoluta

```
OBBLIGATORIO: quando si implementa una mappa (Leaflet/LeafletMarkerMapInput) all'interno di un wizard Filament (CreateTicketWizardWidget):

1. **Pulsanti di controllo (fullscreen/centra posizione)**
   - Devono essere sempre visibili nell'overlay (posizione fixed nell'overlay) e non nascere solo quando lo step è attivo
   - Deve essere possibile cliccarli anche quando lo step non è visibile

2. **Gestione dimensione della mappa dopo cambio step**
   - Quando lo step con la mappa diventa visibile: chiamare `invalidateSize()` sul container Leaflet
   - Quando si esce da fullscreen: ripristinare dimensioni normali

3. **Inizializzazione geolocalizzazione**
   - Se latitude/longitude sono null, calcolare automaticamente la posizione corrente (via browser GPS)
   - Mantenere il marker aggiornato dopo drag o new position

```

### Applicazione

- Modulo Geo: aggiornare `leaflet-marker-map-input.blade.php` per rispettare questa regola
- Wizard widget: controllare che `CreateTicketWizardWidget` implementi correttamente il pattern

### Documentazione

- Aggiungere alla wiki: `laravel/Modules/Geo/docs/wiki/concepts/map-in-wizard-visibility.md`
- Costruire everywhere reference nella documentazione del Form Schema

### Verifica automatica

- In every Pull Request relativi al wizard o al modulo Geo, verificare l'implementazione di questa regola
- Usare `qmd search "mappa wizard"` prima di modificare code

### Bug report

- Qualsiasi violazione di questa regola si segnalerà automaticamente con `stories/badfix`
