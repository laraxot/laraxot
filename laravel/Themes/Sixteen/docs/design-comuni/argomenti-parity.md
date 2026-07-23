# Argomenti Parity Report

## Scope

- Reference: `https://italia.github.io/design-comuni-pagine-statiche/sito/argomenti.html`
- Local target: `http://127.0.0.1:8000/it/tests/argomenti`
- Blade route: [laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php](/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php)
- JSON content: [laravel/config/local/fixcity/database/content/pages/tests.argomenti.json](/var/www/_bases/base_fixcity_fila5/laravel/config/local/fixcity/database/content/pages/tests.argomenti.json)

## Current State

`argomenti` e' adesso la prima pagina del batch che ha superato il gate strutturale utile per passare a CSS/JS.

Verifica batch aggiornata:

- `body parity`: `90%`
- `main parity`: `100%`
- stato: `READY`

Fonte batch:

- [design-comuni-batch-audit.md](/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/design-comuni/design-comuni-batch-audit.md)

## Structural Fixes Applied

Sono stati riallineati i blocchi che compongono `main` per ottenere la stessa sequenza della reference:

1. breadcrumb con wrapper `div.container#main-container`
2. hero con wrapper top-level `div.container`
3. sezione `In evidenza`
4. sezione `Esplora per argomento`
5. feedback con top-level `div.bg-primary`
6. contatti con top-level `div.bg-grey-card.shadow-contacts`

File aggiornati:

- [default.blade.php](/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/resources/views/components/blocks/breadcrumb/default.blade.php)
- [argomenti.blade.php](/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/resources/views/components/blocks/hero/argomenti.blade.php)
- [faq-rating.blade.php](/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/resources/views/components/blocks/feedback/faq-rating.blade.php)
- [faq.blade.php](/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/resources/views/components/blocks/contacts/faq.blade.php)
- [tests.argomenti.json](/var/www/_bases/base_fixcity_fila5/laravel/config/local/fixcity/database/content/pages/tests.argomenti.json)

## Visual Pass

Dopo il riallineamento HTML ho applicato un primo pass CSS scoped su `main[data-page="argomenti"]` in:

- [argomenti-parity.css](/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/resources/css/argomenti-parity.css)

Build eseguita da [laravel/Themes/Sixteen](/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen):

- `npm run build`
- `npm run copy`

## Screenshots

Reference:

- [reference-argomenti.png](/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/design-comuni/screenshots/argomenti-parity/reference-argomenti.png)
- [reference-argomenti-full.png](/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/design-comuni/screenshots/argomenti-parity/reference-argomenti-full.png)

Local:

- [local-argomenti-post-structure.png](/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/design-comuni/screenshots/argomenti-parity/local-argomenti-post-structure.png)
- [local-argomenti-full-pass2.png](/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/design-comuni/screenshots/argomenti-parity/local-argomenti-full-pass2.png)

## Residual Visual Differences

La pagina non e' ancora perfetta, ma ora le differenze residue sono davvero visuali e non piu' di composizione HTML:

- immagini `picsum.photos` non stabili tra reference e locale
- hero ancora da rifinire in spacing e peso visivo
- rating ancora semplificato rispetto alla reference Bootstrap Italia
- contatti piu' vicini alla reference ma non ancora identici nei dettagli iconografici

## Decision

Per `argomenti` si puo' continuare con iterazioni CSS/JS mirate.

Per il resto del batch, invece, resta valida la regola: niente CSS/JS finche' la struttura `main` non supera il gate.
