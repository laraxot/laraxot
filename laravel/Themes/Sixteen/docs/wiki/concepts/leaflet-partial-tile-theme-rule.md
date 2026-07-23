# Leaflet Partial Tile Theme Rule

## Regola

Il tema Sixteen deve dare al `coordinate-picker-lit` dimensioni stabili e non ambigue, cosi' Leaflet puo' caricare l'intera griglia tile. Quadrati vuoti nella mappa indicano quasi sempre un mismatch tra dimensioni CSS reali e ultimo `invalidateSize()`.

## Best practices

- Definire `coordinate-picker-lit`, `.map-container`, `.map-picker-leaflet-pane` e `.leaflet-container` con `display:block`, `width:100%` e altezza/min-height coerente.
- In fullscreen, usare `height:100vh` e `width:100vw` sul container e sul pane Leaflet.
- Tenere i controlli mappa in overlay con `pointer-events:auto` e z-index sopra i tile.
- Dopo ogni modifica CSS tema, eseguire build/copy e verifica visiva.

## Bad practices

- Lasciare che l'altezza dipenda solo dal contenuto interno dei tile.
- Applicare filtri/opacity/overlay che fanno sembrare la mappa disabilitata.
- Usare selector per pagina o ticket per correggere il tile layout.

## False friends

- Se alcuni tile sono visibili, la rete non e' necessariamente il problema: spesso e' una size Leaflet vecchia.
- Un CSS `min-height` sul custom element non basta se il pane Leaflet interno resta con `height: 0` o eredita un parent instabile.
- Il fullscreen puo' peggiorare i quadrati vuoti se il repaint avviene prima che il layout fixed sia completato.
