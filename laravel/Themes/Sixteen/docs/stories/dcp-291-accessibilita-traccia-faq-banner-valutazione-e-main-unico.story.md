---
id: dcp-291
title: "DCP-291: accessibilita e responsive piccoli ma bloccanti, traccia, FAQ, banner cookie, valutazione e main unico"
type: story
epic: go-live-front-office
status: in-progress
priority: P1
owner: theme:Sixteen
module: Sixteen
created: 2026-10-26
updated: 2026-10-26
qmd: "dcp 291 accessibilita traccia campo codice 16 px input-group width 1% badge stato contrasto FAQ accordion focus invisibile main-container id duplicato skip link banner cookie gdpr-cookiebar valutazione rating FO-5-01 FO-5-04 FO-5-05 FO-5-06 FO-5-17 FO-5-18 FO-5-20 FO-5-21"
related: [dcp-143, dcp-151, dcp-153, dcp-149, dcp-186, dcp-268, dcp-286, dcp-287, dcp-288, dcp-289, dcp-290]
tags: [accessibilita, responsive, wcag, traccia, faq, cookiebar, rating, main, skip-link, front-office, bmad]
---

# DCP-291: accessibilita e responsive piccoli ma bloccanti, traccia, FAQ, banner cookie, valutazione e main unico

## Fase BMAD

Dev (TDD): story prima, poi per ogni punto misura nel browser, test rosso, codice, test verde, nuova misura.
Origine: audit front office FO-5 del 2026-10-26 (sezione 3 dell'audit a11y e responsive). Ogni riscontro dell'audit e' DA RIVERIFICARE: sotto, ogni fatto e' marcato
MISURATO (eseguito da me, con l'output) o DEDOTTO (letto dal codice, non eseguito).

## Stato

in-progress. Nessun commit, nessuna migrazione, nessuna dipendenza nuova, nessuna build del tema (la coordina il coordinatore).
Id lock: w3f.

## Ambito

1. FO-5-01 campo del codice di tracciamento largo 16 px sotto 576 px.
2. FO-5-18 contrasto del badge di stato in traccia.
3. FO-5-04 focus invisibile sull'accordion FAQ, FO-5-21 titoli e messaggio dei risultati del filtro.
4. FO-5-06 `main` unico con id e tabindex, id duplicati, `main` annidati nella 404.
5. FO-5-05 banner cookie che copre il focus, lo zoom 400% e la stampa.
6. FO-5-17 valutazione "Quanto sono chiare le informazioni?".
7. FO-5-20 colori forzati e FO-5-13 stepper del wizard (solo se il file non e' lockato da DCP-286).

Fuori ambito: header (DCP-288), footer (DCP-289), wizard e conferma (DCP-286), dettaglio e area personale (DCP-287), login, registrazione e reset (DCP-290),
mappa e popup Leaflet, commenti. Il salvataggio del voto (B-05) richiede schema o decisione: non e' qui.

## Fatti misurati prima di toccare

Sezioni per punto, compilate durante il lavoro (vedi sotto).

### 1. FO-5-01 campo del codice di tracciamento

- MISURATO (Playwright su :8091, `scratchpad/w3f/m1.mjs`, HTTP 200): il campo `#ticket-track-code` e' 16x49 px a 320 (genitore 288) e a 375 (genitore 343);
  432x48 a 576 e 624x48 a 768. Il genitore ha `flex-direction: column` sotto 576 px.
- MISURATO: le regole che impongono `width` sul campo (CSS compilato `app-eEhVjZ6a.css`):
  `body:has(.page-content[data-dc]) .form-control { width: 100% }`, `body:has(.page-content[data-dc]) input[type="text"] { width: 100% }` e
  `body:has(.page-content[data-dc]) .input-group > .form-control { width: 1% }`. Vince l'ultima (specificita' piu' alta), come l'audit aveva dedotto.
  Il 16 e' il solo `padding-right: 16px` del campo, con contenuto a 0.

## Causa

Compilata per punto, vedi sotto.

## Decisioni

Compilate per punto.

## Criteri di accettazione verificabili

Compilati per punto.

## Test

Compilati per punto.

## Owned file scope (file esatti)

Compilato a fine lavoro.

## Esito

Compilato a fine lavoro.
