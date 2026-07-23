# Coordinate Picker Fullscreen Theme Layer Rule

## Regola

Nel tema Sixteen, il fullscreen del componente `coordinate-picker-lit` deve coprire tutto il viewport e stare sopra i blocchi Design Comuni del wizard, inclusi sidebar navscroll e box informativi.

## Best practices

- Scrivere CSS generico per `coordinate-picker-lit .map-container.is-fullscreen`.
- Usare z-index sopra i layer Bootstrap Italia ordinari e coerente con il sistema tema.
- Garantire `width: 100vw`, `height: 100vh`, `inset: 0`, `border-radius: 0`.
- Rendere visibili i controlli fullscreen/zoom/layer sopra la mappa con z-index interno superiore ai tile Leaflet.
- Dopo modifiche CSS tema eseguire `npm run build` e `npm run copy` dalla cartella `laravel/Themes/Sixteen`.

## Bad practices

- Scrivere `.page-content[data-slug="..."]` o `.ticket-wizard-root` per risolvere il fullscreen.
- Mettere `<style>` nel Blade del modulo o inline style JS nel template.
- Abbassare il box informativo solo nello step segnalazione invece di alzare correttamente il layer mappa.

## False friends

- Un `z-index: 9999` sul container puo' non vincere se la regola viene sovrascritta dal tema o se il fullscreen non e' applicato al nodo giusto.
- Nascondere il box informativo non e' la soluzione: in fullscreen deve semplicemente stare sotto l'overlay mappa.
- Se il bundle non viene copiato in `public_html/themes/Sixteen`, il browser continua a mostrare la versione vecchia anche se `resources/css/app.css` e' corretto.

## Story 8-74

La refinement story `8-74-segnalazione-crea-map-fullscreen-refinement` conferma che il tema deve limitarsi al layer CSS/parity:

- `coordinate-picker-lit:fullscreen` per il Fullscreen API reale;
- `coordinate-picker-lit .map-container.is-fullscreen` per fallback CSS;
- `html.geo-map-fullscreen-active` per blocco overflow documento;
- nessun selector page-scoped o wizard-scoped;
- build/copy obbligatorio dopo modifiche a `resources/css/app.css`.
