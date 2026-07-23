---
title: Segnalazione Crea Step Dati Screenshot Audit 2026-04-28
type: comparison
updated: 2026-04-28
status: active
---

# Segnalazione Crea Step Dati Screenshot Audit 2026-04-28

## Scope

- URL osservata: `http://127.0.0.1:8001/it/tests/segnalazione-crea?step=form.dati-della-segnalazione%3A%3Adata%3A%3Awizard-step`
- Fonte: screenshot runtime fornito dall'utente il `2026-04-28`
- Evidenza file locale recheck: [`../../../../Modules/Fixcity/docs/assets/segnalazione-step-dati-after-fix-2026-04-28-full-recheck.png`](../../../../Modules/Fixcity/docs/assets/segnalazione-step-dati-after-fix-2026-04-28-full-recheck.png)
- Focus tema: parity visuale, spacing, affordance, leggibilita'

## Errori visuali documentati

| ID | Severita' | Errore visuale | Sintomo concreto |
|---|---|---|---|
| SX-SHOT-01 | alta | Sidebar sinistra sproporzionata e vuota | un grande box con cornice verde domina la colonna ma non comunica contenuto utile |
| SX-SHOT-02 | media | Header dell'accordion con stato ambiguo | `INFORMAZIONI RICHIESTE` mostra indicatori doppi (`^` e `-`) invece di un solo segnale di stato |
| SX-SHOT-03 | alta | Vuoto verticale eccessivo nella colonna principale | tra nota obbligatorieta' e sezione `Luogo` c'e' troppo spazio morto |
| SX-SHOT-04 | alta | Campo search con lente sovrapposta al testo | la lente di ingrandimento cade sopra l'inizio di `Cerca un luogo...` invece di avere padding separato |
| SX-SHOT-05 | critica | Mappa con overlay di testo grezzo tipo markup rotto | nell'area mappa compaiono stringhe tecniche sopra i tile, come se un tag/SVG non fosse stato chiuso o renderizzato bene |
| SX-SHOT-06 | alta | Mappa visivamente schiarita, quasi disabilitata | marker visibile ma l'intero widget appare attenuato e poco interattivo |

## Contratti violati o a rischio

- `concepts/segnalazione-crea-header-and-map-visual-regression-contract.md`
- `concepts/segnalazione-map-and-section-spacing-parity.md`
- `concepts/leaflet-map-flicker-visual-contract.md`
- `concepts/theme-owned-wizard-css-parity-rule.md`

## Esito

Lo screenshot non e' compatibile con una parity visuale accettabile per lo step `Dati della segnalazione`. I tre sintomi piu' evidenti sono: overlay testuale tipo markup rotto sopra la mappa, lente di ingrandimento sopra il testo del search input, e mappa schiarita come se fosse disabilitata.

## Piano fix owner-side (tema)

1. correggere il layout della search box (padding-left input e posizione icona) per eliminare clipping visivo della lente;
2. ribilanciare spacing verticale della colonna principale nello step `data` (ridurre spazio morto prima di `Luogo`);
3. garantire contrasto e opacita' della mappa in stato attivo (niente effetto "disabled");
4. semplificare affordance sidebar: stato accordion con un solo indicatore coerente e box compatto quando i contenuti sono minimi;
5. verificare parity con Playwright MCP mediante screenshot before/after sulla stessa URL.

## Implementazione eseguita (owner tema)

- file aggiornato: `../../../resources/css/app.css`
- applicati fix CSS semantici su area wizard:
  - sidebar: rimosso indicatore accordion duplicato (`.accordion-button::after`) e resa piu' compatta/leggibile la lista link;
  - spacing: ridotto gap verticale tra legenda campi obbligatori, heading sezione `Luogo` e contenuto utile;
  - search input: aggiustato padding del campo con icona autocomplete per evitare sovrapposizione testo/lente.
- build runtime eseguita in tema: `npm run build && npm run copy`.

## Verifica richiesta

- recheck visuale before/after su stessa URL/step;
- confermare: nessun overlap nel campo search, sidebar non ambigua, mappa non percepita come area disabilitata.

## Verifica after fix (2026-04-28)

- url verificata: `http://127.0.0.1:8001/it/tests/segnalazione-crea?step=form.dati-della-segnalazione%3A%3Adata%3A%3Awizard-step&v=20260428b`
- screenshot full-page di recheck: [`../../../../Modules/Fixcity/docs/assets/segnalazione-step-dati-after-fix-2026-04-28-full-recheck.png`](../../../../Modules/Fixcity/docs/assets/segnalazione-step-dati-after-fix-2026-04-28-full-recheck.png)

| Check visuale tema | Esito | Nota |
|---|---|---|
| Sidebar con stato accordion non ambiguo | pass | rimosso il doppio indicatore di stato |
| Search input senza clipping icona/testo | pass | padding e posizione icona corretti |
| Spacing verticale sezione `Luogo` | pass | sequenza visiva piu' compatta e leggibile |
| Mappa senza overlay testuale anomalo | pass | il testo grezzo non compare piu' |
| Mappa con opacita'/contrasto da widget attivo | pass | resa coerente, non appare disabilitata |

## Residuo owner-side tema

1. sidebar ancora molto compressa su questa griglia; utile una variante tipografica piu' leggera per colonne strette;
2. possibile micro-tuning dei `line-height` in sidebar per aumentare leggibilita' senza occupare spazio aggiuntivo.
