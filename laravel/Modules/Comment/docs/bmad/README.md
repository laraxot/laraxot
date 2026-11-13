---
title: "Comment — BMAD dossier"
type: bmad-module-dossier
module: Comment
status: baseline
updated: 2026-10-20
tags: [bmad, comments, moderation]
qmd: "Comment module purpose architecture PRD epics gaps release"
issues: ["https://github.com/laraxot/base_fixcity_fila5/issues/383"]
discussions: ["https://github.com/laraxot/base_fixcity_fila5/discussions/392"]
---
# Comment BMAD dossier

## Kit BMAD owner (canonico)

- [BMAD Planning](../bmad-planning.md)
- [BMAD Brainstorming](../bmad-brainstorming.md)
- [Filament blueprint](./filament-blueprint.md)
- [Project context](./project-context.md)
- [Decision log](./decision-log.md)
- Standing PHPStan: [phpstan-modules-zero-standing-order](../../../Xot/docs/wiki/memories/phpstan-modules-zero-standing-order.md)
- `graphify-out/`: [graphify-out-gitignore](../../../Xot/docs/bmad/graphify-out-gitignore.md)

**FO comments (auth blog/eventi/ticket):** [fo-comments/](./fo-comments/) —
brief, research AFC/Asso, prd, architecture, ux, filament-blueprint, epics,
stories CMT-016…021.

**Purpose:** polymorphic comments + moderation for domain records (Article,
Ticket) via Filament widgets only — host owns content, Comment owns thread chrome.

**Architecture:** Comment owns persistence/policy; Fixcity `User` is
`CanComment`; Sixteen/Fixcity mount `CommentsWidget`.

**Gaps aperti:** CMT-019 moderation QA, CMT-020 a11y, CMT-021 E2E; rate limit.

Vedi anche [bmad-dossier.md](./bmad-dossier.md).