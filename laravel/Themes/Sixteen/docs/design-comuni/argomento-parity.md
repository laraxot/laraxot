# Argomento Parity Report

## Scope

- Reference: `https://italia.github.io/design-comuni-pagine-statiche/sito/argomento.html`
- Local target: `http://127.0.0.1:8000/it/tests/argomento`
- Blade route: [laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php](/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php)
- JSON content: [laravel/config/local/fixcity/database/content/pages/tests.argomento.json](/var/www/_bases/base_fixcity_fila5/laravel/config/local/fixcity/database/content/pages/tests.argomento.json)

## Gate Result

La route locale renderizza la pagina, quindi il confronto strutturale e' valido.

Pero' la struttura di `main` non e' ancora abbastanza vicina alla reference per passare onestamente alla fase CSS/JS.

## Body Comparison

Top-level `body` reference e locale sono molto simili come shell generale:

- `div.skiplink`
- `header`
- `main`
- `div#search-modal`
- `footer`

Quindi la cornice generale della pagina esiste.

## Main Comparison

### Reference

Dallo snippet estratto e dallo screenshot la reference contiene queste macro-sezioni top-level in `main`:

1. hero wrapper con immagine di sfondo e card sovrapposta, che ingloba breadcrumb, titolo, testo e colonna destra `Questo argomento e' gestito da`
2. sezione `Contenuti in evidenza`
3. sezione `Esplora argomento`
4. sezione `A cura di`
5. sezione contatti / CTA finale
6. modali o sezioni di supporto finali

Screenshot reference: [reference-argomento.png](/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/design-comuni/screenshots/argomento-parity/reference-argomento.png)

### Local

Nel locale `main` contiene invece solo queste macro-sezioni reali:

1. breadcrumb standalone
2. hero semplice su sfondo bianco
3. sezione `Servizi correlati`
4. sezione `Documenti utili`

Screenshot locale: [local-argomento.png](/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/design-comuni/screenshots/argomento-parity/local-argomento.png)

## Structural Differences That Matter

Le differenze strutturali non sono cosmetiche ma di layout e contenuto:

- la reference usa un hero top-level con immagine background, mentre il locale separa breadcrumb e hero e non ha il layer immagine
- nella reference il breadcrumb sta dentro la hero card; nel locale e' un blocco precedente separato
- nella reference esiste una colonna destra dentro la hero con due card `gestito da`; nel locale manca del tutto
- la reference ha molte piu' sezioni sotto la piega; il locale ne ha solo due
- i blocchi locali `cards-grid` e `links-list` non mappano 1:1 le macro-sezioni della reference
- il titolo e i contenuti sono diversi: `Sport` nella reference contro `Dettaglio argomento` nel locale

## CMS Mapping Verified

JSON locale attuale:

- `breadcrumb`
- `hero`
- `cards-grid`
- `links-list`

Questo spiega il motivo strutturale del mismatch: la composizione CMS della pagina locale non replica la gerarchia della reference.

## Decision

Il gate `90%` non e' soddisfatto a livello di struttura utile di `main`.

Quindi il prossimo passo corretto non e' il CSS/JS, ma prima:

1. riallineare la composizione HTML/blocchi della pagina `argomento`
2. far coincidere le macro-sezioni della reference
3. solo dopo passare a CSS e JS per la parity visiva fine
