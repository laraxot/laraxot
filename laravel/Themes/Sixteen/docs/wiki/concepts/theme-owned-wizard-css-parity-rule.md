# Theme-Owned Wizard CSS Parity Rule

## Regola Sixteen

Il tema Sixteen e' l'owner degli stili Design Comuni riusabili per wizard e form.

Quando il markup arriva da `laravel/Modules/Fixcity/resources/views/filament/widgets/ticket-create-wizard.blade.php`, il tema deve applicare parity tramite CSS in:

- `resources/css/app.css`;
- oppure file dedicati importati da `resources/css/app.css`.

## Divieti

- Non mettere `<style>` nel Blade del modulo Fixcity.
- Non usare `style=""` per dimensioni, colori o layout in Blade modulo.
- Non introdurre CSS duplicato in componenti Blade quando la regola puo' stare nel bundle tema.
- Non usare selector per pagina (`.page-content[data-slug="..."]`) per fix di wizard.
- Non usare root ticket-specifiche (`.ticket-wizard-root`) per comportamenti comuni dei wizard: preferire hook semantici riusabili come `data-step-section`, classi Bootstrap Italia/Design Comuni o classi del componente.

## Build Obbligatoria

Dopo ogni modifica CSS in Sixteen:

```bash
npm run build
npm run copy
```

I comandi vanno eseguiti da `laravel/Themes/Sixteen`.

## Motivazione

La parity HTML e visuale richiede un solo layer visuale e regole riusabili. Il modulo fornisce semantica e stato; il tema fornisce presentazione e asset compilati.

## Mappa dopo cambio step

Quando la mappa e' dentro un wizard Filament, puo' essere montata prima che lo step sia visibile. Il tema deve garantire contrasto e dimensioni stabili, ma il resize runtime resta nel componente Geo (`coordinate-picker-lit`) tramite `invalidateSize()` quando l'elemento torna visibile.
