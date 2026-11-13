---
title: "PRD — commenti FO autenticati"
type: prd
status: active
module: Comment
created: 2026-10-16
updated: 2026-10-16
tags: [bmad, prd, comment, fo]
qmd: "PRD commenti FO FR NFR blog eventi ticket Filament"
related:
  - ./product-brief.md
  - ./architecture.md
  - ./epics.md
  - ./README.md
issues: []
discussions: []
---

# PRD — commenti FO

## Vision

Chi è autenticato può commentare novità, eventi e ticket con un unico widget
Filament; chi è ospite legge e viene invitato al login.

## Functional requirements

| ID | Requirement |
|---|---|
| FR-01 | Auth su `/blog/{slug}` può inviare commento anche se thread vuoto |
| FR-02 | Auth su `/events/{slug}` può inviare commento anche se thread vuoto |
| FR-03 | Auth su `/tickets/{id}` può inviare commento |
| FR-04 | Guest vede commenti approvati + CTA login localizzata |
| FR-05 | Solo commenti approvati in FO (`CommentPolicy::see`) |
| FR-06 | Persistenza morph via `HasComments` su `Article` e `Ticket` |
| FR-07 | Moderazione BO via `CommentResource` (Form/Infolist/Table) |
| FR-08 | Zero mount `Http/Livewire` Comment in FO |
| FR-09 | Props widget: `readOnly`, `commentableType`, `commentableKey` |

## Non-functional

| ID | Requirement |
|---|---|
| NFR-01 | PHPStan Comment = 0 |
| NFR-02 | WCAG 2.1 AA sul form (label, focus, errori) |
| NFR-03 | Nessun tracking post-commento |
| NFR-04 | Rate limit submit (story dedicata) |
| NFR-05 | Docs owner = Comment / Sixteen / Fixcity |

## Out of scope

Mention FO, reaction FO, commenti anonimi, nurture email su commento.
