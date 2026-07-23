# Geo Picker Theme Consumption Rule

## Regola

Il tema Sixteen consuma i picker Geo ma non li possiede.

Quando una pagina del tema, ad esempio il flusso `segnalazione-crea`, mostra `CoordinatePicker`, `MapPicker`, `LocationPicker`, `LatitudeLongitudeInput`, `PlacePicker`, `MapPositioner`, `MapLocationInput`, `LeafletMarkerMapInput` o `GeopointPicker`, la responsabilita resta divisa:

- Geo possiede field PHP, Blade del field, Lit/Web Component, Leaflet, marker, geolocation e contratto eventi;
- Sixteen possiede layout, spacing, visual parity, import asset tema e integrazione Design Comuni;
- Fixcity possiede il workflow di dominio che usa il field.

## Implicazione pratica

Il tema non deve duplicare o ridefinire componenti mappa. Deve importare asset corretti, garantire che i container siano visibili e dimensionati, e verificare visual parity nel contesto pagina.

Se un controllo mappa manca nel runtime Sixteen (fullscreen, layer switcher, posizione corrente, marker), prima si verifica se:

1. il componente Geo emette/renderizza il controllo;
2. gli asset Geo sono importati nel bundle corretto;
3. il CSS tema nasconde o sovrascrive Leaflet controls/marker;
4. il wizard monta la mappa in un container hidden senza `invalidateSize()`.

### Nota per Layout & Wizards
I componenti Geo ora implementano un **`ResizeObserver`** interno e il pattern **"Guarded Light DOM"**. 
Il tema deve garantire che i container non usino `overflow: hidden` aggressivi che potrebbero tagliare i controlli `absolute` o i marker che sforano leggermente il bordo (`44x56px`).

## Backlinks

- [Geo picker sibling governance](../../../../../Modules/Geo/docs/wiki/concepts/geo-picker-sibling-components-governance.md)
- [Leaflet class selector governance](../../../../../Modules/Geo/docs/wiki/concepts/leaflet-class-selector-governance.md)
- [Segnalazione runtime asset integrity](./segnalazione-runtime-asset-integrity.md)
