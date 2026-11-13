---
id: homepage-coordination
title: Coordinamento tra agenti sulle pagine /it/tests
document_type: coordination
status: active
updated: 2026-10-13
type: note
tags: [docs, sixteen, prompts]
qmd: "coordinamento tra agenti sixteen"
---

# Coordinamento tra agenti

Il 2026-10-13 hanno scritto negli stessi file Claude, codex, opencode, qwen e omp, con approcci diversi. Regole per non sovrascriversi
(story [DCP-12](../../bmad/stories/dcp-12-multi-agent-ownership-protocol.story.md)).

## Prima di scrivere (obbligatorio)
```bash
find laravel/Themes/Sixteen laravel/Modules/Fixcity laravel/config -mmin -10 -type f -not -path '*/node_modules/*'
git -C laravel/Themes/Sixteen diff --stat
ps -eo pid,etimes,args | grep -E "claude|codex|opencode|qwen|omp" | grep -v grep
```
Modifiche ancorate e idempotenti (aggiungere se manca, mai riscrivere un file intero), backup nello scratchpad, rilettura dopo.
Una sola build alla volta: `npm run build` nel tema (circa 11 secondi); il manifest si rilegge dopo, non si assume.

## Stato dei file contesi (2026-10-13 sera)
- `components/blocks/homepage.blade.php`, `blocks/search-results/design-comuni.blade.php`, partial `blocks/partials/dc-*`, `tests.homepage.json`, `tests.risultati-ricerca.json`,
  `resources/css/design-comuni/*`, `resources/js/theme/design-comuni-home.js`, `postcss/isolate-legacy-important.cjs`: sessione Claude, rilasciati.
- Il vecchio `blocks/search-results/default.blade.php` non e' piu' usato (la selezione e' la chiave `view` del JSON); non cancellato.
- **Vietato**: regole a mano con `.dc-page` nel selettore (fuori da `design-comuni/overrides.css`), nuovi `*-parity.css`, `!important` nuovi, `id` copiati dal riferimento che il layout ha gia'.
  Le toppe di questo tipo trovate in `search-results-parity.css` e in fondo ad `app.css` sono state rimosse (backup nello scratchpad della sessione).

## ATTENZIONE: conflitto su risultati-ricerca (2026-10-13, 08:30)
Un altro agente ha riportato nel JSON del tenant `tests.risultati-ricerca.json` la chiave `view` su `search-results.default` e sta modificando
`blocks/search-results/default.blade.php` e `blocks/listing/search-results.blade.php` (percorso Tailwind a mano). Misura di confronto (stesso comando, `<main>` a 1440 px):
- `design-comuni.blade.php` (da JSON, CSS generato, `postcss/isolate-legacy-important.cjs`): **99,86%**, altezze identiche, 99,6% a 375 px.
- `default.blade.php`: al momento 500 (`Undefined variable $contactLinks`); prima delle modifiche era 83-93% sulla pagina intera.
La chiave `view` e' una sola per pagina: **non cambiarla senza rimisurare con `bashscripts/tools/visual/compare-screenshots.mjs --selector main`** e scrivere qui i numeri.
Il layout `components/layouts/app.blade.php` è la shell pubblica unica: usa header/footer/modale Design Comuni tramite sezioni con `tpl="design-comuni"`. Il valore CMS `layout: design-comuni` non autorizza un layout Blade omonimo.

## Layout e scope (2026-10-13 sera) — non cambiare senza rimisurare
- `components/layouts/app.blade.php`: shell canonica del front office e delle pagine reali; header/footer/modale/skiplink Design Comuni, `<body>` plain.
- `components/layouts/test.blade.php`: shell eventuale per le pagine di prova che la dichiarano esplicitamente; non è la shell pubblica.
- Scope CSS: `body:has(.page-content[data-dc])` (stesso valore in `postcss/isolate-legacy-important.cjs` e in `extract-used-css.mjs --scope`). Niente `class="dc-page"`, niente wrapper: non servono a nessuno stile.
- Misure: con scope solo su `<main>` header 95,5% e footer 76,6%; con scope sul body header 100% e footer 100%.

## ATTENZIONE: eventi in parallelo (2026-10-13 11:45)
Un altro agente lavora su `sito/eventi.html` (story `bmad/stories/eventi-visual-parity.story.md`, evidenze in `bmad/evidence/eventi-*`, `blocks/events-calendar/default.blade.php`) e ha creato `config/local/fixcity/database/content/pages/eventi.json` con lo **stesso slug** `tests.eventi` di `tests.eventi.json`: due record con lo stesso slug = `CMS content collision` (HTTP 500) per tutti. Il file non canonico e' stato spostato (backup nello scratchpad della sessione Claude); il nome canonico e' `tests.eventi.json` (regola in `Modules/Cms/docs/json-content-slug-uniqueness.md`).
Metodo usato qui: un solo blocco `blocks/events-page/design-comuni.blade.php` guidato da JSON + CSS generato (non `events-calendar/default`, non Tailwind a mano). Confrontare con i numeri di `bashscripts/tools/visual/parity-gate.sh` prima di cambiare approccio.

## Contratto non negoziabile
Le pagine `/it/tests/*` sono **dato**: Folio generico `[slug]` + `<x-page>` + blocchi/sezioni Blade + JSON `tests.<slug>.json`.
Vietato: file Folio o layout per singola pagina, testo nelle viste, Bootstrap Italia a runtime, nuovi `*-parity.css` con `!important`.
Fonte: `docs/layout-runtime-contract.md`, `docs/architecture/tailwind-alpine-philosophy.md`.

## Proprieta' (aggiornare a inizio e fine lavoro)
| Cosa | Proprietario | Stato |
|---|---|---|
| `components/blocks/homepage.blade.php`, `tests.homepage.json`, `resources/css/design-comuni/*`, `resources/js/theme/design-comuni-home.js` | sessione Claude 2026-10-13 | rilasciato a fine giornata, riprendere con DCP-04 |
| `components/sections/header/*`, `components/sections/footer/*`, `sections/search-modal` | libero | DCP-05, DCP-06 |
| `blocks/search-results/*`, `tests.risultati-ricerca.json` | libero | DCP-07 |
| `app.css` e file `*-parity.css` | libero, un solo agente per volta | DCP-03 |

Se due approcci confliggono, si confrontano con i numeri del gate (`bashscripts/tools/visual/`) e il risultato si scrive qui.


## tickets/create (DCP-35, 2026-10-13 12:57)
Tabella dei file contesi e protocollo: `Modules/Fixcity/docs/stories/dcp-35-tickets-create-privacy-dati-coordinamento.story.md`.
Passi 3 e 4: DCP-33 e DCP-34. Aggiornare la tabella della story prima e dopo ogni lavoro; prossimo numero di decisione libero: D18 (D15 e D16 doppie, D17 e' `tickets-create-richiede-auth`).

Blocco id DCP-40..49 riservato alle story tickets/create di DCP-35 (DCP-40 radice Volt ... DCP-46 passo 1). Chi crea una nuova story DCP usi 50 o piu', oppure controlli `ls` su Fixcity, Sixteen, Cms e Xot prima.
