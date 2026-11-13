---
title: "Epics — commenti FO Filament"
type: epics
status: active
module: Comment
created: 2026-10-16
updated: 2026-10-26
tags: [bmad, epics, comment]
qmd: "epics FO comments CMT blog eventi ticket filament"
related:
  - ./prd.md
  - ./sprint-status.yaml
  - ../bmad-dossier.md
issues: []
discussions: []
---

# Epic map — FO comments

## Epic A — Contratto commentator (base)

| Story | Intent | Status |
|---|---|---|
| CMT-010 | Fixcity User `CanComment` | verify / keep |
| CMT-014 | No Spatie packages | verify |

## Epic B — Mount FO (blog / eventi / ticket)

| Story | Intent | Status |
|---|---|---|
| CMT-011 | Blog detail → widget | gap always-show → CMT-016 |
| CMT-012 | Events detail → widget | gap always-show → CMT-016 |
| CMT-013 | Ticket FO → widget | align props → CMT-017 |
| **CMT-016** | Sempre montare sezione + props canoniche blog/eventi | **ready-for-dev** |
| **CMT-017** | Parità ticket props + guest CTA | ready-for-dev |

## Epic C — Filament-only cleanup

| Story | Intent | Status |
|---|---|---|
| CMT-015 | Convert Livewire mounts → widget | done (verify) |
| **CMT-018** | Delete `Http/Livewire` + views livewire | ready-for-dev |

## Epic D — Qualità e fiducia

| Story | Intent | Status |
|---|---|---|
| **CMT-019** | Blueprint BO moderation QA | ready-for-dev |
| **CMT-020** | A11y UX Design Comuni FO comments | ready-for-dev |
| **CMT-021** | Pest E2E submit blog/event/ticket | ready-for-dev |

## Parallelismo (Owned Scope)

- CMT-016: Sixteen blog/events blades only
- CMT-017: Fixcity ticket-comments blades only
- CMT-018: Comment `Http/Livewire` + `resources/views/livewire`
- CMT-019: Comment Filament Resource docs/tests
- CMT-020: Sixteen + Comment lang/views a11y
- CMT-021: Comment/Fixcity/Blog tests

## Epic E — Scrivere, rispondere, editor (2026-10-26)

| Story | Intent | Status |
|---|---|---|
| **CMT-022** | Pubblicare, rispondere al singolo commento, `MarkdownEditor` Filament, ospite che torna sul post | **done** (prove nella story) |
| CMT-023 | Notifica all'autore del commento a cui si risponde (D-CMT-6) | attende la decisione dell'utente |
| CMT-024 | Screen reader, menzioni con l'editor, test di blocco per eventi e ticket (punti «Non verificato» di CMT-022) | backlog |
