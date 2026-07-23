# Confronto visivo: `/it` vs Design Comuni «Elenco segnalazioni»

**Data audit:** 2026-05-28  
**Ultimo aggiornamento:** 2026-05-28 (post dev-story: `tickets.json` con 10 feature, tab Filament STORY-065)  
**Locale:** http://127.0.0.1:8000/it  
**Reference:** https://italia.github.io/design-comuni-pagine-statiche/sito/ticket-list.html  
**Story implementazione:** [STORY-058](../../../../docs/stories/STORY-058-it-ticket-list-html-visual-parity.md)  
**Template owner:** `laravel/Themes/Sixteen/resources/views/components/blocks/segnalazioni/layout.blade.php`  
**CMS:** `laravel/config/local/fixcity/database/content/pages/1.json` (`slug: home`)

## Verdetto sintetico

**Parziale — `/it` non è ancora equivalente al reference**, ma il blocco P0 «JSON vuoto» è **risolto** (2026-05-28): `tickets.json` contiene feature GeoJSON → sidebar `map-filter-lit` e marker mappa attesi. Restano gap su conteggio (645 vs ~10), card con immagini, HTML parity, decomposizione blocchi CMS.

## Matrice gap (priorità)

| # | Area | Reference | Locale `/it` | Severità | Come risolvere | Issue / story |
|---|------|-----------|--------------|----------|----------------|---------------|
| G1 | Sidebar filtri desktop | `col-lg-3` + 11 checkbox con conteggi | **Parziale** — `map-filter-lit` se `features.length > 0` | P1 | Più tipologie in JSON / enum fill count 0; parity 11 categorie reference | [base_fixcity_fila5#91](https://github.com/laraxot/base_fixcity_fila5/issues/91), [STORY-053](../../../../docs/stories/STORY-053-ticket-list-filtri-tickets-json.md) |
| G2 | Toolbar risultati | «645 Risultati» + Filtra + Rimuovi filtri | Conteggio da `SegnalazioniFilterViewModel` (≈ `total` JSON) | P1 | Allineare copy CMS `results_count` al live count | STORY-053, STORY-058 |
| G3 | Tab Mappa | Mappa con pin + CTA «Segnala disservizio» | `map-lit` + marker se JSON popolato; tab **Filament** (STORY-065) | P1 | Marker icon parity, `invalidateSize` al switch tab | #91, [module_geo_fila5#4](https://github.com/laraxot/module_geo_fila5/issues/4), STORY-051 |
| G4 | Tab Elenco | ≥3 card accordion + immagini | Stato **vuoto** (`results.empty`) se DB/JSON senza ticket | P0 | Seed ticket visibili + immagini mock in JSON CMS o query | STORY-058, seed comune |
| G5 | «Carica altre» | Pulsante sotto le card | Markup presente; UX inutile se lista vuota | P1 | Paginazione solo dopo G4 | STORY-058 |
| G6 | CTA SPID/CIE | Testo autenticazione + bottone | Copy da `fixcity::segnalazione.map.cta.*`; URL `/it/segnalazione-crea` | P2 | Verificare traduzioni 5 livelli vs testo reference | STORY-055 (i18n) |
| G7 | Rating pagina | `cmp-rating` multi-step | Presente (`cmp-rating`, Alpine `step`) | P2 | Parity ID/classi mancanti (report 77.8% → target 90%) | Prompt [7-4](../prompts/ticket-list/7-4-ticket-list-html-parity.md) |
| G8 | Contatti | Blocco «Contatta il comune» | `section#info-contacts` parziale (1 link in JSON) | P2 | Completare `contacts` in `1.json` come reference | STORY-058 |
| G9 | Layout griglia | Sidebar sinistra + colonna 8 | Senza filtri: colonna unica `col-lg-10` — **layout diverso** | P1 | Conseguenza di G1; non patchare solo CSS | STORY-058 |
| G10 | HTML parity | ~775 nodi reference | Score **76.9%** (sandbox `/it/tests/...`, 2026-04-08) | P1 | `bashscripts/html/compare-html.sh` su `/it` post-fix G1 | [report](../../body-structure-comparison/ticket-list/report.md) |
| G11 | Blocchi CMS monolitico | Partial HBS distinti | Un solo `segnalazioni-layout` | P1 | Decomposizione: heading, 2-col, CTA, rating, contacts; breadcrumb globale | [STORY-062](../../../../docs/stories/STORY-062-ticket-list-cms-blocks-decomposition.md), [theme_sixteen#12](https://github.com/laraxot/theme_sixteen_fila5/issues/12) |
| G12 | Header/footer istituzionale | Slim + center + nav Design Comuni | `<x-section slug="header/footer">` Fixcity | P2 | Story header separata; fuori scope elenco centrale | #22, EPIC header |

## Elementi già allineati (non rifare)

- `GET /it` → 200; nessun `@livewire(TicketList)` ([STORY-059](../../../../docs/stories/STORY-059-it-ticketlist-500-uninitialized-fix.md)).
- `cmp-breadcrumbs`, `cmp-heading`, tab Filament `fi-tabs` (Mappa/Elenco), `accordion`, `modal-categories` (mobile), `cmp-rating`, `info-contacts`.
- Percorso canonico: `pages/index.blade.php` → `<x-page slug="home" />` → blocco `segnalazioni-layout`.

## Evidenza tecnica (2026-05-28)

- File: `public_html/data/tickets.json` — `total: 10`, `features` popolato (GeoJSON).
- ViewModel: `SegnalazioniFilterViewModel` → `getFilterItems()` non vuoto → `map-filter-lit` in sidebar desktop.
- Tab: `<x-filament::tabs>` in `components/blocks/segnalazioni/tabs.blade.php` (STORY-065).

Path JSON: `base_path('../public_html/data/tickets.json')`.

## Piano di remediation (ordine)

1. **Dati:** rigenerare/populate `tickets.json` (≥50 feature come in issue #91) + verificare conteggi filtri = mappa.
2. **Verifica visiva:** screenshot reference vs `/it` @ 375 / 768 / 1280 in questa cartella (`reference-full.png`, `it-full.png` — nomi senza date nel filename; data in frontmatter sotto).
3. **HTML:** rieseguire compare-html su `/it` (non solo sandbox tests).
4. **Marker/popup:** module_geo #4 + STORY-051.
5. **Chiusura:** AC STORY-058 + commento su #91 con link a questo file.

## Screenshot (da aggiornare a mano)

| Viewport | Reference | Locale `/it` |
|----------|-----------|--------------|
| 1280px | *(da catturare)* | *(da catturare)* |
| 768px | *(da catturare)* | *(da catturare)* |
| 375px | *(da catturare)* | *(da catturare)* |

Strumenti: `bashscripts/inspectors/homepage-visual-parity/`, Playwright Sixteen.

## Collegamenti

- [SEGNALAZIONI_ELENCO_ANALISI.md](../SEGNALAZIONI_ELENCO_ANALISI.md) — analisi 2026-04-04 (92% righe, componenti base OK).
- [body-structure-comparison/ticket-list/report.md](../../body-structure-comparison/ticket-list/report.md)
- [Fixcity ticket-list-map-architecture.md](../../../../Modules/Fixcity/docs/wiki/concepts/ticket-list-map-architecture.md)
- [no-pure-livewire-outside-filament-widgets.md](../../../../../../docs/wiki/concepts/no-pure-livewire-outside-filament-widgets.md)
