---
title: "BMAD Planning: Rating"
type: bmad-planning
status: active
module: Rating
track: quick-flow
created: 2026-10-16
updated: 2026-10-16
tags: [bmad, planning, second-brain, filament-blueprint, rating]
qmd: "bmad planning rating scopo onde filament"
related:
  - ./bmad/bmad-dossier.md
  - ./bmad/brainstorming.md
  - ./bmad-brainstorming.md
  - ./bmad/filament-blueprint.md
---

# Obiettivo

Raccogliere feedback polimorfico affidabile dopo un esito civico eleggibile.

## Perché questo owner

- **Utenti:** cittadino post-chiusura, analyst PA
- **Track BMAD:** `quick-flow`
- **Religione:** Actions + Folio/Volt/Filament `XotBase*`; mai Controllers; docs solo in questo `docs/` (mai `bmad-output/`).

## Scope

### In
- contratti rating morph
- aggregate
- resource BO

### Out
- decidere eleggibilita (owner Fixcity)
- rating su pending

## Onde di delivery

| Wave | Deliverable | Owner |
|---|---|---|
| 1 | Contratto eleggibilita host | Rating + Fixcity |
| 2 | UI FO post-resolved | Rating + Sixteen |
| 3 | Aggregate/report BO | Rating |

## Filament blueprint

Resource/widget di competenza:

- RatingResource
- RatingMorphResource

Dettaglio contratto: [filament-blueprint.md](./bmad/filament-blueprint.md).

## Second brain

- Costituzione sintetica: questo file + brainstorming gemello.
- Decisioni durature -> `docs/bmad/decision-log.md` (o dossier topic se gia esiste).
- QMD: indicizzare titoli/qmd; ownership resta nel modulo/tema.

## Definition of Done (planning)

- [ ] Scope in/out condiviso e non contraddetto dal codice owner
- [ ] Onde senza dipendenze circolari cross-owner
- [ ] Blueprint Filament aggiornato (o esplicitamente N/A)
- [ ] Link bidirezionali ai dossier/topic gia esistenti
- [ ] Nessun artefatto in `bmad-output/`
