# Argomenti HTML Analysis

## Scope

Confronto tra il `body` della reference Design Comuni e il `body` della pagina locale `http://127.0.0.1:8000/it/tests/argomenti`, escludendo gli script.

- Reference: `https://italia.github.io/design-comuni-pagine-statiche/sito/argomenti.html`
- Local: `http://127.0.0.1:8000/it/tests/argomenti`
- Blade sorgente: `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php`
- JSON contenuti: `laravel/config/local/fixcity/database/content/pages/tests.argomenti.json`
- Data: 2026-04-03

## Artefatti

- HTML reference: `./argomenti-parity/reference-page.html`
- HTML local: `./argomenti-parity/local-page.html`
- Body reference: `./argomenti-parity/reference-body.html`
- Body local: `./argomenti-parity/local-body.html`
- Screenshot reference: `./screenshots/argomenti/reference-full.png`
- Screenshot local: `./screenshots/argomenti/local-full.png`

## Verdettto Strutturale

La struttura non e ancora al 90%.

Stima attuale: `~82-85%`.

Il corpo centrale della pagina e vicino alla reference per questi blocchi:
- hero principale `Argomenti`
- sezione `In evidenza`
- sezione `Esplora per argomento`

I delta che impediscono la soglia del 90% sono strutturali, non solo CSS:
- manca il blocco breadcrumb all'inizio di `<main>`
- il `main` locale parte direttamente con il hero
- la sezione feedback locale usa un componente rating generico a 5 stelle, mentre la reference usa il blocco Design Comuni con struttura diversa
- la sezione `Contatta il comune` non risulta renderizzata nella pagina locale corrente
- l'altezza complessiva della pagina resta inferiore alla reference (`3715px` vs `4142px` negli screenshot full page)

## Differenze Principali

### 1. Apertura di `<main>`

Reference:
- `<main>`
- primo blocco: `container#main-container`
- dentro: `cmp-breadcrumbs`
- solo dopo arriva il hero

Local:
- `<main id="main-container" data-page="argomenti">`
- primo blocco: `cmp-hero`
- breadcrumb assente

Impatto:
- ordine dei nodi diverso
- anchor `#main-container` applicata al `main` e non al container breadcrumb come nella reference

### 2. Breadcrumb

Reference:
- `div.container#main-container`
- `div.row.justify-content-center`
- `div.col-12.col-lg-10`
- `div.cmp-breadcrumbs`
- `nav.breadcrumb-container`
- `ol.breadcrumb`

Local:
- nessun breadcrumb renderizzato dentro `main`

Impatto:
- delta strutturale immediato sopra il hero
- delta visuale nel top fold

### 3. Hero

Reference e local sono abbastanza vicini:
- `container`
- `row justify-content-center`
- `col-12 col-lg-10`
- `cmp-hero`
- `section.it-hero-wrapper`

Nota:
- il local usa `hero.default` tramite `hero/argomenti.blade.php`
- il wrapper extra `div.cmp-hero` sul `main` locale e tollerabile, ma non e identico 1:1

### 4. In evidenza

Reference:
- `container py-5`
- `h2.title-xxlarge`
- `row g-4`
- 3 card `it-grid-item-wrapper it-grid-item-overlay`

Local:
- stessa struttura di alto livello
- i nodi principali coincidono quasi completamente

Nota:
- questa e la parte piu allineata della pagina

### 5. Esplora per argomento

Reference:
- `div.container.py-5#argomento`
- `h2.title-xxlarge`
- `div.row.g-4`
- card `cmp-card-simple card-wrapper ... > .card.shadow-sm.rounded > .card-body`

Local:
- stessa struttura di alto livello
- stesso pattern di card
- dataset coerente con la reference mock

Nota:
- questa sezione e gia abbastanza vicina alla reference anche sul piano strutturale

### 6. Feedback

Reference:
- blocco Design Comuni con heading `data-element="feedback-title"`
- struttura piu articolata, non solo stelle
- semantica e classi differenti

Local:
- `div.it-rating-section`
- wrapper centrale con Alpine `x-data`
- rating a 5 stelle custom

