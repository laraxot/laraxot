# Design Comuni Page Census

## Scope
Initial BMAD-style census of every public page exposed by the official Design Comuni static pages index.

Source index:
- <https://italia.github.io/design-comuni-pagine-statiche/>

Goal:
- enumerate every page that must be replicated in the local `tests.*` namespace
- identify recurring page structures before implementing more conversions
- derive reusable block families instead of page-specific Blade files

## Canonical Mapping Rule
Each source page maps to one local slug:
- source: `sito/<page>.html`
- local route: `/it/tests/<page>`
- local content file: `laravel/config/local/fixcity/database/content/pages/tests.<page>.json`
- local renderer: `resources/views/pages/tests/[slug].blade.php`

## Complete Page Inventory

### Generali
- `homepage.html` -> `tests.homepage`
- `domande-frequenti.html` -> `tests.domande-frequenti`
- `risultati-ricerca.html` -> `tests.risultati-ricerca`
- `argomenti.html` -> `tests.argomenti`
- `argomento.html` -> `tests.argomento`
- `lista-risorse.html` -> `tests.lista-risorse`
- `lista-categorie.html` -> `tests.lista-categorie`
- `lista-risorse-categorie.html` -> `tests.lista-risorse-categorie`
- `mappa-sito.html` -> `tests.mappa-sito`

### Amministrazione
- `amministrazione.html` -> `tests.amministrazione`
- `documenti-dati.html` -> `tests.documenti-dati`

### Novità
- `novita.html` -> `tests.novita`
- `novita-dettaglio.html` -> `tests.novita-dettaglio`

### Servizi
- `servizi.html` -> `tests.servizi`
- `servizi-categoria.html` -> `tests.servizi-categoria`
- `servizio-dettaglio.html` -> `tests.servizio-dettaglio`

### Vivere il Comune
- `eventi.html` -> `tests.eventi`
- `evento-dettaglio.html` -> `tests.evento-dettaglio`

### Prenotazione Appuntamento
- `appuntamento-01-ufficio.html` -> `tests.appuntamento-01-ufficio`
- `appuntamento-01-ufficio-luogo.html` -> `tests.appuntamento-01-ufficio-luogo`
- `appuntamento-02-data-orario.html` -> `tests.appuntamento-02-data-orario`
- `appuntamento-03-dettagli.html` -> `tests.appuntamento-03-dettagli`
- `appuntamento-04-richiedente.html` -> `tests.appuntamento-04-richiedente`
- `appuntamento-04-richiedente-autenticato.html` -> `tests.appuntamento-04-richiedente-autenticato`
- `appuntamento-05-riepilogo.html` -> `tests.appuntamento-05-riepilogo`
- `appuntamento-06-conferma.html` -> `tests.appuntamento-06-conferma`

### Richiesta Assistenza
- `assistenza-01-dati.html` -> `tests.assistenza-01-dati`
- `assistenza-02-conferma.html` -> `tests.assistenza-02-conferma`

### Segnalazione Disservizio
- `segnalazione-dettaglio.html` -> `tests.segnalazione-dettaglio`
- `segnalazione-01-privacy.html` -> `tests.segnalazione-01-privacy`
- `segnalazione-02-dati.html` -> `tests.segnalazione-02-dati`
- `segnalazione-03-riepilogo.html` -> `tests.segnalazione-03-riepilogo`
- `segnalazione-04-conferma.html` -> `tests.segnalazione-04-conferma`
- `segnalazione-area-personale.html` -> `tests.segnalazione-area-personale`
- `ticket-list.html` -> `tests.ticket-list`

## Reusable Block Families
The repeated structures observed across the census suggest these universal block families.

### Global sections
- `header/*`
- `footer/*`
- `navigation/*`
- `breadcrumb/*`

### List pages
Used by `novita`, `argomenti`, `lista-risorse`, `lista-categorie`, `lista-risorse-categorie`, `eventi`, `servizi`, `amministrazione`.
- `listing.page-header`
- `listing.featured-cards`
- `listing.search-results`
- `listing.pagination`
- `listing.category-grid`
- `listing.mixed-grid`

### Detail pages
Used by `argomento`, `novita-dettaglio`, `servizio-dettaglio`, `evento-dettaglio`, `segnalazione-dettaglio`.
- `detail.page-header`
- `detail.meta`
- `detail.content`
- `detail.sidebar-links`
- `detail.related-items`

### Feedback and support
Used by many content pages.
- `feedback.survey`
- `search.support-links`
- `contact.support-grid`

### Service flow steps
Used by appointment, assistance, and service transaction pages.
- `flow.stepper`
- `flow.form-section`
- `flow.summary`
- `flow.confirmation`
- `flow.personal-data`
- `flow.choice-list`

### Civic/admin content
Used by administration and documents pages.
- `administration.sections`
- `administration.documents`
- `administration.people-list`

### Geo and map-driven pages
Used by `ticket-list` and location-oriented pages.
- `map.results`
- `map.filters`
- `map.marker-list`

## Frequency Hotspots
Highest-value reusable clusters to build first:
1. `header`, `footer`, `breadcrumb`
2. `listing.page-header`
3. `listing.featured-cards`
4. `listing.search-results`
5. `listing.pagination`
6. `feedback.survey`
7. `search.support-links`
8. `flow.*` family

## Conversion Priority
Recommended implementation order for the next waves:
1. `lista-risorse`
2. `lista-categorie`
3. `lista-risorse-categorie`
4. `novita`
5. `eventi`
6. `argomento`
7. service-flow pages
8. disservice-flow pages

## Governance Notes
- one dynamic Folio page for all test routes: `pages/tests/[slug].blade.php`
- JSON stores content, not page-specific Blade layouts
- block view paths must stay universal: `pub_theme::components.blocks.<type>.<blade>`
- no page-scoped block types such as `tests.lista-risorse`
- prefer families that can serve at least two pages before introducing a new type

## Cross References
- [Theme Index](README.md)
- [CMS README](../../../Modules/Cms/docs/README.md)
- [Content Blocks System](../../../Modules/Cms/docs/content-blocks-system.md)
