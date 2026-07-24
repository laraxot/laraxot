---
title: "Debito D8 — Operator FO da Filament a Folio Themes"
type: how-to
tags: [technical-debt, folio, themes, operator, filament, bmad]
created: 2026-07-24
updated: 2026-07-24
qmd: "debt fo operator folio sixteen filament intervention signature playbook"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./gestionale-technical-debt.md"
  - "./debt-pest-field-service-coverage.md"
  - "../Themes/docs/wave1-fo-operator-note.md"
  - "../Themes/docs/gestionale-docs-index.md"
  - "../Themes/docs/gc-modules-runtime-matrix.md"
---

# Debito D8 — Operator field-service su Folio (Themes)

## Perché

SRC aveva panel **Operator**. In Wave 1 Laraxot l’operatore lavora su Filament (`intervention/admin`, `signature/admin`, …).  
Themes (Sixteen) = FO pubblico / Design Comuni — **non** ancora shell operator.

Debito di **UX/prodotto**, non di schema. Chiudere D1/D6 prima di promettere parity FO su documenti/timbrature.

## Stato attuale (baseline)

| Flusso | Dove oggi |
|---|---|
| Timbratura / note | `intervention/admin` |
| Firma / sketch | `signature/admin` |
| Commesse | `workorder/admin` |
| Assistente | `aiassistant/admin` |
| Login pubblico | User Folio (Auth = replica, vedi D10) |

Canon Themes: [wave1-fo-operator-note.md](../Themes/docs/wave1-fo-operator-note.md).

## Come risolvere (solo con mandato + story FO)

### Fase 0 — Discovery BMAD

1. Elenco flussi da portare (MVP operator):
   - start/stop punch
   - nota giornaliera
   - cattura firma su intervento
   - lista “mie commesse”
2. Per ognuno: resta Filament **oppure** Folio+Volt+widget.
3. Vincoli Laraxot:
   - no Controllers FO
   - Folio pages (no `@extends`/`@section` vietati nelle pages)
   - widget `Widgets\{Domain}\{Role}Widget` nel **modulo** owner
   - form schema in `*ResourceForm` / `*Form`, non duplicato nel widget
4. Mapping URL pubblici vs panel (documentare in Themes docs).

### Fase 1 — Spike tecnico (1 flusso)

Esempio: “start punch” su Folio Sixteen.

1. Page Folio minimale (mount + layout app).
2. Widget Intervention che chiama Action esistente (`QueueableAction`).
3. Auth: `actingAs` User; permission allineate a Policy modulo.
4. Pest Feature HTTP sulla route Folio + assert DB.
5. Tenere Filament come fallback admin.

### Fase 2 — Rollout

1. Aggiungere flussi uno alla volta (stessa checklist).
2. Feature flag / AccountFeatures se serve rollout graduale.
3. Aggiornare `wave1-fo-operator-note.md`: tabella “migrato vs ancora Filament”.
4. Playwright solo dopo Pest stabile (D6).

### Criterio parity

Stesso job (es. start time punch) completabile da FO con **stessi invarianti DB** dei test Pest dominio.

## DoD

- [ ] Story FO dedicata done (Issue+Discussion)
- [ ] ≥1 flusso critico su Folio con Pest
- [ ] Themes note aggiornata
- [ ] Nessuna BL di dominio sotto `Themes/`

## Non fare

- Copiare blade SRC nel tema come sostituto.
- Dichiarare Wave 1 “FO completo” senza flussi.
- Portare operator FO prima di D10 (login confuso) o D6 (zero regressione).
