---
title: "FO commenti — dossier BMAD (blog, eventi, ticket)"
type: index
status: active
module: Comment
created: 2026-10-16
updated: 2026-10-26
tags: [bmad, comment, fo, filament-widget, second-brain]
qmd: "FO commenti blog eventi ticket Filament CommentsWidget BMAD dossier"
related:
  - ../bmad-dossier.md
  - ../../concepts/no-livewire-filament-widgets-only.md
  - ../../../../Fixcity/docs/bmad/inbound-marketing/afc-aferrari-asso-pattern-analysis.md
  - ../../../../Fixcity/docs/bmad/inbound-marketing/improvement-reasoning.md
issues: []
discussions: []
---

<!--
GateGuard facts (first-touch):
1. Duplicates/supersedes: none. Complements thin ../bmad-dossier.md and CMT-010..015;
   orthogonal to Fixcity inbound-marketing (editoriale, non thread commenti).
2. Linked from: ../bmad-dossier.md, ../README.md, Fixcity inbound epics (Epic VI),
   Sixteen blueprints inbound-fo-blog/events.
3. Why new file: Method track needs brief+prd+arch+epics under Comment owner;
   dossier dedicated to FO auth comments ≠ inbound content marketing.
4. User instruction: "crea tutte le bmad stories e tutti gli altri documenti,
   fa tutto con bmad + second brain + filament blueprint" + commenti autenticati
   blog/eventi/ticket + Filament widget only.
-->

# FO commenti — indice

Owner tecnico: **Comment**. Presentazione FO: **Sixteen** (blog/eventi) e
**Fixcity** (ticket). Authoring/moderazione BO: Filament Blueprint +
`CommentResource`.

## Perché esiste

Un cittadino autenticato deve poter dialogare su novità, eventi e segnalazioni
con un solo contratto UI: `CommentsWidget` (Filament), zero Livewire standalone.

## Artefatti

| File | Ruolo |
|---|---|
| [product-brief.md](./product-brief.md) | Problema, utenti, MVP |
| [research.md](./research.md) | Studio AFC / AFerrari / Asso → adattamento civico |
| [prd.md](./prd.md) | FR/NFR |
| [architecture.md](./architecture.md) | ADR + copertura FR |
| [ux-design.md](./ux-design.md) | Pattern UX FO |
| [filament-blueprint.md](./filament-blueprint.md) | Contratto widget + moderazione |
| [epics.md](./epics.md) | Mappa epic → story |
| [decision-log.md](./decision-log.md) | Decisioni lockate |
| [sprint-status.yaml](./sprint-status.yaml) | Stato sprint |

Stories: `stories/cmt-001`…`cmt-021` (tutte **kebab minuscolo**; ex `CMT-*`
rinominate 2026-10-16). Track FO: `cmt-016`…`cmt-021`; scrittura, risposte ed editor: [cmt-022](../../stories/cmt-022.fo-comments-write-reply-markdown-editor.story.md) (ADR-006..008).

Inbound editoriale (contenuti, non thread): Fixcity
`docs/bmad/inbound-marketing/`.

Dettaglio blog richiede `data-dc`:
[blog-detail-data-dc-comments.md](../../../../../Themes/Sixteen/docs/bmad/investigations/blog-detail-data-dc-comments.md)
· ADR-005 in [architecture.md](./architecture.md).
