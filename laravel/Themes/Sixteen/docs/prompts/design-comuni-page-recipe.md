---
id: design-comuni-page-recipe
title: "Ricetta per replicare una pagina Design Comuni in /it/tests (guidata da JSON, Tailwind + Alpine)"
document_type: operational_prompt
category: parity
status: active
source_of_truth: true
created: 2026-10-13
qmd: "ricetta pagina design comuni parity json blocco gate proprieta file agenti paralleli bmad"
type: note
updated: 2026-10-13
tags: [docs, sixteen, prompts]
---

# Ricetta per replicare una pagina Design Comuni

Obiettivo per ogni pagina di `https://italia.github.io/design-comuni-pagine-statiche/` (cartelle `sito/` e `servizi/`): `http://192.168.1.40:8000/it/tests/<slug>`
con **HTML body >= 90%** (rigoroso, valori degli URL inclusi; se non si arriva, riportare anche la misura con URL normalizzati e perche') e **pixel >= 97%
a 1920, 1440, 992, 768 e 375 px con altezze identiche**, `<body>` plain, nessun Bootstrap Italia a runtime, nessun testo cablato nelle viste.
Metodo gia' provato su homepage, risultati-ricerca, appuntamento-02-data-orario, eventi, evento-dettaglio, accesso-servizio: vedi
`docs/wiki/concepts/design-comuni-parity-method.md` e le story `docs/bmad/stories/2026-10-13-dcp-14*`, `dcp-15*`, `dcp-17*`.

## Contratto (non negoziabile; fonti: docs/layout-runtime-contract.md, docs/html-parity-body-policy.md, decisione 2026-10-13)
- Una pagina e' **dato**: `pages/tests/[slug].blade.php` (non toccare) -> `layouts/design-comuni` (non toccare) -> `<x-page>` -> **blocco Blade** -> **JSON** `tests.<slug>.json`.
- Mai: file Folio o layout per singola pagina, `class="dc-page"`/wrapper inventati, `<style>` o CSS a mano, `!important`, `x-data` nel markup, testo cablato nelle viste,
  `<script>` del riferimento, `app.js`/CSS/JS condivisi modificati.
- Il markup del blocco **segue quello del riferimento** (stesse classi, stessi tag): i suoi stili vengono estratti dal CSS originale (`extract-used-css.mjs`) e isolati dal CSS legacy da un
  plugin PostCSS. Lo scope e' `body:has(.page-content[data-dc])`. Per questo la parity visiva arriva da sola se il markup e' fedele.

## Passi
1. **Baseline**: `bashscripts/tools/visual/parity-gate.sh <ref-url> http://192.168.1.40:8000/it/tests/<slug> --stub-images 'picsum\.photos|via\.placeholder\.com' --fail-if-contains '|---LINE:'`.
   Controllare se esiste gia' `tests.<slug>.json` (tenant: `laravel/config/local/fixcity/database/content/pages/`, modulo: `laravel/Modules/Fixcity/config/database/content/pages/`) e che **non ci siano due file con lo stesso `slug`** (due record = HTTP 500 per tutti).
2. **Struttura**: `node bashscripts/tools/visual/compact-markup.mjs <ref-url> --tag main --out <file>` e leggere il file (Read). I comportamenti: `bashscripts/tools/visual/dom-diff-after-click.mjs <ref-url> 1440 '<css>'`.
3. **JSON**: estrarre i dati dal riferimento con uno script (Python, sul markup con `>\s+<` -> `><` e spazi collassati), un blocco per sezione, e controllare i conteggi. Nome canonico `tests.<slug>.json`,
   stessa struttura dei file esistenti (`id`, `slug: "tests.<slug>"`, `parent_slug: "tests"`, `title`, `layout: "test"`, `content_blocks.it: [{type, data:{view, ...}}]`). Scrivere **tenant e modulo**. Salvare prima un backup del file esistente fuori dal repo.
4. **Blocco**: `laravel/Themes/Sixteen/resources/views/components/blocks/<famiglia>/design-comuni.blade.php`, con `@props(['data' => []])`, helper `$d`, `@class`, cicli sui dati, `@switch` per tipi di sezione. Esempi da imitare:
   `blocks/event-detail/design-comuni.blade.php`, `blocks/events-page/design-comuni.blade.php`, `blocks/appointment-step/design-comuni.blade.php`, `blocks/access-page/design-comuni.blade.php`.
   Partial condivisi **da includere, non modificare**: `blocks/partials/dc-rating.blade.php`, `blocks/partials/dc-contacts.blade.php` (opzione `contacts.layout = 'offset'`). Se serve un partial diverso, crearlo dentro la propria cartella famiglia.
   Immagini locali del riferimento (`../assets/images/x`): scaricarle da `https://italia.github.io/design-comuni-pagine-statiche/assets/images/x` in `laravel/Themes/Sixteen/public/design-comuni/assets/images/` (mai file da 0 byte) e usare `asset('themes/Sixteen/design-comuni/assets/images/x')`.
   Immagini esterne (picsum, placeholder): lasciare lo stesso URL del riferimento (cosi' anche l'HTML coincide).
5. **Misura l'HTML** (non serve CSS): `node bashscripts/tools/visual/compare-body-html.mjs <ref-url> <local-url>` stampa rigorosa e URL normalizzati. Correggere fino a >= 90 rigorosa o a una forbice spiegabile solo da valori di URL.
6. **Registrare la pagina** (append, non riscrivere): una riga in `laravel/Themes/Sixteen/scripts/design-comuni-pages.txt` (percorso dopo il dominio, es. `sito/eventi.html`) e, se servono stati dopo un clic (menu aperti, accordion, radio, tab),
   righe `LARGHEZZA|selettore` in `design-comuni-states.txt`. **Non eseguire** `regenerate-design-comuni-css.sh` ne' `npm run build`: li lancia l'orchestratore, uno alla volta, dopo che tutti hanno scritto.
7. **Verifica visiva** (dopo la rigenerazione dell'orchestratore, o se il CSS contiene gia' le regole): `parity-gate.sh` completo; comportamenti con `compare-screenshots.mjs --selector body --click '<css>'` (pagina intera, non un ritaglio: un ritaglio puo' avere 1 px di larghezza in piu' per arrotondamenti di 0,016 px);
   stili calcolati con `compare-computed.mjs` e la regola vincente con `explain-style.mjs` (`--state hover`). Un valore alto con altezze diverse e' sospetto.
8. **Story BMAD**: una per famiglia, `laravel/Themes/Sixteen/docs/bmad/stories/2026-10-13-dcp-<N>-<famiglia>.story.md` (N assegnato), con baseline, cosa e' stato fatto, risultati **misurati** (incolla l'output del gate), differenze note, come si controlla. Mai dichiarare "fatto" senza output del gate.

