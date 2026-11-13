---
id: "sixteen-fo-tests-segnalazione-remaining-parity"
type: "story"
title: "Tests DesignComuni: remaining segnalazione wizard + elenco + area-personale parity"
status: todo
parent: STORY-703
tags: [design-comuni, segnalazione, wizard, tailwind, alpine, parity, tests-pages, bmad]
design_ref: "https://italia.github.io/design-comuni-pagine-statiche/sito/"
created: 2026-10-13
updated: 2026-10-13
supersedes_note: "STORY-701 e' marcata completed ma le metriche 2026-10-13 mostrano che 4 pagine sono ancora sotto il gate 90%."
---

# Story 703 — Remaining segnalazione wizard parity

## Why this story exists

STORY-701 (`701-tests-segnalazione-wizard-parity.story.md`) e' marcata
`status: completed`, ma la misura body-tag parity del 2026-10-13 dimostra
che quattro pagine sono ancora ben sotto il gate del 90%:

| URL local | Reference | ref tags | live tags | parity |
|---|---|---:|---:|---:|
| `/it/tests/segnalazione-area-personale` | `segnalazione-area-personale.html` | 986 | 56 | **6%** |
| `/it/tests/segnalazione-dettaglio` | `segnalazione-dettaglio.html` | 850 | 248 | **29%** |
| `/it/tests/segnalazioni-elenco` | `segnalazioni-elenco.html` | 775 | 354 | **46%** |
| `/it/tests/segnalazione-02-dati` | `segnalazione-02-dati.html` | 389 | 313 | **80%** |

STORY-701 va corretta a `status: in-progress` (o `partial`) per non
falsare lo stato del backlog.

## Root cause (per pagina)

- **area-personale (6%)**: il blocco corrente e' una shell quasi vuota;
  manca l'intera area personale (profilo, stato segnalazioni, azioni,
  menu laterale) che la reference renderizza in ~986 tag.
- **dettaglio (29%)**: mancano timeline dello stato, allegati, mappa,
  dettagli ente, azioni cittadino, rating.
- **elenco (46%)**: manca la lista completa con filtri, ordinamento,
  stati e paginazione; restano poche righe.
- **02-dati (80%)**: vicina al gate; mancano campi/label e validazione
  per ~76 tag.

## Tasks

- [ ] Correggere `701-...story.md` a `status: in-progress`.
- [ ] `segnalazione-area-personale`: ricostruire il blocco
      `segnalazione-area-personale/default.blade.php` + JSON
      `tests.segnalazione-area-personale.json` sulla reference.
- [ ] `segnalazione-dettaglio`: completare timeline, allegati, mappa,
      dettagli, azioni in `segnalazione-dettaglio` block + JSON.
- [ ] `segnalazioni-elenco`: completare filtri, ordinamento, stati,
      paginazione in `segnalazioni-elenco/default.blade.php` + JSON.
- [ ] `segnalazione-02-dati`: aggiungere i ~76 tag mancanti (campi,
      label, helper, validazione).
- [ ] Ogni blocco: Tailwind utilities + SVG inline; nessuna classe
      Bootstrap utility (`d-none`, `d-md-block`, `btn-primary`);
      nessun riferimento `bootstrap-italia`.

## Acceptance criteria

1. Ciascuna pagina: body HTML parity >= 90% e visual parity >= 97%
   (controlled) a 375/768/1440 px.
2. HTTP 200, zero errori console, zero immagini mancanti, zero overflow.
3. Zero riferimenti `bootstrap-italia`; zero classi Bootstrap utility.
4. WCAG 2.1 AA pulito (AxeBuilder zero violazioni).

## Verification

```bash
npm run build   # da laravel/Themes/Sixteen
# per pagina: confronto body HTML + screenshot controlled vs reference
node bashscripts/tools/visual/compare-body-html.mjs \
  "https://italia.github.io/design-comuni-pagine-statiche/sito/<ref>.html" \
  "http://192.168.1.40:8000/it/tests/<slug>" --json
```

## Definition of done

Metriche per pagina allegate alla story; 701 riaperta e poi richiusa
solo quando tutte e quattro le pagine superano il gate.
