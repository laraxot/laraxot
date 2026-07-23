---
name: leaflet-map-flicker-visual-contract
description: Theme-side visual contract for stable Leaflet rendering inside public wizards
---

# Leaflet Map Flicker Visual Contract

## Goal

Nel tema Sixteen una mappa Leaflet dentro un wizard pubblico non deve:

- lampeggiare al primo load;
- lampeggiare durante pan o zoom normali;
- sembrare disabilitata da overlay bianchi non voluti;
- mostrare tile a blocchi per mismatch di dimensioni.

## Theme Contract

Il tema deve fornire al componente mappa un box stabile e leggibile:

- `coordinate-picker-lit` e il suo container devono avere dimensioni continue;
- nessun overlay di layout del wizard deve coprire il canvas interattivo;
- fullscreen e layout normale devono evitare scrollbar spurie;
- i controlli devono restare visibili sopra la mappa.

## What The Theme Must Not Do

- CSS per pagina o per singolo caso d'uso (`[data-slug]`, `.ticket-wizard-root`);
- animazioni/transizioni che cambiano continuamente l'altezza del box mappa;
- z-index casuali che introducono pannelli sopra il viewport Leaflet;
- workaround JS nel tema per compensare flicker runtime del modulo Geo.

## Cross-Module Boundary

- **Geo** risolve refresh, sizing runtime e redraw tile.
- **Sixteen** risolve contrasto, stacking, min-height e continuita di layout.
- **Fixcity** mantiene markup e ordine semantico dello step wizard.