## Proprieta' dei file (piu' agenti lavorano insieme, piu' altri agenti esterni)
Si possono creare/modificare SOLO: i JSON `tests.<slug>.json` delle proprie pagine (tenant e modulo), `blocks/<propria-famiglia>/**`, le immagini nuove in `public/design-comuni/assets/images/`, la propria story, righe in coda ai due manifest.
**Vietato**: `resources/css/**` (anche `components.css`, generato), `resources/js/**`, `postcss/**`, `vite.config*`, `postcss.config.js`, layouts, `sections/**` (header, footer, modale), `blocks/partials/**`, `pages/tests/**`, le pagine di altri agenti, `MEMORY.md`, indici, epic, `00-COORDINATION.md`.
Se serve una modifica condivisa (nuovo comportamento JS, partial, regola CSS che non si ottiene dal riferimento, stato non previsto): **non farla**; descriverla in modo preciso nel report (file, comportamento, misura che la giustifica).
Prima di scrivere: `find <dir> -mmin -10`, `git diff` del file; modifiche ancorate; non cancellare file altrui; se per il tuo slug esiste un altro file con lo stesso `slug`, spostalo fuori dal repo con backup e dichiaralo.
Dopo un 500 qualsiasi: `cd laravel && php artisan view:clear` (l'exception renderer lascia `|---LINE:n---|` nella cache).

## Report finale (formato)
Per pagina: slug, file creati, HTML rigorosa / normalizzata, pixel per larghezza con altezze, comportamenti verificati (valore), differenze note; poi: righe aggiunte ai manifest, modifiche condivise richieste, story scritta, cose non fatte e perche'.

## Comportamenti condivisi gia' disponibili (non richiederli, usali)
Montati per selettore da `mountDesignComuniComponents` (`resources/js/theme/design-comuni-home.js`), il markup resta identico al riferimento:
- caroselli Splide `[data-bs-carousel-splide]` e `.carousel-4-card` (opzioni per classe: `it-calendar-wrapper`, `it-carousel-landscape-abstract-three-cols`, `carousel-4-card`);
- scroll-spy con barra di avanzamento `[data-bs-navscroll]`; accordion/collapse (`data-bs-toggle="collapse"` con `data-bs-target` oppure `href="#id"`); menu a tendina (`data-bs-toggle="dropdown"`, posizionati come Popper);
- rating a passi `.cmp-rating#rating` (partial `dc-rating`, colonna opzionale con `feedback.col_class`); contatti (`dc-contacts`, `contacts.layout = 'offset'`);
- validazione form `#justValidateForm`, `#justValidateMulta` (classi `is-invalid`, etichette `form-feedback`); `.saveBtn` (messaggio 3 s) e `.radio-input`/`.radio-card` (`has-border-green`);
- modali `.modal.fade` con backdrop (`data-bs-toggle="modal"`), menu mobile `[data-bs-toggle="navbarcollapsible"]`, focus del mouse (`focus--mouse`).
Layout: `layouts/design-comuni` (con header, footer, modale di ricerca) per `pages/tests/[slug]`; `layouts/design-comuni-bare` (solo `<main>`) per il catalogo `pages/tests/index`.
Lo sweep del gate su piu' pagine: `bashscripts/tools/visual/parity-sweep.sh <manifest> --jobs 3 --out /tmp/dir [--only 'regex']`.
Per capire un comportamento nuovo del riferimento: `dom-diff-after-click.mjs <ref-url> <larghezza> '<css>'` e la logica in `assets/script/scripts.js` del riferimento (https://italia.github.io/design-comuni-pagine-statiche/assets/script/scripts.js).