Impatto:
- mismatch strutturale medio-alto
- non e un problema di pura formattazione

### 7. Contatti

Reference:
- sezione `Contatta il comune` presente dentro il `main`

Local:
- dal render corrente non emerge il blocco `Contatta il comune`
- il componente `contact/default.blade.php` dipende da `links`; il JSON attuale passa solo `title`

Impatto:
- sezione assente nel body locale
- delta strutturale e visivo alto nel fondo pagina

## Diagnosi Sorgenti

### Blade routing pagina

`laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php`
- non contiene struttura specifica di pagina
- delega a `<x-page ... />`
- quindi la vera struttura dipende dai blocchi CMS/JSON

### JSON pagina

`laravel/config/local/fixcity/database/content/pages/tests.argomenti.json`
- definisce `hero`, `topics-highlight`, `topics-grid`, `feedback-rating`, `contact-info`
- non definisce nessun blocco breadcrumb
- il blocco contact non contiene `links`, quindi il template attuale non renderizza niente

### Blocchi Blade coinvolti

- `resources/views/components/blocks/hero/argomenti.blade.php`
- `resources/views/components/blocks/hero/default.blade.php`
- `resources/views/components/blocks/topics-highlight.blade.php`
- `resources/views/components/blocks/topics-grid/default.blade.php`
- `resources/views/components/blocks/feedback/rating.blade.php`
- `resources/views/components/blocks/contact/default.blade.php`
- `resources/views/components/blocks/breadcrumb/default.blade.php`

## Priorita di Fix

### Prima della parity CSS/JS

1. Ripristinare breadcrumb nel `main` tramite blocco JSON o composizione equivalente.
2. Rendere effettiva la sezione `Contatta il comune` passando `links` coerenti o adeguando il blocco contact.
3. Decidere se il feedback deve restare custom o replicare piu fedelmente la struttura reference.

### Dopo la soglia 90%

1. Rifinitura CSS del top fold.
2. Rifinitura spacing e altezze delle card `In evidenza`.
3. Parity visuale di `topics-grid`.
4. Polishing della sezione feedback/contacts.

## Conclusione

`argomenti` non e ancora pronto per un pass finale solo CSS/JS.

La parte centrale della pagina e buona, ma mancano due blocchi strutturali rilevanti (`breadcrumb` e `contacts`) e il feedback non replica abbastanza la reference. Il prossimo passo corretto e completare la struttura via JSON/blade fino ad almeno ~90%, poi fare il parity pass visuale nel tema `Sixteen` con CSS/JS e successivo `npm run build` + `npm run copy`.

## Update Post Fix

Aggiornamento eseguito il 2026-04-03 dopo il primo pass di riallineamento struttura + parity CSS/JS.

Cambi applicati:
- aggiunto blocco breadcrumb nel JSON pagina
- sostituito il feedback con `pub_theme::components.blocks.feedback.faq-rating`
- aggiunto blocco contatti con `pub_theme::components.blocks.contacts.faq`
- aggiunto hook body `dc-argomenti-parity` in `resources/js/app.js`
- aggiunte rifiniture page-scoped in `resources/css/argomenti-parity.css`
- eseguiti `npm run build`, `npm run copy`, `php artisan view:clear`, `php artisan optimize:clear`

Bundle finale distribuito:
- CSS: `app-DJvAZWEh.css`
- JS: `app-CHg7vRlD.js`

Stato dopo il fix:
- breadcrumb presente nel `main`
- feedback presente con heading `data-element="feedback-title"`
- contatti presenti con `Contatta il comune` e `Problemi in città`
- pagina locale serve il bundle finale corretto

Valutazione aggiornata:
- la soglia strutturale e ora sufficientemente vicina alla reference per lavorare principalmente di CSS/JS
- il delta residuo piu evidente e nel blocco contatti/fondo pagina, che visivamente risulta ancora troppo alto rispetto alla reference
- gli screenshot finali di lavoro sono `./screenshots/argomenti/reference-full.png` e `./screenshots/argomenti/local-full-after.png`
