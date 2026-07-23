# HTML Parity Analysis: segnalazione-02-dati

## Stato attuale
- Reference: `https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html`
- Local: `http://127.0.0.1:8000/it/tests/segnalazione-02-dati`
- Blade pagina: [`/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php`](/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php)
- Blade blocco: [`/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/resources/views/components/blocks/tests/segnalazione-02-dati.blade.php`](/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/resources/views/components/blocks/tests/segnalazione-02-dati.blade.php)
- JSON sorgente: [`/var/www/_bases/base_fixcity_fila5/laravel/config/local/fixcity/database/content/pages/tests.segnalazione-02-dati.json`](/var/www/_bases/base_fixcity_fila5/laravel/config/local/fixcity/database/content/pages/tests.segnalazione-02-dati.json)

## Risultato confronto
- Parity score: `90.52%`
- Tag parity: `98.57%`
- ID parity: `100.0%`
- Class parity: `96.65%`
- ✅ Identici: `463`
- ⚠️ Differenti: `86`
- ❌ Mancanti: `10`
- ➕ Extra: `4`
- Stato: `PASS`

## Artifact prodotti
- [`/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/prompts/segnalazione-02-dati/body-structure-comparison/segnalazione-02-dati-comparison-report.md`](/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/prompts/segnalazione-02-dati/body-structure-comparison/segnalazione-02-dati-comparison-report.md)
- [`/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/prompts/segnalazione-02-dati/body-structure-comparison/segnalazione-02-dati-comparison-report.json`](/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/prompts/segnalazione-02-dati/body-structure-comparison/segnalazione-02-dati-comparison-report.json)
- [`/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/prompts/segnalazione-02-dati/body-structure-comparison/reference-body.html`](/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/prompts/segnalazione-02-dati/body-structure-comparison/reference-body.html)
- [`/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/prompts/segnalazione-02-dati/body-structure-comparison/local-body.html`](/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/prompts/segnalazione-02-dati/body-structure-comparison/local-body.html)

## Delta residuo prioritario
- Allineare gli attributi SVG `use` e altri attributi markup che oggi generano molti `⚠️` senza abbattere il punteggio totale.
- Rimuovere le stringhe italiane hardcoded nel blocco Blade e sostituirle con chiavi `fixcity::context.collection.element.type` corrette.
- Pulire le ultime differenze di classi/attributi su breadcrumb, nav e CTA del form per ridurre i `10` mancanti e i `4` extra.
