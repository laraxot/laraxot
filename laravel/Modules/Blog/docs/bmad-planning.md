---
title: "BMAD Planning — Blog"
type: bmad-planning
status: active
module: Blog
track: bmad-method
created: 2026-10-16
updated: 2026-10-16
tags: [bmad, planning, second-brain, filament-blueprint, blog]
qmd: "bmad planning blog scopo onde filament"
related:
  - ./bmad/bmad-dossier.md
  - ./bmad/blog-detail-planning.md
  - ./bmad/content-type-contract.md
  - ./bmad/blog-detail-brainstorming.md
  - ./bmad-brainstorming.md
  - ./bmad/filament-blueprint.md
---

# Obiettivo

Pubblicare contenuti editoriali comunali (novita/eventi come Article) indipendenti dal workflow Ticket.

## Perché questo owner

- **Utenti:** redattore, guest cittadino, SEO editor
- **Track BMAD:** `bmad-method`
- **Religione:** Actions + Folio/Volt/Filament `XotBase*`; mai Controllers; docs solo in questo `docs/` (mai `bmad-output/`).

## Scope

### In
- ciclo Article draft/publish
- content_type post|event
- permessi authoring
- feed/metadata

### Out
- regole Ticket
- timeline segnalazioni
- ownership FO Design Comuni (tema Sixteen)

## Onde di delivery

| Wave | Deliverable | Owner |
|---|---|---|
| 1 | Contratto content-type post/event | Blog |
| 2 | Dettaglio/list FO con Comment | Blog + Comment + Sixteen |
| 3 | SEO/sitemap/feed evidence | Blog + Seo |

## Filament blueprint

Resource/widget di competenza:

- ArticleResource
- CategoryResource
- BannerResource

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
