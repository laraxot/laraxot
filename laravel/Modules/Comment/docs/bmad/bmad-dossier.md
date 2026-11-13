---
title: "Comment — BMAD dossier"
type: bmad-dossier
module: Comment
updated: 2026-10-16
tags: [bmad, comments, moderation, filament-widgets]
qmd: "Comment module product brief PRD architecture UX security epics gaps release"
issues: ["https://github.com/laraxot/base_fixcity_fila5/issues/383"]
discussions: ["https://github.com/laraxot/base_fixcity_fila5/discussions/392"]
status: active
---

# Comment — BMAD dossier

## Product brief / PRD
Enable contextual comments without weakening domain privacy. Logged-in users
comment on blog posts, events and tickets via Filament widgets only.

**Dossier FO completo:** [fo-comments/](./fo-comments/) (brief, research AFC/Asso,
prd, architecture, ux, filament-blueprint, epics, decision-log, sprint-status).

## Architecture / UX / security
Comment owns polymorphic persistence and moderation (`Commentable`,
`HasComments`, `CommentPolicy`). Commentator = `Modules\Fixcity\Models\User`
(`CanComment` + `InteractsWithComments`) — not `BaseUser`. FO SSOT =
`CommentsWidget`. `Http/Livewire` removed. Section always mounted (even empty
thread). Props: `readOnly`, `commentableType`, `commentableKey`.

## Epics and stories
- CMT-010…015: base (verify / superseded where noted in fo-comments/epics)
- **CMT-016**: always-show + props canoniche blog/eventi (implemented 2026-10-16)
- **CMT-017**: ticket props + guest CTA (implemented 2026-10-16)
- **CMT-018**: delete Http/Livewire (implemented 2026-10-16)
- **CMT-019…021**: moderation QA, a11y, E2E (ready-for-dev)

## Gaps / release
Rate limits, edit history, tenant isolation still need proof. A11y + E2E stories open.

## Integration points
- Blog: `Themes/Sixteen/.../blocks/blog/detail.blade.php`
- Events: `Themes/Sixteen/.../blocks/events/detail.blade.php`
- Tickets: `Modules/Fixcity/.../ticket-comments.blade.php` + filament infolist
