# Current Pass 2026-04-04

## Scope

Passaggio CSS/JS verificato su:
- `homepage`
- `lista-risorse`

Target locale usato per le verifiche:
- `http://127.0.0.1:8000/it/tests/homepage`
- `http://127.0.0.1:8000/it/tests/lista-risorse`

Reference:
- `https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html`
- `https://italia.github.io/design-comuni-pagine-statiche/sito/lista-risorse.html`

## Screenshot salvati

Cartella:
- `screenshots/current-pass/`

File principali:
- `screenshots/current-pass/homepage-reference.png`
- `screenshots/current-pass/homepage-local-pass2.png`
- `screenshots/current-pass/lista-risorse-reference.png`
- `screenshots/current-pass/lista-risorse-local-pass3.png`

## Modifiche applicate

### CSS
- nuovo file `resources/css/listing-parity.css`
- import aggiunto in `resources/css/app.css`

### JS
- `resources/js/app.js`
- attivazione automatica voce menu in base a `main[data-page]`
- `body.dataset.page` per scoping CSS stabile
- fallback immagini per card listing quando le placeholder non caricano

## Risultato osservato

### Homepage
Migliorato:
- voce menu attiva visibile
- header tornato stabile dopo rollback del fix aggressivo globale
- immagini hero/card non più bloccate come nel pass intermedio

Ancora distante dalla reference:
- spacing header/topbar
- asset fotografici diversi dalla reference
- micro-differenze su logo/search/social alignment

### Lista risorse
Migliorato:
- voce `Novità` attiva nel menu
- breadcrumb leggibile
- hero title/summary più vicini al pattern listing
- fallback immagini predisposto lato JS

Ancora distante dalla reference:
- hero card iniziale non è ancora visivamente equivalente
- wrapping del titolo ancora troppo verticale
- cards sotto fold da rifinire su immagini, spacing e shadow rhythm
- sezione intro ancora troppo “larga” rispetto alla card reference

## Valutazione pratica

- `homepage`: CSS/JS phase continua
- `lista-risorse`: CSS/JS phase continua, ma serve un secondo pass specifico sul page-header listing
- `mappa-sito` e `amministrazione`: non ancora candidabili a parity solo CSS/JS senza un controllo strutturale più severo

## File toccati in questo pass

- `laravel/Themes/Sixteen/resources/css/app.css`
- `laravel/Themes/Sixteen/resources/css/listing-parity.css`
- `laravel/Themes/Sixteen/resources/js/app.js`

## Next recommended pass

1. isolare il blocco hero listing in una classe dedicata per `lista-risorse`
2. confrontare screenshot crop-by-crop per header e page-header
3. estendere lo stesso approccio a `lista-categorie` e `lista-risorse-categorie`
4. non aprire CSS/JS su pagine con drift HTML/body ancora sotto soglia
