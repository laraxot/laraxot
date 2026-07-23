---
title: "Estrazione token class= — 7 HTML segnalazione (fonte .planning/research)"
type: concept
sources:
  - "../../../../../../.planning/research/local-segnalazione-dettaglio.html"
  - "../../../../../../.planning/research/local-segnalazione-01-privacy.html"
  - "../../../../../../.planning/research/local-segnalazione-02-dati.html"
  - "../../../../../../.planning/research/local-segnalazione-03-riepilogo.html"
  - "../../../../../../.planning/research/local-segnalazione-04-conferma.html"
  - "../../../../../../.planning/research/local-segnalazione-area-personale.html"
  - "../../../../../../.planning/research/local-ticket-list.html"
confidence: high
created: 2026-05-04
updated: 2026-05-04
tags: [bootstrap-italia, class-inventory, segnalazione, story-7-105]
related:
  - "./segnalazione-local-html-class-token-table.md"
  - "../entities/bootstrap-italia-class-inventory.md"
  - "../../../../../../_bmad-output/implementation-artifacts/7-105-design-comuni-segnalazione-static-pages-bootstrap-to-tailwind-class-map.md"
  - "../../../../../../_bmad-output/implementation-artifacts/7-103-segnalazione-01-privacy-tailwind-lit-html-audit-correction-plan.md"
  - "../../../../../../_bmad-output/implementation-artifacts/7-104-segnalazione-01-privacy-tailwind-correction-implementation.md"
---

# Estrazione riproducibile `class="..."` — sette pagine segnalazione

## Scopo

Inventario **per pagina** dei token presenti in `class="..."` sulle sette pagine Design Comuni, a partire da **HTML salvati in workspace** (`.planning/research/local-*.html`), per alimentare la story **7-105** senza duplicare il contenuto operativo di **7-103** / **7-104** (solo link e backlink).

## Metriche (pipeline attuale)

| Metrica | Valore |
|---------|--------|
| Occorrenze token (raw, dopo split spazi) | **7886** |
| Chiavi uniche prima del filtro «plausibile classe CSS» | **636** |
| Token unici dopo filtro | **529** |

Rigenerazione: dalla root del workspace eseguire `php bashscripts/extract-segnalazione-class-tokens.php` (scrive la tabella markdown in [segnalazione-local-html-class-token-table](./segnalazione-local-html-class-token-table.md)).

## Metodologia

1. **Input**: sette file `local-*.html` in `.planning/research/` (nomi allineati allo script in `bashscripts/extract-segnalazione-class-tokens.php`).
2. **Pre-processing**: rimozione blocchi `<script>` e `<style>`.
3. **Match**: `preg_match_all` su `class="([^"]*)"`, split su whitespace, aggregazione per token con elenco codici pagina.
4. **Filtro**: token con lunghezza ≥ 2, almeno una lettera `[a-zA-Z]`, pattern `^[a-zA-Z0-9:_\-]+$`, esclusione token solo numerici.

## Codici pagina (colonna «Pagine» nella tabella generata)

| Codice | File |
|--------|------|
| `det` | `local-segnalazione-dettaglio.html` |
| `p01` | `local-segnalazione-01-privacy.html` |
| `p02` | `local-segnalazione-02-dati.html` |
| `p03` | `local-segnalazione-03-riepilogo.html` |
| `p04` | `local-segnalazione-04-conferma.html` |
| `ap` | `local-segnalazione-area-personale.html` |
| `el` | `local-ticket-list.html` |

## Riconciliazione con [bootstrap italia class inventory](../entities/bootstrap-italia-class-inventory.md)

| Origine | Conteggio |
|---------|-----------|
| Entity (inventario URL / build, categorie, mapping Tailwind) | **486** classi uniche nel modello inventario |
| Script su HTML locali + filtri sopra | **529** token in tabella rigenerabile |

La differenza è attesa: **486** è un inventario semantico aggregato sulle stesse pagine ufficiali; **529** conta ogni stringa separata da spazio dentro `class="..."` dopo strip script/style (inclusi utility Tailwind miste a token BI, varianti `lg:` ecc.). Per **priorità mapping Tailwind** resta l’entity; la **tabella per token e per pagina** è [segnalazione-local-html-class-token-table](./segnalazione-local-html-class-token-table.md).

## Nota su `html_samples/`

Copie sotto `docs/design-comuni/screenshots/comparison/html_samples/` possono divergere per data di salvataggio; la **fonte canonica** per la pipeline numerata qui è **`.planning/research/`** come elencato negli `sources` del frontmatter.
