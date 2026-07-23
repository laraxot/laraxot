# Segnalazione Map And Section Spacing Parity

## Regola

Nei wizard Design Comuni, la mappa Geo deve essere pienamente opaca e i controlli fullscreen/locate/layer/zoom devono rimanere sopra il layer Leaflet.

## CSS owner

Le correzioni visuali appartengono a `Themes/Sixteen/resources/css/app.css` o import CSS del tema, non alla Blade del modulo.

I selettori devono essere site/component-level, come nel kit Design Comuni, non legati allo slug pagina:

- usare pattern generici come `.it-form-wizard`;
- usare `[data-step-section="inefficiency"]`;
- usare classi componente mappa (`coordinate-picker-lit`, `.map-container`, `.layer-controls-overlay`);
- evitare `.page-content[data-slug="tests.segnalazione-crea"]` per regole riusabili.
- evitare `.ticket-wizard-root` per regole riusabili, perche' rende il wizard ticket diverso dagli altri wizard.

## Spacing Disservizio

Lo spazio verticale fra heading `Disservizio` e primo campo `Tipo di disservizio` deve essere ridotto nel CSS del tema usando selettori semantici:

- wrapper wizard: `.it-form-wizard`;
- sezione: `[data-step-section="inefficiency"]`;
- container Filament: `.fi-sc-section-*`.

## Build

Dopo la modifica CSS:

```bash
cd laravel/Themes/Sixteen
npm run build
npm run copy
```
