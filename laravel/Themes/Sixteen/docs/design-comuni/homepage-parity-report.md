# Homepage Parity Report

## Scope

Route analizzata: `http://127.0.0.1:8000/it/tests/homepage`  
Riferimento: `https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html`

File sorgente coinvolti:

- `resources/views/pages/tests/[slug].blade.php`
- `resources/views/components/layouts/app.blade.php`
- `resources/views/components/layouts/main.blade.php`
- `resources/views/components/sections/header/v1.blade.php`
- `resources/views/components/sections/search-modal.blade.php`
- `resources/css/app.css`
- `resources/js/app.js`

## Differenze HTML trovate

Prima del fix la homepage locale differiva in quattro punti strutturali:

1. Dentro `main` era presente un wrapper Livewire/Volt con attributi `wire:*`.
2. Il markup conteneva commenti `<!--[if BLOCK]...-->` generati da Volt.
3. Il `search-modal` era renderizzato subito dopo `header`, mentre nel riferimento è dopo `main`.
4. In fondo al `body` comparivano asset extra (`style`, `link rel="modulepreload"`) perché il JS Vite veniva emesso nel body per la route di test.

## Interventi applicati

1. Rendering Blade puro per la route `tests.view`.
2. `main` con `id="main-container"`.
3. Search modal estratto in un partial dedicato.
4. Modal spostato dopo `main` e prima del footer.
5. Bundle JS Vite spostato nell`head` per le route `tests.*`.
6. Override CSS scoped a `.dc-homepage-parity` per riallineare palette e blocchi principali.
7. JS completato per search modal, `Escape` e hamburger menu.

## Differenze visive principali

Le differenze visive più evidenti prima del fix erano:

- palette blu nella top bar e nel footer invece del verde/grigio del riferimento
- sezione feedback blu invece che verde
- gerarchia del blocco “Argomenti in evidenza” troppo fredda rispetto al riferimento
- comportamento JS incompleto del modal e del menu mobile

## Verifica consigliata

1. `npm run build`
2. `npm run copy`
3. screenshot della route locale e del riferimento
4. confronto finale di header, feedback, footer e modal di ricerca

## Footer Check

Analisi screenshot footer:
- [footer-analysis.md](./screenshots/homepage-parity/footer-analysis.md)
- [reference-footer-element.png](./screenshots/homepage-parity/reference-footer-element.png)
- [local-footer-element.png](./screenshots/homepage-parity/local-footer-element.png)

Esito: struttura footer identica al riferimento; il problema residuo era CSS, corretto portando il background finale di `footer#footer` da trasparente a blu antracite e riallineando testi e separatori.


## Header Search Check

Analisi screenshot header/search:
- [header-search-analysis.md](./screenshots/homepage-parity/header-search-analysis.md)
- [reference-header-issues.png](./screenshots/homepage-parity/reference-header-issues.png)
- [local-header-issues.png](./screenshots/homepage-parity/local-header-issues.png)
- [local-header-fixed.png](./screenshots/homepage-parity/local-header-fixed.png)

Esito del fix:
- `container` riallineato ai gutter del riferimento (`x=52`, `width=1176` anche in locale)
- icone social header rese visibili forzando `color/fill` bianchi
- `#search-modal` confermato nascosto; il blocco rimosso era `.cmp-search` dell'hero, non il modal

## Body Structure Refresh

Confronto strutturale attuale del `body` senza `script`:

Riferimento:
- `div.skiplink`
- `header.it-header-wrapper`
- `main`
- `div.modal.fade.search-modal#search-modal`
- `footer.it-footer#footer`

Locale:
- `div.skiplink`
- `header.it-header-wrapper`
- `main#main-container`
- `div.modal.fade.search-modal#search-modal`
- `footer.it-footer#footer`

Confronto strutturale attuale dei figli immediati di `main`:
- `h1.visually-hidden#main-container`
- `section#head-section`
- `section#calendario`
- `section.evidence-section`
- `section.useful-links-section`
- `div.bg-primary`
- `div.bg-grey-card.shadow-contacts`

Esito: la shell HTML utile della homepage e sostanzialmente allineata; la differenza residua a questo livello e l'id `main-container` sul `main` locale. Il confronto grezzo totale resta basso perché il contenuto interno dei blocchi CMS non e ancora perfettamente 1:1, ma il livello strutturale di pagina e ormai oltre la soglia operativa per continuare solo con CSS/JS.

## Above The Fold Pass 4

Analisi residui:
- [header-hero-residuals.md](./screenshots/homepage-parity/header-hero-residuals.md)
- [local-header-hero-pass4.png](./screenshots/homepage-parity/local-header-hero-pass4.png)

Esito del passaggio:
- `header center` allineato a `120px`
- `header navbar` allineato a `54px`
- tipografia hero allineata a `32/40` e `18/28`
- immagine hero allineata a `556x417`
- residuo principale rimasto: il blocco hero locale parte ancora circa `32px` piu in basso del riferimento


## Tooling di ispezione

Script canonici e documentazione tecnica:
- [Bashscripts homepage inspectors](../../../../bashscripts/docs/homepage-visual-parity/inspectors.md)
- [Bashscripts homepage inspectors index](../../../../bashscripts/docs/homepage-visual-parity/README.md)

## CTA Read More Status

Analisi dedicata:
- [readmore-analysis.md](./screenshots/homepage-parity/readmore-analysis.md)
- [Bashscripts homepage inspectors](../../../../bashscripts/docs/homepage-visual-parity/inspectors.md)

Stato corrente del primo CTA `VAI ALLA PAGINA`:
- colore verde allineato al riferimento
- icona freccia riallineata a `32x16`
- padding e altezza del wrapper riallineati a `16/0/0/16` e `40px`
- residuo ancora aperto: il testo locale rende piu stretto del riferimento e il CTA occupa circa `159px` invece di `172.5px`

## Segnalazione Privacy CSS/JS Phase

For `tests/segnalazione-01-privacy` the HTML parity is already above the operational threshold, so the next refinement loop is CSS/JS only.

Current rule for this phase:
- freeze HTML for the target page
- refine only theme assets in `Themes/Sixteen/resources/css` and `resources/js`
- validate through rebuilt assets plus fresh screenshots against the official Design Comuni reference
