---
title: "BMAD Planning — Comment"
type: bmad-planning
status: active
module: Comment
track: bmad-method
created: 2026-10-16
updated: 2026-10-16
tags: [bmad, planning, second-brain, filament-blueprint, comment]
qmd: "bmad planning comment scopo onde filament"
related:
  - ./bmad/fo-comments/filament-blueprint.md
  - ./bmad/ticket-comments-planning.md
  - ./bmad/fo-comments/README.md
  - ./bmad-brainstorming.md
  - ./bmad/filament-blueprint.md
---

# Obiettivo

Abilitare commenti contestuali su Article/Ticket senza indebolire privacy e policy del dominio host.

## Perché questo owner

- **Utenti:** cittadino autenticato, moderatore, guest (sola lettura)
- **Track BMAD:** `bmad-method`
- **Religione:** Actions + Folio/Volt/Filament `XotBase*`; mai Controllers; docs solo in questo `docs/` (mai `bmad-output/`).

## Scope

### In
- CommentsWidget XotBase
- CommentResource moderazione
- HasComments morph
- policy visibilita

### Out
- Http/Livewire legacy
- ownership lifecycle Ticket
- authoring Blog

## Onde di delivery

| Wave | Deliverable | Owner |
|---|---|---|
| 1 | Mount canonico FO sempre visibile | Comment + Sixteen |
| 2 | Moderazione BO + a11y | Comment |
| 3 | E2E guest/auth su blog/eventi/ticket | Comment |

## Filament blueprint

Resource/widget di competenza:

- CommentResource
- CommentsWidget

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
